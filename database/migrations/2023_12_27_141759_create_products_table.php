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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->text('name')->nullable();
            $table->string('slug', 120)->unique();
            $table->timestamp('short_description')->nullable();
            $table->text('details')->nullable();
            $table->string('category_ids', 80)->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->string('added_by')->nullable();
            $table->string('units')->nullable();
            $table->integer('min_qty')->default(1);
            $table->boolean('refundable')->default(true);
            $table->string('images', 255)->nullable();
            $table->string('thumbnail', 255)->nullable();
            $table->string('featured', 255)->nullable();
            $table->string('flash_deal', 255)->nullable();
            $table->string('video_provider', 30)->nullable();
            $table->string('video_url', 150)->nullable();
            $table->boolean('variant_product')->default(false);
            $table->text('attributes')->nullable();
            $table->text('choice_options')->nullable();
            $table->text('variation')->nullable();
            $table->boolean('published')->default(false);
            $table->decimal('unit_price')->default(0);
            $table->decimal('purchase_price')->default(0);
            $table->decimal('regular_price')->default(0);
            $table->decimal('offer_price')->default(0);
            $table->string('tax')->default('0.00');
            $table->string('tax_type', 80)->nullable();
            $table->string('discount')->default('0.00');
            $table->string('discount_type', 80)->nullable();
            $table->integer('current_stock')->nullable();
            $table->boolean('free_shipping')->default(false);
            $table->string('attachment')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('featured_status')->default(true);
            $table->string('type', 255)->nullable();
            $table->boolean('on_sale')->default(true);
            $table->boolean('purchasable')->default(true);
            $table->boolean('manage_stock')->default(false);
            $table->text('date_on_sale_from')->default(0);
            $table->text('date_on_sale_to')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
