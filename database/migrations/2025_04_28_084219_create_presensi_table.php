<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('presensi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user')->onDelete('cascade');
            $table->unsignedBigInteger('id_setting')->onDelete('cascade');
            $table->date('jam_masuk')->default(DB::raw('CURRENT_DATE'));
            $table->date('jam_keluar')->nullable();
            $table->string('latitude');
            $table->string('longitude');
            $table->enum('status', ['tepat waktu','terlambat','alfa'])->default('alfa');
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_setting')->references('id')->on('setting_presensi');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};
