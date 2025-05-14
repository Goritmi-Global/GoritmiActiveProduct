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
        Schema::create('languages', function (Blueprint $table) {
            $table->string('id', 255)->primary();
            $table->string('name', 100);
            $table->string('code', 100);
            $table->integer('rtl')->default(0);
            $table->tinyInteger('status')->nullable();
            $table->timestamps(0); // Ensures current timestamp with no fractional seconds
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
