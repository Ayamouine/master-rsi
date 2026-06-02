<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('etudiants', function (Blueprint $table) {
            if (!Schema::hasColumn('etudiants', 'profile_skills')) {
                $table->json('profile_skills')->nullable();
            }
            if (!Schema::hasColumn('etudiants', 'profile_interests')) {
                $table->json('profile_interests')->nullable();
            }
            if (!Schema::hasColumn('etudiants', 'profile_experience')) {
                $table->json('profile_experience')->nullable();
            }
        });

        Schema::table('admins', function (Blueprint $table) {
            if (!Schema::hasColumn('admins', 'profile_skills')) {
                $table->json('profile_skills')->nullable();
            }
            if (!Schema::hasColumn('admins', 'profile_interests')) {
                $table->json('profile_interests')->nullable();
            }
            if (!Schema::hasColumn('admins', 'profile_experience')) {
                $table->json('profile_experience')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('etudiants', function (Blueprint $table) {
            $table->dropColumn(['profile_skills', 'profile_interests', 'profile_experience']);
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['profile_skills', 'profile_interests', 'profile_experience']);
        });
    }
};
