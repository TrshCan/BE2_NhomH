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
        Schema::create('deal_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id') // Cột khóa ngoại
                ->constrained('products', 'product_id') // Tham chiếu đúng cột 'products_id'
                ->onDelete('cascade');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deal_products');
    }
};