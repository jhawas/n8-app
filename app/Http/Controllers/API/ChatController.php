<?php

namespace App\Http\Controllers\API;

use App\ActiveConversation;
use App\Events\ConversationFlaggedEvent;
use App\Events\ConversationUnflagged;
use App\FlaggedConversation;
use App\Http\Controllers\Controller;
use App\Note;
use App\Services\MessageService;
use App\Services\ProfileService;
use App\Tenant\Conversation;
use App\Tenant\Message;
use App\Tenant\User;
use App\UserSentMessage;
use App\Website;
use Illuminate\Http\Request;

class ChatController extends Controller
{

    public $profile;

    public $msgService;

    public function __construct(ProfileService $profile, MessageService $msgService)
    {
        $this->profile = $profile;

        $this->msgService = $msgService;
    }

    public function lobby(MessageService $msgService)
    {
        $conversations = $msgService->getReturningConversations()
            ->merge($msgService->getConversations());
        return response()->json($conversations);
    }

    public function conversation(Website $website, $conversation_id)
    {
        $conversation = Conversation::whereId($conversation_id)->with(
            'messages.sender.avatar',
            'messages.recipient.avatar',
            'initiator.avatar',
            'interlocutor.avatar',
            'initiator.profile',
            'interlocutor.profile',
            'notes',
            'interlocutor.website',
            'flagged')->first();

        return response()->json(['conversation' => $conversation, 'website' => $website]);
    }

    public function send(Request $request, Website $website, $conversation_id)
    {
        $this->validate($request, [
            'text' => 'required|min:1',
        ]);

        $conversation = Conversation::with('returning_conversation')
            ->whereId($conversation_id)
            ->first();

        $message = new Message;
        $message->timeStamp = time();
        $message->senderId = $request->sender['id'];
        $message->recipientId = $request->recipient['id'];
        $message->text = $request->text;

        $conversation->messages()->save($message);

        if ($website->ftp && $request->file) {
            $this->msgService->sendAttachment($request->file, $message);
        }

        if ($conversation->returning_conversation) {
            $conversation->returning_conversation->already_sent = true;
            $conversation->returning_conversation->save();
        }

        $initiatorMessages = $conversation->messages()->where('senderId', $conversation->initiatorId)->get();

        $last_message = $conversation->last_message()->create([
            'initiatorMessageId' => $initiatorMessages ? $initiatorMessages->last()->id : 0,
            'interlocutorMessageId' => $message->id,
        ]);

        $conversation->update([
            'read' => 3,
            'lastMessageId' => $last_message->id,
        ]);

        $user = User::findOrFail($request->sender['id']);
        if ($user) {
            $this->profile->login($user);
        }

        UserSentMessage::create([
            'user_id' => auth()->user()->id,
            'website_id' => $website->id,
            'message_id' => $message->id,
        ]);

        $message = Message::whereId($message->id)->with('attachments')->first();

        return response()->json($message);
    }

    public function storeNotes(Request $request, Website $website, $conversation_id)
    {
        $conversation = Conversation::findOrFail($conversation_id);
        $data = [
            'website_id' => $website->id,
            'conversation_id' => $conversation->id,
            'note' => $request->note,
            'type' => $request->type,
        ];
        $note = Note::create($data);

        return response()->json($conversation->flagged);
    }

    public function flagConversation(Request $request, Website $website)
    {
        $conversation = Conversation::findOrFail($request->conversation_id);

        $conversation->website = $website;

        $conversation->notes = $request->notes;

        event(new ConversationFlaggedEvent($conversation));

        return response()->json($conversation);
    }

    public function unflagConversation(Website $website, $conversation_id)
    {
        $conversation = Conversation::findOrFail($conversation_id);
        $conversation->flagged->delete();

        event(new ConversationUnflagged($conversation));
        return response()->json('Conversation unflagged.');
    }

    public function updateFlagConversation(Request $request, Website $website, $flagged_conversation_id)
    {
        $conversation = FlaggedConversation::findOrFail($flagged_conversation_id);

        $conversation->update($request->all());

        return response()->json('Notes updated!', 200);
    }

    public function getMessages(Website $website, $conversation_id)
    {
        $conversation = Conversation::findOrFail($conversation_id);

        $messages = $conversation->messages()->with('sender.avatar', 'recipient.avatar', 'attachments')->get();

        return response()->json($messages, 200);
    }

    public function removeActiveConversation(Website $website, $conversation_id)
    {
        $active_conversation = ActiveConversation::where('website_id', $website->id)
            ->where('conversation_id', $conversation_id)->first();

        $active_conversation->delete();

        $conversation = Conversation::whereId($conversation_id)
            ->select('id', 'read', 'initiatorId', 'interlocutorId')
            ->where('read', 0)
            ->orWhere('read', 1)
            ->has('initiator')
            ->has('interlocutor')
            ->has('messages')
            ->with(['interlocutor.website',
                'initiator' => function ($i) {
                    $i->select('id', 'username');
                },
                'messages' => function ($q) {
                    $q->select('conversationId', 'senderId');
                }])->first();

        event(new \App\Events\UserLeaveChat($conversation));
        return response()->json('One conversation is inactive');
    }
}
