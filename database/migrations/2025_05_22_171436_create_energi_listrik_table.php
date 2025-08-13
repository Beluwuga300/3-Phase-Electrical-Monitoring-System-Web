<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('energi_listrik', function (Blueprint $table) {
            $table->id();
            $table->dateTime('waktu')->useCurrent();
            // $table->char('fasa'); // 'R', 'S', atau 'T'
            $table->float('tegangan_r');
            $table->float('tegangan_s');
            $table->float('tegangan_t');
            $table->float('arus_r');
            $table->float('arus_s');
            $table->float('arus_t');
            $table->float('daya_r');
            $table->float('daya_s');
            $table->float('daya_t');
            $table->float('energi_r');
            $table->float('energi_s');
            $table->float('energi_t');
            $table->float('faktor_daya_r')->nullable();
            $table->float('faktor_daya_s')->nullable();
            $table->float('faktor_daya_t')->nullable();
            $table->float('frekuensi_r')->nullable();
            $table->float('frekuensi_s')->nullable();
            $table->float('frekuensi_t')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('energi_listrik');
    }
};
