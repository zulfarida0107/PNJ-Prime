<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code', 40)->unique();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->enum('segment', ['b2c', 'b2b'])->default('b2c');
            $table->enum('order_type', ['ready_stock', 'pre_order', 'lab_reservation'])->default('ready_stock');
            $table->enum('status', [
                'menunggu_verifikasi',
                'menunggu_pembayaran',
                'diproses',
                'siap_diambil',
                'selesai',
                'dibatalkan',
            ])->default('menunggu_verifikasi');

            $table->date('booking_date')->nullable();
            $table->string('booking_slot', 60)->nullable();
            $table->string('attachment_path', 255)->nullable();
            $table->text('notes')->nullable();
            $table->text('shipping_address')->nullable();
            $table->unsignedInteger('estimasi_hari')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
