<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class WhatsAppService
{
    public static function normalizePhone(?string $phone): ?string
    {
        $phone = strtr($phone ?? '', array_combine(
            preg_split('//u', '٠١٢٣٤٥٦٧٨٩', -1, PREG_SPLIT_NO_EMPTY), range(0, 9)
        ));
        $phone = preg_replace('/\D/', '', $phone);
        if (str_starts_with($phone, '00')) $phone = substr($phone, 2);
        $country = (string) config('services.whatsapp.country_code', '966');
        if ($country === '966' && preg_match('/^0?5\d{8}$/', $phone)) {
            $phone = $country.ltrim($phone, '0');
        }

        return preg_match('/^[1-9]\d{7,14}$/', $phone) ? $phone : null;
    }

    public function send(string $phone, string $message, string $id): void
    {
        if (! config('services.whatsapp.token')) throw new RuntimeException('WhatsApp bridge token is missing.');
        // Keep long descriptions intact by sending consecutive parts.
        for ($offset = 0, $part = 0; $offset < Str::length($message); $offset += 3500, $part++) {
            Http::withToken(config('services.whatsapp.token'))
                ->connectTimeout(5)->timeout(60)
                ->post(rtrim(config('services.whatsapp.url'), '/').'/send', [
                    'phone' => $phone,
                    'message' => Str::substr($message, $offset, 3500),
                    'idempotencyKey' => $id.':'.$part,
                ])->throw();
        }
    }
}
