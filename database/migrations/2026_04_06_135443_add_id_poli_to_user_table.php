<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        // Ganti 'role' menjadi 'password' atau hapus saja ->after(...) nya
        $table->foreignId('id_poli')->nullable()->after('password')->constrained('poli')->cascadeOnDelete();
    });
}

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_poli']);
            $table->dropColumn('id_poli');
        });
    }
};