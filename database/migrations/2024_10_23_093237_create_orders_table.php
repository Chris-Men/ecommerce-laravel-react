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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->integer('qty');
        $table->decimal('subtotal', 8, 2); // 🔹 Nuevo campo: subtotal antes del descuento
        $table->decimal('discount', 8, 2)->default(0); // 🔹 Nuevo campo: monto descontado
        $table->decimal('total', 8, 2); // total = subtotal - discount
        $table->datetime('delivered_at')->nullable();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('coupon_id')->nullable()->constrained()->cascadeOnDelete();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
