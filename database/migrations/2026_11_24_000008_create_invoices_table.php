<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 50)->unique();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();

            $table->decimal('dpp', 15, 2)->default(0);
            $table->decimal('discount_b2b', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('ppn_amount', 15, 2)->default(0);
            $table->decimal('pph23_amount', 15, 2)->default(0);
            $table->decimal('emeterai_amount', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);

            $table->string('va_number', 50)->nullable();
            $table->enum('payment_method', ['va_mandiri_blu', 'qris'])->default('va_mandiri_blu');
            $table->enum('payment_status', ['unpaid', 'paid', 'expired'])->default('unpaid');
            $table->timestamp('due_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('qr_pass_code', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
