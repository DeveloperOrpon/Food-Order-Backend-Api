<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * `id`, `code`, `name`, `phonecode`,`flag`
     */
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id('id');
            $table->string('code');
            $table->string('name');
            $table->integer('phonecode');
            $table->string('flag');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
