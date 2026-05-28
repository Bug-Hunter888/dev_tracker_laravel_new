<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $messages = auth()->user()->currentTeam
            ->messages()
            ->with('user')
            ->latest()
            ->take(60)
            ->get()
            ->reverse()
            ->values()
            ->map(fn($m) => [
                'id'         => $m->id,
                'content'    => $m->content,
                'user_name'  => $m->user->name,
                'user_id'    => $m->user_id,
                'created_at' => $m->created_at->format('H:i'),
            ]);

        return response()->json($messages);
    }

    public function store(Request $request)
    {
        $request->validate(['content' => 'required|string|max:1000']);

        $team    = auth()->user()->currentTeam;
        $message = $team->messages()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        $message->load('user');
        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'id'         => $message->id,
            'content'    => $message->content,
            'user_name'  => $message->user->name,
            'user_id'    => $message->user_id,
            'created_at' => $message->created_at->format('H:i'),
        ]);
    }
}
