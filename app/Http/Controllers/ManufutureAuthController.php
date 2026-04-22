<?php

namespace App\Http\Controllers;

use App\CentralLogics\Helpers;
use App\Models\SubscriptionPackage;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ManufutureAuthController extends Controller
{
    public function create()
    {
        // Reuse the existing registration mechanics, but render with Manufuture labels + routes.
        $admin_commission = Helpers::get_business_settings('admin_commission');
        $business_name = Helpers::get_business_settings('business_name');
        $packages = SubscriptionPackage::where('status', 1)
            ->where('module_type', 'all')
            ->where('is_manufuture', 1)
            ->where(function ($q) {
                $q->whereNull('vendor_types')
                    ->orWhereJsonContains('vendor_types', 'manufacturer');
            })
            ->latest()
            ->get();

        $custome_recaptcha = new CaptchaBuilder;
        $custome_recaptcha->build();
        Session::put('six_captcha', $custome_recaptcha->getPhrase());

        return view('vendor-views.auth.general-info', [
            'custome_recaptcha' => $custome_recaptcha,
            'admin_commission' => $admin_commission,
            'business_name' => $business_name,
            'packages' => $packages,
            'applyTitle' => 'Manufuture registration',
            'applyHeadingLabel' => 'Manufuture',
            'applyRoutePrefix' => 'manufuture',
        ]);
    }

    public function store(Request $request)
    {
        $request->merge(['portal' => 'manufuture']);
        return app(VendorController::class)->store($request);
    }
}

