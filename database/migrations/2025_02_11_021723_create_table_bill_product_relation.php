<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('product_bill_relations', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id'); 
            $table->unsignedBigInteger('bill_id');

            $table->primary(['product_id', 'bill_id']); 

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('bill_id')->references('id')->on('bills')->onDelete('cascade');
            $table->float("number_of_packets")->nullable();
            $table->float("number_of_pieces")->nullable();
            $table->float("total_product_price");
            $table->timestamps();
        });


    }

    public function down(): void
    {
        Schema::dropIfExists('table_bill_product_relation');
    }
};
