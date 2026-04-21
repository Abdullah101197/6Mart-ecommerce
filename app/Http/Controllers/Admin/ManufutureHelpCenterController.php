<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManufutureHelpCenterController extends Controller
{
    public function index(Request $request)
    {
        $moduleId = $request->query('module_id', config('module.current_module_id'));

        // "Help Center" maps to customer contact/support messages in legacy admin.
        $src = route('admin.users.contact.contact-list');
        if ($moduleId !== null) {
            $src .= '?module_id=' . urlencode((string) $moduleId);
        }

        return view('admin-views.mf.helpcenter.index', compact('src', 'moduleId'));
    }
}

