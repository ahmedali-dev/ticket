<?php

return [
    'connection' => 'WhatsApp connection',
    'missing_admin_phones' => ':count active administrator account(s) have no valid phone number. Add international phone numbers in user management to receive new-ticket notifications.',
    'pairing_help' => 'On your WhatsApp phone, open Linked devices → Link a device and scan the QR code below.',
    'checking' => 'Checking connection…',
    'qr_alt' => 'WhatsApp device pairing QR code',
    'notification_help' => 'Ticket notifications are queued. Recipients need a valid phone number and a reachable ticket URL.',
    'states' => [
        'starting' => 'Starting WhatsApp…',
        'awaiting_scan' => 'Scan the QR code to connect WhatsApp.',
        'connecting' => 'Signing in…',
        'connected' => 'WhatsApp is connected.',
        'authentication_failed' => 'Authentication failed. Restart the bridge to pair again.',
        'disconnected' => 'WhatsApp disconnected. Restart the bridge to reconnect.',
        'startup_failed' => 'WhatsApp could not start. Check the bridge terminal.',
        'unavailable' => 'WhatsApp bridge unavailable. Start the service on port 3001.',
    ],
    'created' => 'New support ticket submitted by :actor.',
    'opened' => 'Admin :actor has opened your ticket and started reviewing it.',
    'closed' => 'Admin :actor has closed your ticket and marked it as completed.',
    'number' => 'Ticket number: #:number',
    'title' => 'Title: :title',
    'description' => 'Description: :description',
];
