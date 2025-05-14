<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {
            // ID column
            $table->increments('id');  // AUTO_INCREMENT for primary key
            
            // File columns
            $table->string('file_original_name', 255)->nullable();  // Original file name
            $table->string('file_name', 255)->nullable();  // Saved file name
            $table->integer('file_size')->nullable();  // File size as integer
            $table->string('extension', 10)->nullable();  // File extension
            
            // File type (optional, could be MIME type or another value)
            $table->string('type', 255)->nullable();
            
            // Timestamps
            $table->timestamps(0);  // Created_at and updated_at (default CURRENT_TIMESTAMP)
            
            // Soft delete column
            $table->timestamp('deleted_at')->nullable();  // Nullable for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uploads');
    }
}
