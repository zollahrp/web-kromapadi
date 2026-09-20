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
        Schema::create('riwayat_scans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // KTD who scanned
            $table->unsignedBigInteger('lahan_id'); // Scanned field
            $table->string('penyakit');
            $table->decimal('akurasi', 5, 2)->nullable();
            $table->text('tindakan')->nullable();
            $table->string('foto_path')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('lahan_id')->references('id')->on('lahans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_scans');
    }
};
