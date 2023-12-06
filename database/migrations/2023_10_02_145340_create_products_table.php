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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->string('name');
            $table->string('imageUrl');
            $table->string('type')->nullable();
            $table->unsignedBigInteger('quantity');
            $table->enum('average_rating',[1,2,3,4,5]);
            $table->enum('discount',range(1,100));
            $table->unsignedDecimal('weight');
            $table->unsignedBigInteger('sales_count')->default(0);
            $table->text('description');
            $table->unsignedDecimal('price');
            $table->string('slug');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
