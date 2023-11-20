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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->json('products_order');
            $table->float('total_price');
            // $table->string('address_billing');
            $table->string('address_shipping'); // địa chỉ giao hàng 
            $table->string('payment_method');
            $table->string('email');
            $table->string('phone_number');
            $table->text('note')->nullable();
            $table->enum("approval_status",[0,1,2,3,4])->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
