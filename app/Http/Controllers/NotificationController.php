<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function notify(Request $request)
    {
        $message = $request->input('message', 'Default notification message.');
        return redirect()->back()->with('notification', $message);
    }
}
