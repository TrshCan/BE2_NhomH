<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Chat extends Model
{
    protected $table = 'chats';
      // Lấy tin nhắn giữa hai người dùng
      public static function getMessagesBetweenUsers($sender_id, $receiver_id)
      {
          return DB::select("
              SELECT sender_id, receiver_id, message, sent_at
              FROM chats
              WHERE (sender_id = ? AND receiver_id = ?)
              OR (sender_id = ? AND receiver_id = ?)
              ORDER BY sent_at ASC;
          ", [$sender_id, $receiver_id, $receiver_id, $sender_id]);
      }
  
      // Lưu tin nhắn mới
      public static function storeMessage($sender_id, $receiver_id, $message)
      {
          return DB::table('chats')->insert([
              'sender_id' => $sender_id,
              'receiver_id' => $receiver_id,
              'message' => $message,
          ]);
      }
       // Phương thức lấy danh sách người dùng với tin nhắn mới nhất
    public static function getUsersWithLatestMessages()
    {
        return DB::select("
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
    }
}
