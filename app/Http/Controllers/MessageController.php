<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|max:255',
            'message' => 'required|max:1000',
            'image' => 'nullable|mimes:jpg,jpeg,png|max:4096', // 4MB
            'captcha' => 'required|captcha',
        ]);

        $message = Message::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'message' => $validated['message'],
            'user_ip' => $request->ip(),
        ]);

        return response()->json($message, 201);
    }

    public function index()
    {
        $messages = Message::orderBy('created_at', 'desc')->paginate(10);

        $userIp = request()->ip();
        
        return inertia('messages/Index', [
            'messages' => $messages,
            'userIp' => $userIp,
        ]);
    }

    public function destroy($id, Request $request)
    {
        $message = Message::findOrFail($id);

        if ($message->user_ip !== $request->ip() || now()->diffInMinutes($message->created_at) > 5) {
            return response()->json(['error' => 'You cannot delete this message.'], 403);
        }

        $message->delete();
        return response()->json(['message' => 'Message deleted successfully']);
    }
}
