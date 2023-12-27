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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('username');
            $table->enum('gender',['male', 'female','others'])->default('male');
            $table->string('email')->unique();
            $table->date('date_of_birth')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('cart_number')->nullable();
            $table->boolean('is_cart_verified')->default(0);
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('zip')->nullable();
            $table->string('role')->default('user');
            $table->string('currency_id')->nullable();
            $table->string('lang_code')->nullable();
            $table->boolean('status')->default(0)->comment('0:inactive, 1:active');
            $table->string('last_login_ip')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_from')->nullable();
            $table->string('subscription_package_id')->nullable();
            $table->text('fcm_token')->nullable();
            $table->string('notification_preference')->nullable();
            $table->boolean('is_active')->default(1);
            $table->rememberToken();
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
