<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
   {
       public function up()
       {
           Schema::table('pembeli', function (Blueprint $table) {
               $table->string('otp_code')->nullable()->after('password');
               $table->timestamp('otp_expires_at')->nullable()->after('otp_code');
               $table->string('login_otp_code')->nullable()->after('otp_expires_at');
               $table->timestamp('login_otp_expires_at')->nullable()->after('login_otp_code');
               $table->boolean('is_verified')->default(false)->after('login_otp_expires_at');
           });
       }

       public function down()
       {
           Schema::table('pembeli', function (Blueprint $table) {
               $table->dropColumn(['otp_code', 'otp_expires_at', 'login_otp_code', 'login_otp_expires_at', 'is_verified']);
           });
       }
   };