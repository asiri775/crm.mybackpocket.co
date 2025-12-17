<?php

namespace App\Helpers;

use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\ChatMessage;
use Str;

class ChatHelper
{

    public static function findChat($chatUuid, $userId)
    {
        // Check if the chat exists and the user is a member of the chat
        return Chat::where('uuid', $chatUuid)
            ->whereHas('members', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with(['members.user'])
            ->first();
    }

    public static function createOrFindChat($chatType, $userId, $recipientId)
    {
        // Check if a chat exists between the two users
        $chatModel = Chat::where('type', $chatType)
            ->where(function ($query) use ($userId, $recipientId) {
                $query->where(function ($q) use ($userId, $recipientId) {
                    $q->where('user_id', $userId)
                        ->where('recipient_id', $recipientId);
                })->orWhere(function ($q) use ($userId, $recipientId) {
                    $q->where('user_id', $recipientId)
                        ->where('recipient_id', $userId);
                });
            })
            ->first();

        // If chat does not exist, create a new one
        if (!$chatModel) {
            $chatModel = new Chat([
                'uuid' => (string) Str::uuid(),
                'type' => $chatType,
                'user_id' => $userId,
                'recipient_id' => $recipientId
            ]);
            $chatModel->save();
            //add members
            $chatMember = new ChatMember([
                'chat_id' => $chatModel->id,
                'user_id' => $userId,
                'is_admin' => true,
            ]);
            $chatMember->save();
            $chatMember = new ChatMember([
                'chat_id' => $chatModel->id,
                'user_id' => $recipientId
            ]);
            $chatMember->save();

            self::sendChatMessage(
                $chatModel->id,
                $userId,
                'Chat Created',
                null,
                null,
                true
            );

        }

        return $chatModel;
    }

    public static function updateLastReadTime($chatId, $userId, $readTime = null)
    {
        if ($readTime == null) {
            $readTime = date(Constants::PHP_DATE_FORMAT);
        }
        $chatMember = ChatMember::where('chat_id', $chatId)
            ->where('user_id', $userId)->first();
        if ($chatMember != null) {
            $chatMember->last_readed_at = $readTime;
            $chatMember->save();
            return true;
        }
        return false;
    }

    public static function sendChatMessage($chatId, $userId, $message, $messageType = null, $media = null, $isSystemMessage = false)
    {
        if ($messageType == null) {
            $messageType = Constants::CHAT_MESSAGE_TYPE_TEXT;
        }
        $messageTime = date(Constants::PHP_DATE_FORMAT);
        $chatMessage = new ChatMessage([
            'chat_id' => $chatId,
            'user_id' => $userId,
            'is_system_message' => $isSystemMessage,
            'message' => $message,
            'message_type' => $messageType,
            'media' => $media,
            'created_at' => $messageTime
        ]);
        $chatMessage->save();
        $chat = Chat::where('id', $chatId)->first();
        if ($chat != null) {
            $chat->last_message = $message;
            $chat->last_message_at = $messageTime;
            $chat->save();
        }
        ChatHelper::updateLastReadTime($chatId, $userId, $messageTime);
        return $chatMessage;
    }

    public static function chatInfo($userId, $chat)
    {
        $otherMember = null;
        $meMember = null;
        $totalUnReadMessages = 0;
        $name = null;
        $image = null;

        if (isset($chat)) {
            if (count($chat->members) > 0) {
                foreach ($chat->members as $member) {
                    if ($member->user_id != $userId) {
                        $otherMember = $member->user;
                    } else {
                        $meMember = $member;
                    }
                }
            }

            if ($otherMember != null) {
                $name = $otherMember->name;
                $image = $otherMember->getUserAvatar();
            }


            $lastMessageRead = $meMember->last_readed_at ?? date(Constants::PHP_DATE_FORMAT, strtotime("-10 years"));

            $totalUnReadMessages = ChatMessage::where('chat_id', $chat->id)
                ->where('created_at', '>', $lastMessageRead)->count();
            if ($totalUnReadMessages == null) {
                $totalUnReadMessages = 0;
            }

        } else {
            $chat = null;
        }

        if ($chat == null) {
            return ['name' => null];
        }

        return [
            'other_member_id' => $otherMember != null ? $otherMember->id : null,
            "name" => $name,
            "image" => $image,
            "last_message" => $chat->last_message,
            "last_message_at" => date(Constants::DISPLAY_TIME_FORMAT, strtotime($chat->last_message_at)),
            // 'time' => CodeHelper::timeAgo($chat->last_message_at),
            'time' => date('M d/y h:i A', strtotime($chat->last_message_at)),
            "unread_messages" => $totalUnReadMessages,
            "link" => route('user.chat.open', $chat->uuid),

            "last_message_time" => strtotime($chat->last_message_at),
            "chat_created_at" => strtotime($chat->created_at),
        ];
    }

    public static function formatChatMessage($chatMessage, $userId)
    {
        return [
            "message" => $chatMessage->message,
            'id' => $chatMessage->id,
            "created_at" => date(Constants::DISPLAY_TIME_FORMAT, strtotime($chatMessage->created_at)),
            // 'time' => CodeHelper::timeAgo($chatMessage->created_at),
            'time' => date('M d/y h:i A', strtotime($chatMessage->created_at)),
            "message_type" => $chatMessage->message_type,
            "is_system_message" => $chatMessage->is_system_message,
            "is_my_message" => $chatMessage->user_id === $userId,
            "sender" => [
                "name" => $chatMessage->user->name,
                "image" => $chatMessage->user->getUserAvatar()
            ]
        ];
    }

    public static function getChatMessages($chatId, $userId, $lastMsgTime = null)
    {
        $messageLimit = 1000;

        $chatMessages = ChatMessage::where('chat_id', $chatId);
        if ($lastMsgTime != null) {
            $chatMessages = $chatMessages->where('created_at', '<', $lastMsgTime);
        }
        $chatMessages = $chatMessages->orderBy('created_at', 'desc')->limit($messageLimit)->with('user')->get();
        $chats = [];
        foreach ($chatMessages as $chatMessage) {
            $chats[] = self::formatChatMessage($chatMessage, $userId);
        }
        return array_reverse($chats);
    }

    public static function searchChats($userId, $search = '', $startDate = null, $endDate = null)
    {
        $chats = [];

        $chatMembers = ChatMember::where('user_id', $userId)->get();

        $includedInChat = [];

        if ($chatMembers != null) {
            foreach ($chatMembers as $chatMember) {
                $chat = self::chatInfo($userId, $chatMember->chat);

                // Check if search term exists in chat name (case insensitive)
                if (empty($search) || stripos($chat['name'], $search) !== false) {
                    $chats[] = $chat;
                }
            }
        }

        foreach ($chats as $chat) {
            $includedInChat[] = $chat['other_member_id'];
        }

        if ($search != null) {
            //search chats
            $chatMessages = ChatMessage::where('message', 'like', '%' . $search . '%')
                ->groupBy('chat_id')->get();
            // print_r($chatMessages);
            // die;
            if ($chatMessages != null) {
                foreach ($chatMessages as $chatMessage) {
                    $chatMember = ChatMember::where('chat_id', $chatMessage->chat_id)
                        ->where('user_id', '!=', $userId)->first();
                    // dd($chatMember);
                    if ($chatMember != null) {
                        if (!in_array($chatMember->user_id, $includedInChat)) {
                            $chat = self::chatInfo($userId, $chatMember->chat);
                            $chats[] = $chat;
                        }
                    }
                }
            }
        }

        if ($startDate != null && $endDate != null) {
            $startDate = strtotime($startDate . " 00:00:00");
            $endDate = strtotime($endDate . " 23:59:59");
        }

        $filterChats = [];

        foreach ($chats as $chat) {

            if ($startDate != null && $endDate != null) {

                if ($chat['last_message_time'] >= $startDate && $chat['last_message_time'] <= $endDate) {
                    $filterChats[] = $chat;
                } else if ($chat['chat_created_at'] >= $startDate && $chat['chat_created_at'] <= $endDate) {
                    $filterChats[] = $chat;
                }

            } else {
                $filterChats[] = $chat;
            }

        }

        return $filterChats;
    }


}