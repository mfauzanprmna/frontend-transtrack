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
        Schema::create('file_datasets', function (Blueprint $table) {
            $table->id();
            $table->string('file_name')->comment('Name of the uploaded file');
            $table->string('date_column')->comment('Column name for date values');
            $table->string('sales_column')->comment('Column name for sales values');
            $table->string('store_column')->nullable();
            $table->string('family_column')->nullable();
            $table->string('family_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_datasets');
    }
};
