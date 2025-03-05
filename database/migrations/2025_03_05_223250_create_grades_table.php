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
        Schema::create(table: 'grades', callback: function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'course_id')->constrained()->cascadeOnDelete();
            $table->foreignId(column: 'student_id')->constrained()->cascadeOnDelete();
            $table->foreignId(column: 'classroom_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger(column: 'grade')->default(value: 0);
            $table->unsignedInteger(column: 'section')->nullable();
            $table->string('category');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
