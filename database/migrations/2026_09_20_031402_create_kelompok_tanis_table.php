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
        Schema::create('kelompok_tanis', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('wilayah_id');
            $table->unsignedBigInteger('master_admin_id');
            $table->timestamps();
            
            $table->foreign('wilayah_id')->references('id')->on('wilayahs')->onDelete('cascade');
            $table->foreign('master_admin_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompok_tanis');
    }
};
