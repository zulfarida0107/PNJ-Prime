<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', [
                'mahasiswa',
                'mitra',
                'admin_p3m',
                'admin_rtpu',
                'pimpinan_rtpu',
                'direktur',
            ])->default('mahasiswa')->after('password');

            $table->string('nim_nip', 50)->nullable()->after('role');
            $table->string('prodi_unit', 120)->nullable()->after('nim_nip');
            $table->string('phone', 30)->nullable()->after('prodi_unit');
            $table->text('address')->nullable()->after('phone');
            $table->string('company_name', 150)->nullable()->after('address');
            $table->string('nib', 50)->nullable()->after('company_name');
            $table->string('npwp', 30)->nullable()->after('nib');
            $table->enum('kyc_status', ['pending', 'approved', 'rejected'])->default('pending')->after('npwp');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'nim_nip',
                'prodi_unit',
                'phone',
                'address',
                'company_name',
                'nib',
                'npwp',
                'kyc_status',
            ]);
        });
    }
};
