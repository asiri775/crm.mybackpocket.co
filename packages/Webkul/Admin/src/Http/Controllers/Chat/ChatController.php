<?php

namespace Webkul\Admin\Http\Controllers\Chat;

use App\Helpers\ChatHelper;
use App\Helpers\Constants;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Client\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Project\Repositories\ProjectRepository;

class ChatController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected ProjectRepository $projectRepository)
    {
        request()->request->add(['entity_type' => 'chat']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        return view('admin::chat.index');
    }

    public function chatSupport()
    {
        $user = auth()->user();
        $chat = ChatHelper::createOrFindChat(
            Constants::CHAT_TYPE_SUPPORT,
            $user->id,
            Constants::ADMIN_USER_ID
        );
        return redirect()->route('user.chat.open', $chat->uuid);
    }

    public function initChat($id)
    {
        $user = auth()->user();
        $chat = ChatHelper::createOrFindChat(
            Constants::CHAT_TYPE_NORMAL,
            $user->id,
            $id
        );
        return redirect()->route('user.chat.open', $chat->uuid);
    }

    public function search()
    {
        $search = '';
        if (isset($_GET['search'])) {
            $search = trim($_GET['search']);
        }

        $startDate = null;
        if (isset($_GET['start'])) {
            $startDate = trim($_GET['start']);
        }

        $endDate = null;
        if (isset($_GET['end'])) {
            $endDate = trim($_GET['end']);
        }
        
        $user = auth()->user();
        return response()->json([
            'success' => 'Messages fetched',
            'data' => ChatHelper::searchChats($user->id, $search, $startDate, $endDate)
        ]);
    }

    public function chatOpen($uuid)
    {
        $user = auth()->user();
        $chat = ChatHelper::findChat($uuid, $user->id);
        if ($chat == null) {
            return redirect()->route('admin.chat.index')->with('error', 'Chat not found');
        }
        return view('admin::chat.index', compact('chat', 'user'));
    }

    public function chatMessages($uuid)
    {
        $user = auth()->user();
        $chat = ChatHelper::findChat($uuid, $user->id);
        if ($chat == null) {
            return response()->json(['error' => 'Chat not found']);
        }
        $lastMsgTime = null;
        if (isset($_GET['last_message_time'])) {
            $lastMsgTime = trim($_GET['last_message_time']);
        }
        return response()->json([
            'success' => 'Messages fetched',
            'data' => ChatHelper::getChatMessages($chat->id, $user->id, $lastMsgTime)
        ]);
    }

    public function sendChatMessages($uuid)
    {
        $user = auth()->user();
        $chat = ChatHelper::findChat($uuid, $user->id);
        if ($chat == null) {
            return response()->json(['error' => 'Chat not found']);
        }
        $message = null;
        if (isset($_POST['message'])) {
            $message = trim($_POST['message']);
        }
        if ($message != null) {
            $chatMessage = ChatHelper::sendChatMessage($chat->id, $user->id, $message);
            return response()->json([
                'success' => 'Message Sent',
                'data' => ChatHelper::formatChatMessage($chatMessage, $user->id)
            ]);
        } else {
            return response()->json(['error' => 'Message is required']);
        }
    }

    public function markChatRead($uuid)
    {
        $user = auth()->user();
        $chat = ChatHelper::findChat($uuid, $user->id);
        if ($chat == null) {
            return response()->json(['error' => 'Chat not found']);
        }
        $readTime = null;
        if (isset($_GET['last_message_id'])) {
            $lastMessageId = trim($_GET['last_message_id']);
            $chatMessage = ChatMessage::where('id', $lastMessageId)->first();
            if ($chatMessage != null) {
                $readTime = $chatMessage->created_at;
            }
        }
        ChatHelper::updateLastReadTime($chat->id, $user->id, $readTime);
        return response()->json(['success' => 'Message read', 'data' => '']);
    }

}
