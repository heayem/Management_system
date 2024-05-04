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
        Schema::create('approval_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('approver_Id');
            $table->foreign('approver_Id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('request_Id');
            $table->foreign('request_Id')->references('id')->on('request_forms')->onDelete('cascade');
            $table->enum('status', ['approved', 'rejected']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_histories');
    }
};
