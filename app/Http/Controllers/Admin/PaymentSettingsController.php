<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentSettingsController extends Controller
{
    public function index()
    {
        $mobilePaymentEnabled = $this->getMobilePaymentStatus();
        return view('admin.settings.payment', compact('mobilePaymentEnabled'));
    }

    public function update(Request $request)
    {
        $enabled = $request->has('mobile_payment_enabled') ? 1 : 0;
        DB::table('settings')->updateOrInsert(
            ['key' => 'mobile_payment_enabled'],
            ['value' => $enabled, 'updated_at' => now()]
        );
        return redirect()->route('admin.settings.payment')
            ->with('success', $enabled ? 'Paiement mobile activé.' : 'Paiement mobile désactivé.');
    }

    private function getMobilePaymentStatus()
    {
        $setting = DB::table('settings')->where('key', 'mobile_payment_enabled')->first();
        return $setting ? (bool) $setting->value : true;
    }

    public static function isMobilePaymentEnabled()
    {
        $setting = DB::table('settings')->where('key', 'mobile_payment_enabled')->first();
        return $setting ? (bool) $setting->value : true;
    }
}
