<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_degrees', function (Blueprint $table) {
            $table->string('id', 255)->primary();
            $table->string('doctor_id', 255);
            $table->string('degree_id', 255);
            $table->timestamps();

            // foreign keys
            $table->foreign('doctor_id')
                  ->references('id')->on('doctors')
                  ->onDelete('cascade');
            $table->foreign('degree_id')
                  ->references('id')->on('degrees')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_degrees');
    }
};

