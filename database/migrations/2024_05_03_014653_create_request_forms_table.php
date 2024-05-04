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
        Schema::create('request_forms', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('user_Id');
            $table->foreign('user_Id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('department_Id');
            $table->foreign('department_Id')->references('id')->on('departments')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->longText('reason');
            $table->enum('type', ['mission','leave']);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_forms');
    }
};
