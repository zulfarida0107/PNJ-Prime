<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->restrictOnDelete();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->foreignId('sub_category_id')->constrained('sub_categories')->restrictOnDelete();
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();

            $table->string('code', 40)->unique();
            $table->string('name', 180);
            $table->text('description')->nullable();
            $table->enum('order_type', ['ready_stock', 'pre_order', 'lab_reservation'])->default('ready_stock');
            $table->decimal('hpp', 15, 2)->default(0);
            $table->decimal('price_b2c', 15, 2)->default(0);
            $table->decimal('price_b2b', 15, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->string('image_path', 255)->nullable();
            $table->enum('status', [
                'draft',
                'menunggu_kurasi_rtpu',
                'revisi',
                'menunggu_sk_pimpinan',
                'tayang',
                'ditolak',
            ])->default('draft');
            $table->text('curation_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
