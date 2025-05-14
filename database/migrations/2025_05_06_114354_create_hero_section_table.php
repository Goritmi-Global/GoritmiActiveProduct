<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeroSectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hero_section', function (Blueprint $table) {
            // ID column
            $table->string('id', 255)->primary();  // Primary key as varchar(255)
            
            // Language column
            $table->string('lang', 10)->nullable();  // Nullable lang column (for multi-language support)
            
            // Title and description columns
            $table->text('title');  // Non-nullable text column for title
            $table->text('description');  // Non-nullable text column for description
            
            // User ID (nullable, if you want to associate a user)
            $table->integer('user_id')->nullable();  // Nullable user_id
            
            // Timestamps
            $table->timestamps(0);  // Created_at and updated_at (default CURRENT_TIMESTAMP)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hero_section');
    }
}
