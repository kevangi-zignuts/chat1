<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public $user, $sender_id, $receiver_id;
    public $message = '';
    public $messages = [];

    public function dashboard()
    {
        $users = User::whereNotIn('id', [auth()->user()->id])->get();
        return view('dashboard', ['users' => $users]);
    }

    public function chat($id){
        $this->sender_id = auth()->user()->id;
        $this->receiver_id = $id;

        $messages = Message::where(function ($query) {
            $query->where('sender_id', $this->sender_id)
                ->where('receiver_id', $this->receiver_id);
        })->orWhere(function ($query) {
            $query->where('sender_id', $this->receiver_id)
                ->where('receiver_id', $this->sender_id);
        })->with('sender', 'receiver')->orderBy('id', 'asc')->get();

        foreach ($messages as $message) {
            $this->appendChatMessage($message);
        }

        $this->user = User::findOrFail($id);

        return view('chat', ['user' => $this->user, 'sender_id' => $this->sender_id, 'receiver_id' => $this->receiver_id, 'messages' => $this->messages]);
    }

    public function sendMessage(Request $request)
    {
        $message = Message::create([
            'message' => $request->input('message'),
            'sender_id' => $request->input('sender_id'),
            'receiver_id' => $request->input('receiver_id'),
        ]);
        $this->appendChatMessage($message);

        return response()->json(['message' => 'Form submitted successfully']);
    }

    public function appendChatMessage($message)
    {
        $this->messages[] = [
            'id' => $message->id,
            'message' => $message->message,
            'sender'   => $message->sender->name,
            'senderId'   => $message->sender->id,
            'receiver' => $message->receiver->name,
            'receiverId' => $message->receiver->id,
        ];
    }
}
