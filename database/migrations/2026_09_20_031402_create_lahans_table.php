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
        Schema::create('lahans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('kelompok_tani_id');
            $table->string('klasifikasi')->nullable();
            $table->unsignedBigInteger('uploaded_by');
            $table->timestamps();
            
            $table->foreign('kelompok_tani_id')->references('id')->on('kelompok_tanis')->onDelete('cascade');
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lahans');
    }
};
