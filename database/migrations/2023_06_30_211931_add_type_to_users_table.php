<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
    * Adds the 'type' column to the 'users' table, allowing values of 'admin', 'mod', or 'regular'.
    * The default value is set to 'regular'.
    */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('type', ['admin', 'mod', 'regular'])->default('regular');
        });
    }

    /**
     * Removes the 'type' column from the 'users' table.
    */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
