<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\UploadHelper;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        foreach ($data as $key => $value) {
            if ($request->hasFile($key)) {
                $value = UploadHelper::uploadImage($request->file($key), 'uploads/settings');
            }
            Setting::set($key, $value);
        }

        return back()->with('success', 'Website settings and contact information saved successfully!');
    }

    /**
     * Run live SMTP / Sendmail diagnostic test
     */
    public function testEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        $result = \App\Helpers\MailHelper::testConnection($request->test_email);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($result);
        }

        if ($result['success']) {
            return back()->with('success', $result['message']);
        } else {
            return back()->with('warning', $result['message']);
        }
    }
}
