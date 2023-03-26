<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use OpenAI\Laravel\Facades\OpenAI;

class ChatController extends Controller
{
    public function show(string $id = null): Response
    {
        $chat_list = Auth::user()->chats()->select('id', 'updated_at')->get();

        $chat = null;
        if($id) {
            $chat = Chat::findOrFail($id);
        }

        return Inertia::render('Chat/Show', [
            'chat_list' => $chat_list,
            'chat' => $chat
        ]);
    }

    public function store(Request $request, string $id = null): RedirectResponse
    {
        $messages = [];
        if($id) {
            $chat = Chat::findOrFail($id);
            $messages = $chat->context;
        }
        
        $messages[] = [
            'role' => 'user', 
            'content' => $request->input('prompt')
        ];

        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => $messages
        ]);

        $messages[] = [
            'role' => 'assistant', 
            'content' => $response->choices[0]->message->content
        ];

        $chat = Chat::updateOrCreate([
            'id' => $id,
            'user_id' => Auth::id(),
        ], [
            'context' => $messages
        ]);

        return Redirect::route('chat.show', ['id' => $chat->id]);
    }
}
