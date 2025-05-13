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

        return redirect()->route('messages.index')->with('success', 'Message created.');
    }

    public function index(Request $request)
    {
        $validated = $request->validate([
            'sort' => 'nullable|string|in:created_at,name',
            'sortDirection' => 'nullable|string|in:asc,desc',
        ]);

        $sort = $validated['sort'] ?? 'created_at';
        $sortDirection = $validated['sortDirection'] ?? 'desc';

        $messages = Message::orderBy($sort, $sortDirection)->paginate(10);

        $userIp = request()->ip();

        return inertia('messages/Index', [
            'messages' => $messages,
            'userIp' => $userIp,
            'sort' => $sort,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function destroy($id, Request $request)
    {
        $message = Message::findOrFail($id);

        if ($message->user_ip !== $request->ip() || now()->diffInMinutes($message->created_at) > 5) {
            return redirect()->back()->withErrors(['error' => 'You cannot delete this message.']);
        }

        $message->delete();
        return redirect()->back()->with('success', 'Message deleted successfully.');
    }
}
