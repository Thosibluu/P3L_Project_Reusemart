<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Ulangi logika ini untuk tabel 'penitips' atau 'pegawais' jika perlu
        Schema::table('pembeli', function (Blueprint $table) {
            $table->string('otp_code')->nullable(); // Menyimpan hash OTP
            $table->timestamp('otp_expires_at')->nullable();
            $table->boolean('is_2fa_verified')->default(false); // Reset saat logout
        });
    }

    public function down()
    {
        Schema::table('pembeli', function (Blueprint $table) {
            $table->dropColumn(['otp_code', 'otp_expires_at', 'is_2fa_verified']);
        });
    }
};
