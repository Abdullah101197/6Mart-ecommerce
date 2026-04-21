<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManufutureOmnichannelController extends Controller
{
    public function index(Request $request)
    {
        $moduleId = $request->query('module_id', config('module.current_module_id'));
        $type = $request->get('type', 'basic');

        // Closest existing "multi-channel" operational hub in legacy admin:
        // campaigns + notification/email/sms config screens.
        $src = route('admin.campaign.list', [$type]);
        if ($moduleId !== null) {
            $src .= '?module_id=' . urlencode((string) $moduleId);
        }

        return view('admin-views.mf.omnichannel.index', compact('src', 'moduleId', 'type'));
    }
}

