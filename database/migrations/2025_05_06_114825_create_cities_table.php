<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            // ID column (primary key)
            $table->string('id', 6)->primary();  // Primary key as varchar(6)
            
            // City name column
            $table->string('name', 59)->nullable();  // Nullable name column
            
            // State-related columns
            $table->string('state_id', 8)->nullable();  // Nullable state ID column
            $table->string('state_code', 10)->nullable();  // Nullable state code column
            $table->string('state_name', 56)->nullable();  // Nullable state name column
            
            // Country-related columns
            $table->string('country_id', 10)->nullable();  // Nullable country ID column
            $table->string('country_code', 12)->nullable();  // Nullable country code column
            $table->string('country_name', 32)->nullable();  // Nullable country name column
            
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
        Schema::dropIfExists('cities');
    }
}
