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
        Schema::create('model_datasets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dataset_id')->comment('Foreign key to datasets table');
            $table->foreign('dataset_id')->references('id')->on('file_datasets')->onDelete('cascade');
            $table->string('model_name')->nullable();
            $table->string('family_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_datasets');
    }
};
