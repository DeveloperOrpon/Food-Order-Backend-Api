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
        Schema::table('brands', function (Blueprint $table) {
            $table->boolean('status')->default(1)->comment('1:Active ,0 :InActive');
            $table->boolean('feature')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->boolean('status')->default(1)->comment('1:Active ,0 :InActive');
            $table->boolean('feature')->default(0);
        });
    }
};
