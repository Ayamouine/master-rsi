<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->text('bio')->nullable()->after('nom');
            $table->string('phone', 30)->nullable()->after('bio');
            $table->string('email', 120)->nullable()->after('phone');
            $table->string('city', 80)->nullable()->after('email');
            $table->string('linkedin', 160)->nullable()->after('city');
            $table->string('github', 160)->nullable()->after('linkedin');
        });
    }

    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['bio', 'phone', 'email', 'city', 'linkedin', 'github']);
        });
    }
};
