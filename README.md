# Support Tickets & Training Center

A Laravel 12 application for support tickets, company/user management, and video-based training. Supports Arabic and English, responsive layouts, and a collapsible sidebar.

## Features

- Ticket creation, attachments, quick view, conversations, and completion confirmation.
- Pending, in-progress, and completed ticket states.
- Training courses with modules, timestamped chapters, and module editing/deletion.
- Drag-and-drop video selection, preview, upload progress, and transfer speed.
- User editing, active/disabled accounts, and company management.
- Optional bilingual WhatsApp notifications through a local whatsapp-web.js bridge, without Meta's API.

## Requirements

PHP 8.2+ with Composer and Laravel's required extensions, including PDO SQLite for the default database. Use Node.js 20.19+ or 22.12+ and npm for Vite. The WhatsApp bridge also needs a supported Chromium environment through Puppeteer.

## Installation

From the project directory:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
npm run build
```

Copy the environment template only for a fresh installation; do not overwrite an existing .env. Configure APP_URL and your database before migrations. SQLite is the default. Keep environment secrets, databases, uploaded files, and WhatsApp sessions private.

## Run locally

```bash
composer run dev
```

Starts the PHP development server, default queue listener, Vite, and the dedicated WhatsApp worker. Open http://127.0.0.1:8000.

For a built frontend without Vite, run npm run build and then:

```bash
php scripts/serve.php
```

The server script sets PHP upload limits to 100 MB per file and 110 MB per request. Match these limits in production PHP/web-server configuration.

For trusted local-network testing:

```bash
TICKET_SERVER_HOST=192.168.1.20 php scripts/serve.php
```

Replace the IP with your computer's current LAN address. Phones must share the network and the firewall must allow access. Never expose the PHP development server to the public internet.

## WhatsApp notifications

- New ticket: all active administrators with valid phone numbers.
- First administrator opening a pending ticket: the ticket owner.
- Administrator completing a ticket: the ticket owner.

Each message contains Arabic and English, the ticket number, title, description, and authenticated ticket link. Refreshes and repeated close requests do not resend the same transition notification.

Install bridge dependencies:

```bash
npm ci --prefix whatsapp-service
```

Configure .env:

```dotenv
WHATSAPP_ENABLED=true
WHATSAPP_BRIDGE_URL=http://127.0.0.1:3001
WHATSAPP_BRIDGE_TOKEN=replace-with-a-long-random-secret
WHATSAPP_COUNTRY_CODE=966
WHATSAPP_TICKET_BASE_URL=https://your-ticket-domain.example
```

Generate a secret locally with:

```bash
php -r 'echo bin2hex(random_bytes(32)), PHP_EOL;'
```

Do not commit or share the secret. Start the bridge:

```bash
php artisan config:clear
npm start --prefix whatsapp-service
```

Open /whatsapp as an administrator and scan the QR from WhatsApp → Linked devices → Link a device. Add valid phone numbers to recipient accounts. Saudi local numbers beginning with 05 are normalized to 9665…; other countries need their international country code.

Keep the bridge running. The composer run dev command starts the WhatsApp worker; otherwise start it separately:

```bash
php artisan queue:work whatsapp --queue=whatsapp --tries=1 --timeout=600
```

Run only one WhatsApp worker. Links must be reachable from recipients' phones: localhost cannot work on another device. Use a LAN address for same-network testing or an HTTPS domain for remote users.

### Troubleshooting and security

- Connected but no messages: check the dedicated whatsapp queue worker is running.
- No new-ticket alert: check active administrator phone numbers; missing/invalid numbers are skipped.
- No opened alert: it fires only on the first administrator visit to a pending ticket.
- Delivery failure: inspect php artisan queue:failed and storage/logs/laravel.log before retrying; ambiguous failures can cause duplicates.
- Disconnected/authentication failed: restart the bridge and pair again if needed.
- Protect whatsapp-service/.auth, which grants access to the paired WhatsApp account.

This integration is unofficial. WhatsApp changes can interrupt it or cause account restrictions. Incoming WhatsApp replies are not imported. Browser-downloader dependencies have unresolved security advisories; review [setup and security notes](WHATSAPP_SETUP.md) before production use.

## Tests and frontend checks

```bash
php artisan test
npm run build
npm run check --prefix whatsapp-service
```

Focused module and WhatsApp tests:

```bash
php artisan test --filter='TicketWhatsAppTest|ModuleActionsTest'
```

## Pages and project structure

- /ticket: tickets; /ticket/{ticket}/reply: conversation.
- /training: courses; /training/{training}/{module}: module classroom.
- /users and /company: account and company management.
- /whatsapp: administrator-only connection page.
- app/Http/Controllers: request handling.
- app/Services and app/Jobs: WhatsApp message creation and queued delivery.
- resources/views, resources/css, and resources/js: interface.
- routes/web.php: application routes; use php artisan route:list for the complete list.

See [project exploration](PROJECT_EXPLORATION.md) and [WhatsApp setup](WHATSAPP_SETUP.md). Static design alternatives are in public/ticket-designs.html and public/course-designs.html.

## العربية

تطبيق لإدارة تذاكر الدعم الفني والدورات التدريبية، مع دعم العربية والإنجليزية وإدارة المستخدمين والشركات.

- للتشغيل المحلي استخدم composer run dev بعد تثبيت الاعتماديات وضبط .env وتشغيل الترحيلات.
- لتفعيل واتساب شغّل الخدمة داخل whatsapp-service ثم افتح /whatsapp وامسح رمز الربط.
- يجب تشغيل عامل قائمة whatsapp وإضافة أرقام صحيحة للمسؤولين والمستخدمين.
- تصل إشعارات التذاكر الجديدة إلى المسؤولين النشطين، وإشعارات فتح وإغلاق التذكرة إلى صاحبها، باللغتين مع رابط التذكرة.
- يجب أن يكون الرابط متاحًا من هاتف المستلم. استخدم عنوان الشبكة المحلية للتجربة أو نطاق HTTPS للنشر.
- التكامل غير رسمي؛ راجع تعليمات الإعداد والتنبيهات الأمنية قبل استخدامه في الإنتاج.
