<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('antrian', function (Blueprint $table) {
            $table->id();
            $table->integer('nomor_antrian');
            $table->enum('status', ['Dipanggil', 'Belum Dipanggil', 'Dilayani', 'Selesai Dilayani', 'Tidak Datang']);
            $table->timestamps(); 
            $table->unsignedBigInteger('loket_id')->nullable();

            $table->foreign('loket_id')->references('id')->on('lokets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('antrian');
    }
};
