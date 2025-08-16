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
        Schema::table('users', function (Blueprint $table) {
            $table->string('department');
            $table->string('designation');
            $table->string('phone');
            $table->text('address');
            $table->boolean('is_active')->default(true);
            $table->enum('role', ['Admin', 'Manager', 'Employee'])->default('Employee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('department');
            $table->dropColumn('designation');
            $table->dropColumn('phone');
            $table->dropColumn('address');
            $table->dropColumn('is_active');
            $table->dropColumn('role');
        });
    }
};
