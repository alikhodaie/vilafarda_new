<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\SmsTemplates;

class SmsTemplateController extends Controller
{
    public function index()
    {
        $this->authorize('index', SmsTemplates::class);

        $templates = SmsTemplates::all();
        $grouped = SmsTemplates::grouped();

        return view('admin.sms-templates.index', compact('templates', 'grouped'));
    }
}
