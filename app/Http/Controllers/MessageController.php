<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * عرض صفحة المحادثة وجلب الرسائل
     */
    public function index($receiver_id = null)
    {
        // جلب الرسائل المتبادلة بين المستخدم الحالي والمستقبل
        $messages = [];
        if ($receiver_id) {
            $messages = Message::where(function($query) use ($receiver_id) {
                $query->where('sender_id', Auth::id())
                      ->where('receiver_id', $receiver_id);
            })->orWhere(function($query) use ($receiver_id) {
                $query->where('sender_id', $receiver_id)
                      ->where('receiver_id', Auth::id());
            })->orderBy('created_at', 'asc')->get();
        }

        return view('eleveur.chats', compact('messages', 'receiver_id'));
    }

    /**
     * إرسال رسالة جديدة
     */
    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string|max:1000',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
        ]);

        return back()->with('success', 'تم إرسال الرسالة');
    }
}