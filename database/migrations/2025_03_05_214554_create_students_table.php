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
        Schema::create('students', function (Blueprint $table) {
            $table->id();        
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('faculty_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->foreignId('classroom_id')->nullable()->constrained()->nullOnDelete();        
            $table->string('student_number')->unique();
            $table->unsignedInteger('semester')->default(1);
            $table->year('batch');       
            $table->foreignId('fee_group_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
