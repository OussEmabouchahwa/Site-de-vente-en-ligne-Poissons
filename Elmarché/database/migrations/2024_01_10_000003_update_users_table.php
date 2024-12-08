<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'nom')) {
                $table->string('nom')->nullable();
            }
            if (!Schema::hasColumn('users', 'tel')) {
                $table->string('tel')->nullable();
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['vendeur', 'user'])->default('user');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nom', 'tel', 'role']);
        });
    }
};
