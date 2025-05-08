<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\ChatMessage; // Assuming you have a ChatMessage model
class ChatController extends Controller
{
    public function getUsers()
    {
        // Kiểm tra người dùng đã đăng nhập và là admin
        if (!Session::has('user_id') || Session::get('user_role') !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied.'
            ], 403);
        }

        try {
            // Truy vấn để lấy danh sách người dùng và tin nhắn mới nhất của họ
            $users = DB::select("
              SELECT users.id AS user_id, 
                users.name AS full_name, 
                latest_message.message, 
                latest_message.sent_at AS last_time
            FROM 
                users
            JOIN (
                SELECT 
                    sender_id, 
                    message, 
                    sent_at
                FROM 
                    chats c1
                WHERE 
                    sent_at = (
                        SELECT MAX(sent_at)
                        FROM chats c2
                        WHERE c2.sender_id = c1.sender_id
                    )
            ) AS latest_message
            ON 
                users.id = latest_message.sender_id
            WHERE 
                users.role = 'user'
            ORDER BY 
                latest_message.sent_at DESC;
            ");

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

    /**
     * Lấy tin nhắn giữa người dùng hiện tại và người được chọn
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMessages(Request $request)
    {
        // Kiểm tra phiên làm việc
        if (!Session::has('user_id')) {
            Log::error('No user session');
            return response()->json(['error' => 'No user session'], 403);
        }

        $sender_id = Session::get('user_id');
        $receiver_id = $request->input('receiver_id');

      
        Log::info("User ID: " . Session::get('user_id')); // Debug session
        Log::info("sender_id: $sender_id, receiver_id: $receiver_id");

        try {
            // Truy vấn các tin nhắn giữa hai người dùng
            $messages = DB::select("
                SELECT sender_id, receiver_id, message, sent_at
                FROM chats
                WHERE (sender_id = ? AND receiver_id = ?)
                OR (sender_id = ? AND receiver_id = ?)
                ORDER BY sent_at ASC;
            ", [$sender_id, $receiver_id, $receiver_id, $sender_id]);

            return response()->json([
                'status' => 'success',
                'messages' => $messages
            ]);
        } catch (\Exception $e) {
            Log::error("Database error: " . $e->getMessage());
            return response()->json(['error' => 'Database error'], 500);
        }
    }

    /**
     * Lưu tin nhắn mới
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeMessage(Request $request)
    {
       // Check for user session
    if (!Session::has('user_id')) {
        return response()->json(['error' => 'No user session'], 403);
    }

    $sender_id = Session::get('user_id');
    $receiver_id = $request->input('receiver_id');
    $message = $request->input('message');


   
    // if (!DB::table('users')->where('id', $receiver_id)->exists()) {
    //     return response()->json(['error' => 'Invalid receiver'], 400);
    // }

    try {
        // Insert message into chats table
        DB::table('chats')->insert([
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'message' => $message,
          
        ]);

        return response()->json(['status' => 'success']);
    } catch (\Exception $e) {
        Log::error("Failed to store message: " . $e->getMessage());
        return response()->json(['error' => 'Failed to store message'], 500);
    }
    }
}
