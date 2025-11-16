<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['Present', 'Absent', 'Late']);
            $table->text('note')->nullable;
            $table->string('recorded_by');
            $table->timestamps();

            $table->unique('student_id', 'date');
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
