<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            // ID column (primary key)
            $table->string('id', 3)->primary();  // Primary key as varchar(3)
            
            // Country name column
            $table->string('name', 36)->nullable();  // Nullable name column
            
            // ISO columns
            $table->string('iso3', 4)->nullable();  // Nullable ISO3 code column
            $table->string('iso2', 4)->nullable();  // Nullable ISO2 code column
            
            // Numeric code column
            $table->string('numeric_code', 12)->nullable();  // Nullable numeric code column
            
            // Phone code column
            $table->string('phone_code', 16)->nullable();  // Nullable phone code column
            
            // Capital city column
            $table->string('capital', 20)->nullable();  // Nullable capital city column
            
            // Currency related columns
            $table->string('currency', 8)->nullable();  // Nullable currency column
            $table->string('currency_name', 39)->nullable();  // Nullable currency name column
            $table->string('currency_symbol', 15)->nullable();  // Nullable currency symbol column
            
            // TLD (Top-Level Domain) column
            $table->string('tld', 4)->nullable();  // Nullable TLD column
            
            // Native name column
            $table->string('native', 50)->nullable();  // Nullable native name column
            
            // Region and subregion columns
            $table->string('region', 8)->nullable();  // Nullable region column
            $table->string('subregion', 25)->nullable();  // Nullable subregion column
            
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
        Schema::dropIfExists('countries');
    }
}
