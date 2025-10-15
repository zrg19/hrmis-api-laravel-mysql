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
        Schema::create('customer_measurements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('phone');
            $table->text('address')->nullable();
            $table->string('kameezlength')->nullable();
            $table->string('teera')->nullable();
            $table->string('baazo')->nullable();
            $table->string('chest')->nullable();
            $table->string('neck')->nullable();
            $table->string('daman')->nullable();
            $table->string('kera')->nullable();
            $table->string('shalwar')->nullable();
            $table->string('pancha')->nullable();
            $table->string('pocket')->nullable();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_measurements');
    }
};
