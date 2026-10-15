<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Services\WhatsAppService;

class WhatsAppController extends Controller
{
    public function index(Request $request)
    {
        abort_unless($request->user()->isAdmin() || $request->user()->isSuperadmin(), 403);
        $adminsMissingPhone = User::whereIn('type', [User::TYPE_ADMIN, User::TYPE_SUPERADMIN])
            ->where('status', true)->get()->filter(fn ($user) => ! WhatsAppService::normalizePhone((string) $user->phone))->count();
        return view('whatsapp.index', compact('adminsMissingPhone'));
    }

    public function status(Request $request)
    {
        abort_unless($request->user()->isAdmin() || $request->user()->isSuperadmin(), 403);
        try {
            $response = Http::withToken(config('services.whatsapp.token'))
                ->connectTimeout(2)->timeout(4)
                ->get(rtrim(config('services.whatsapp.url'), '/').'/status')->throw();
            return response()->json($response->json())->header('Cache-Control', 'no-store');
        } catch (\Throwable $exception) {
            return response()->json(['state' => 'unavailable', 'qr' => null])->header('Cache-Control', 'no-store');
        }
    }
}
