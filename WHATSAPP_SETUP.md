# WhatsApp ticket notifications

The project uses a local `whatsapp-web.js` bridge, not Meta's API.

## What sends a message

- New ticket: every active admin/super-admin with a valid phone (duplicate numbers receive one notification).
- First admin opening a pending ticket: ticket owner gets an opened notification.
- Admin completing a ticket: ticket owner gets a closed notification.
- Refreshes and repeated close requests do not send the notification again.

Each message contains Arabic and English, the actor, ticket ID, title, description, and authenticated ticket link. Long messages are split into consecutive parts. No public access to ticket details is added.

## Setup

1. Run `php artisan migrate`.
2. Run `npm install --prefix whatsapp-service`.
3. Set these values in Laravel's `.env`:

```dotenv
WHATSAPP_ENABLED=true
WHATSAPP_BRIDGE_URL=http://127.0.0.1:3001
WHATSAPP_BRIDGE_TOKEN=your-long-random-secret
WHATSAPP_COUNTRY_CODE=966
WHATSAPP_TICKET_BASE_URL=https://your-ticket-website.example
```

The bridge loads this same `.env`. Never expose the token in frontend code or commit it. The ticket base URL must be reachable from recipients' phones. `localhost` and `127.0.0.1` links only work on the server computer. For LAN testing use the computer's reachable LAN address and bind Laravel accordingly; for remote users use an HTTPS domain.

This workstation is configured for `http://192.168.1.20:8000`. Start its LAN listener with `TICKET_SERVER_HOST=192.168.1.20 php scripts/serve.php`. Phones must be on the same trusted network; the computer must stay awake and its firewall must allow the connection. If DHCP changes the address, update both settings. This is a development server, not a production deployment; do not forward this port to the public internet.

4. Run `npm start --prefix whatsapp-service`.
5. Open `/whatsapp` as an administrator and scan the QR using WhatsApp → Linked devices → Link a device.
6. Run `php artisan queue:work whatsapp --queue=whatsapp --tries=1 --timeout=600`.
7. Set valid phone numbers in each recipient's user profile. Saudi `05…` and `5…` numbers are normalized to `9665…`; other numbers must include their international country code.

These notifications explicitly use the `whatsapp` database-backed queue connection even if other jobs use `sync`. Its 900-second reservation exceeds the 600-second worker timeout. Run only one WhatsApp worker: it maintains message order. Keep the bridge and worker running. Restart the bridge after authentication failures/disconnection.

`composer run dev` also starts the WhatsApp worker. Do not start another manual WhatsApp worker alongside it. The WhatsApp connection page warns when active administrators have missing/invalid phone numbers; a connected account alone does not mean recipients are configured.

## Reliability and privacy

- Pairing is a manual user action. Session data in `whatsapp-service/.auth` grants account access; protect it and exclude it from Git/backups shared with others.
- Recipients should expect these ticket notifications. Ticket descriptions are sent to administrators and the owner; do not enable this for accounts that should not receive that information.
- The bridge binds to localhost, authenticates requests, and remembers confirmed message IDs across restarts in `.sent.jsonl`.
- Failed deliveries go to Laravel's failed-jobs store. Inspect `php artisan queue:failed` before retrying. A crash after WhatsApp accepted a message but before it was recorded can still create uncertainty; exactly-once delivery is not guaranteed.
- Existing integer phone values are preserved as strings by the migration. Missing/zero numbers are skipped and logged by user/ticket ID.
- No WhatsApp replies are imported into tickets by this integration.
- `whatsapp-web.js` is unofficial; WhatsApp changes can interrupt it or lead to account blocking. Test with a dedicated number.
- Dependency audit: `basic-ftp` is overridden to a patched version. The browser downloader still depends on `extract-zip`, which has upstream high-severity archive-extraction advisories without a published fixed version at setup time. Do not supply untrusted browser archives/cache contents; review upstream fixes before production use.
