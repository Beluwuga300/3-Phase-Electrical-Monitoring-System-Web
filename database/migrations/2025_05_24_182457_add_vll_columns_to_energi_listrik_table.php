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
        Schema::table('energi_listrik', function (Blueprint $table) {
            $table->float('voltage_rs')->nullable();
            $table->float('voltage_st')->nullable();
            $table->float('voltage_tr')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('energi_listrik', function (Blueprint $table) {
            $table->dropColumn(['voltage_rs', 'voltage_st', 'voltage_tr']);
        });
    }
};
