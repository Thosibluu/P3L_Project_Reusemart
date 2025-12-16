<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('security_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_type')->nullable(); // Pembeli/Penitip/Pegawai
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ip_address');
            $table->string('endpoint');
            $table->string('method'); // GET/POST
            $table->string('action_description'); // e.g., "Login Attempt", "Checkout"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_logs');
    }
};
