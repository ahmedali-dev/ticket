const path = require('node:path');
const fs = require('node:fs');
const crypto = require('node:crypto');
require('dotenv').config({ path: path.join(__dirname, '../.env'), quiet: true });
const express = require('express');
const QRCode = require('qrcode');
const { Client, LocalAuth } = require('whatsapp-web.js');

const token = process.env.WHATSAPP_BRIDGE_TOKEN;
if (!token) throw new Error('Set WHATSAPP_BRIDGE_TOKEN in the Laravel .env file.');
let state = 'starting';
let qrImage = null;
const client = new Client({
    authStrategy: new LocalAuth({ dataPath: path.join(__dirname, '.auth') }),
    webVersionCache: { type: 'local', path: path.join(__dirname, '.cache') },
    puppeteer: { headless: true },
});
client.on('qr', async qr => {
    state = 'awaiting_scan';
    qrImage = await QRCode.toDataURL(qr);
    console.log('Scan the QR code from the Laravel WhatsApp settings page.');
});
client.on('authenticated', () => { state = 'connecting'; qrImage = null; });
client.on('ready', () => { state = 'connected'; qrImage = null; console.log('WhatsApp connected.'); });
client.on('auth_failure', () => { state = 'authentication_failed'; qrImage = null; });
client.on('disconnected', () => { state = 'disconnected'; qrImage = null; });

const app = express();
app.get('/', (req, res) => res.json({ service: 'Ticket WhatsApp bridge', settings: '/whatsapp on your Laravel application' }));
app.use((req, res, next) => {
    const actual = Buffer.from(req.headers.authorization || '');
    const expected = Buffer.from(`Bearer ${token}`);
    if (actual.length !== expected.length || !crypto.timingSafeEqual(actual, expected)) return res.sendStatus(401);
    res.set('Cache-Control', 'no-store');
    next();
});
app.use(express.json({ limit: '64kb' }));
app.get('/status', (req, res) => res.json({ state, qr: qrImage }));

const ledgerPath = path.join(__dirname, '.sent.jsonl');
const delivered = new Set();
if (fs.existsSync(ledgerPath)) {
    fs.readFileSync(ledgerPath, 'utf8').split('\n').filter(Boolean).forEach(line => {
        try { delivered.add(JSON.parse(line).key); } catch {}
    });
}
let sending = Promise.resolve();
app.post('/send', async (req, res) => {
    const { phone, message, idempotencyKey } = req.body;
    if (typeof phone !== 'string' || !/^[1-9]\d{7,14}$/.test(phone) ||
        typeof message !== 'string' || !message.trim() || message.length > 14000 ||
        typeof idempotencyKey !== 'string' || idempotencyKey.length > 100) {
        return res.status(422).json({ error: 'Invalid notification payload.' });
    }
    // Serialize sends and remember confirmed delivery requests across restarts.
    const send = async () => {
        if (delivered.has(idempotencyKey)) return { sent: true, duplicate: true };
        if (state !== 'connected') return { error: 'WhatsApp is not connected.', status: 503 };
        const number = await client.getNumberId(phone);
        if (!number) return { error: 'Recipient is not registered on WhatsApp.', status: 422 };
        await client.sendMessage(number._serialized, message);
        fs.appendFileSync(ledgerPath, JSON.stringify({ key: idempotencyKey, at: new Date().toISOString() }) + '\n', { mode: 0o600 });
        delivered.add(idempotencyKey);
        return { sent: true };
    };
    const pending = sending.then(send);
    sending = pending.catch(() => {});
    try {
        const result = await pending;
        res.status(result.status || 200).json(result);
    } catch (error) {
        console.error('WhatsApp send failed:', error.name);
        res.status(500).json({ error: 'Delivery failed; inspect before retrying.' });
    }
});

const server = app.listen(3001, '127.0.0.1', () => console.log('WhatsApp bridge: http://127.0.0.1:3001'));
client.initialize().catch(error => { state = 'startup_failed'; console.error('WhatsApp startup failed:', error.message); });
for (const signal of ['SIGINT', 'SIGTERM']) process.on(signal, async () => {
    server.close();
    await client.destroy().catch(() => {});
    process.exit(0);
});
