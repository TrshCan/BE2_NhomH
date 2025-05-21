<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id') // Cột khóa ngoại
            ->constrained('products', 'product_id') // Tham chiếu đúng cột 'products_id'
            ->onDelete('cascade'); // Liên kết với bảng products
            $table->string('model')->nullable();
            $table->string('connectivity')->nullable();
            $table->string('compatibility')->nullable();
            $table->string('weight')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_details');
    }
};