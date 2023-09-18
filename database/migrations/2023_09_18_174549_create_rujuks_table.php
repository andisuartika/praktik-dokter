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
        Schema::create('rujuks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('periksa_id')->unsigned();
            $table->bigInteger('pasien_id')->unsigned();
            $table->date('tanggal');
            $table->string('tempat');
            $table->string('keterangan');
            $table->timestamps();

            $table->foreign('periksa_id', 'periksaid_foreign')->references('id')->on('pemeriksaans');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rujuks');
    }
};
