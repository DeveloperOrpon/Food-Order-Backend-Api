<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * `id`, `name`, `country_id`
     */
    public function up(): void
    {
        Schema::create('states', function (Blueprint $table) {
            $table->id('id');
            $table->string('name');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**$table->unsignedBigInteger('company_id')->nullable();
     * $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
