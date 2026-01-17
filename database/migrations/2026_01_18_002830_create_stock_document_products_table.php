<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_document_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_document_id');
            $table->unsignedInteger('product_id');
            $table->string('product_number');
            $table->string('sku');
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->unsignedBigInteger('attribute_family_id')->default(1);
            $table->string('type')->default('simple');
            $table->decimal('price', 15, 2);
            $table->integer('quantity');
            $table->timestamps();

            $table->foreign('stock_document_id')->references('id')->on('stock_documents')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_document_products');
    }
};
