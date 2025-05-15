<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
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

    public function create() 
    {
         return inertia('messages/Create');
    }

    public function store(StoreMessageRequest  $request)
    {
        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'message' => $validated['message'],
            'user_ip' => $request->ip(),
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('messages', 'public');
        }

        Message::create($data);

        return redirect()->route('messages.index')->with('success', 'Message created.');
    }

    public function edit(Message $message)
    {
        return inertia('messages/Edit', [
            'message' => $message,
        ]);
    }

   public function update(UpdateMessageRequest $request, Message $message)
    {
        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'message' => $validated['message'],
            'user_ip' => $request->ip(),
        ];

         if ($request->boolean('delete_image')) {
            if ($message->image) {
                Storage::disk('public')->delete($message->image);
            }
            $data['image'] = null;
        }

        if ($request->hasFile('image')) {
            if ($message->image) {
                Storage::disk('public')->delete($message->image);
            }
            $data['image'] = $request->file('image')->store('messages', 'public');
        }

        $message->update($data);

        return redirect()->route('messages.index')->with('success', 'Message updated.');
    }


    public function destroy(Request $request, Message $message)
    {

        if ($message->user_ip !== $request->ip() || now()->diffInMinutes($message->created_at) > 5) {
            return redirect()->back()->withErrors(['error' => 'You cannot delete this message.']);
        }

        if ($request->hasFile('image')) {
            if ($message->image) {
                Storage::disk('public')->delete($message->image);
            }
            $data['image'] = $request->file('image')->store('messages', 'public');
        }

        $message->delete();
        return redirect()->back()->with('success', 'Message deleted successfully.');
    }
}
