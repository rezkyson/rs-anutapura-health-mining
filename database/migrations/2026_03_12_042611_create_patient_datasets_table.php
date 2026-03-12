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
        Schema::create('patient_datasets', function (Blueprint $table) {
            $table->id();
            $table->integer('age');
            $table->enum('gender', ['L', 'P']);
            $table->integer('blood_pressure');
            $table->integer('cholesterol');
            $table->integer('blood_sugar');
            $table->boolean('risk_result');
            $table->boolean('is_training')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_datasets');
    }
};
