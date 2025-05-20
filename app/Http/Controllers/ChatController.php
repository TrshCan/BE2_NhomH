<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Chat;
use App\Models\ChatMessage; // Assuming you have a ChatMessage model
class ChatController extends Controller
{
    // Lấy danh sách người dùng với tin nhắn mới nhất
    public function getUsers()
    {
        if (!Session::has('user_id') || Session::get('user_role') !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied.'
            ], 403);
        }

        try {
            $users = Chat::getUsersWithLatestMessages();

            if ($users) {
                return response()->json([
                    'status' => 'success',
                    'data' => $users
                ], 200);
            } else {
                return response()->json([
                    'status' => 'empty',
                    'message' => 'No users found'
                ], 404);
            }
        } catch (\Exception $e) {
            Log::error("Database query failed: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An internal server error occurred.'
            ], 500);
        }
    }

    // Lấy tin nhắn giữa hai người dùng
    public function getMessages(Request $request)
    {
        if (!Session::has('user_id')) {
            return response()->json(['error' => 'No user session'], 403);
        }

        $sender_id = Session::get('user_id');
        $receiver_id = $request->input('receiver_id');

        try {
            $messages = Chat::getMessagesBetweenUsers($sender_id, $receiver_id);

            return response()->json([
                'status' => 'success',
                'messages' => $messages
            ]);
        } catch (\Exception $e) {
            Log::error("Database error: " . $e->getMessage());
            return response()->json(['error' => 'Database error'], 500);
        }
    }

    // Lưu tin nhắn mới
    public function storeMessage(Request $request)
    {
        if (!Session::has('user_id')) {
            return response()->json(['error' => 'No user session'], 403);
        }

        $sender_id = Session::get('user_id');
        $receiver_id = $request->input('receiver_id');
        $message = $request->input('message');

        try {
            Chat::storeMessage($sender_id, $receiver_id, $message);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error("Failed to store message: " . $e->getMessage());
            return response()->json(['error' => 'Failed to store message'], 500);
        }
    }
}
