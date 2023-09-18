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
        Schema::create('pemeriksaans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dokter_id')->unsigned();
            $table->bigInteger('pasien_id')->unsigned();
            $table->date('tanggal')->default(now());
            $table->string('keluhan');
            $table->string('tekanan_darah');
            $table->string('nadi');
            $table->string('rr');
            $table->string('suhu');
            $table->string('fisik');
            $table->string('diagnosis');
            $table->string('tata_laksana');
            $table->boolean('rujuk');
            $table->decimal('tarif', 10, 2);
            $table->timestamps();

            $table->foreign('dokter_id', 'dokterid_foreign')->references('id')->on('users');
            $table->foreign('pasien_id', 'pasienid_foreign')->references('id')->on('pasiens');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaans');
    }
};
