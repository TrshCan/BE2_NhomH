<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->increments('id'); // int(11) primary key, auto-increment
            $table->unsignedInteger('sender_id'); // int(11) NOT NULL
            $table->unsignedInteger('receiver_id'); // int(11) NOT NULL
            $table->string('message', 255); // varchar(2000) NOT NULL
            $table->dateTime('sent_at')->default(DB::raw('CURRENT_TIMESTAMP')); 

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
