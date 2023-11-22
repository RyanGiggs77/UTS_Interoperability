<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * @return
     */
    
     public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama' );
            $table->string('email');
            $table->string('password');
            $table->text('alamat')->nullable();
            $table->integer('umur')->nullable();
            $table->string('kelas')->nullable();
            $table->integer('nohp')->nullable();
            $table->integer('tahun_bergabung')->nullable();
            
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }

};
