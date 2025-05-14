<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            // your primary key as varchar(255)
            $table->string('id', 255)->primary();
            $table->string('name', 255);
            $table->string('email', 255)->unique();
            $table->string('visit_fee', 255);
            $table->text('about');
            $table->string('personal_contact_number', 255);
            $table->string('emergency_contact_number', 255);
            $table->unsignedBigInteger('photo');
            $table->unsignedBigInteger('signature');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
