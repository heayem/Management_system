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
        Schema::create('employee_departments', function (Blueprint $table) {
            $table->unsignedBigInteger('user_Id');
            $table->foreign('user_Id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('department_Id');
            $table->foreign('department_Id')->references('id')->on('departments')->onDelete('cascade');
            $table->tinyInteger('approver')->unsigned()->default(0);
            $table->timestamps();
            $table->primary(['user_Id', 'department_Id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_departments');
    }
};
