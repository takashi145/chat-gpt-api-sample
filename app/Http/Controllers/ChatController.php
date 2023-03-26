<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChatRequest;
use App\Http\Requests\UpdateChatRequest;
use App\Models\Chat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use OpenAI\Laravel\Facades\OpenAI;

class ChatController extends Controller
{
    /**
     * チャット画面を表示
     */
    public function show(string $id = null): Response
    {
        // ログインユーザーの作成したチャット一覧を取得
        $chat_list = Auth::user()->chats()->select('id', 'name', 'updated_at')->get();

        $chat = null;
        if($id) {
            $chat = Chat::findOrFail($id);
        }

        return Inertia::render('Chat/Show', [
            'chat_list' => $chat_list,
            'chat' => $chat
        ]);
    }

    /**
     * チャットにメッセージとそれに対する返答を保存
     * 
     * 引数にidが指定されていれば、そのidを持つチャットにメッセージと返答を保存。
     * idがなければ新しくチャットを作成し、そのチャットにメッセージと返答を保存。
     */
    public function store(StoreChatRequest $request, string $id = null): RedirectResponse
    {
        $messages = [];
        if($id) {
            $chat = Chat::findOrFail($id);
            $messages = $chat->context;
        }
        
        $messages[] = [
            'role' => 'user', 
            'content' => $request->input('prompt'),
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
            'name' => 'New Chat',
            'context' => $messages
        ]);

        return Redirect::route('chat.show', ['id' => $chat->id]);
    }

    /**
     * 指定されたチャットの名前を変更
     */
    public function update(UpdateChatRequest $request, Chat $chat): RedirectResponse
    {
        $chat->name = $request->input('name');
        $chat->save();
        return Redirect::back();
    }

    /**
     * 指定されたチャットを削除
     */
    public function destroy(Chat $chat): RedirectResponse
    {
        $chat->delete();
        return Redirect::route('chat.show');
    }
}
