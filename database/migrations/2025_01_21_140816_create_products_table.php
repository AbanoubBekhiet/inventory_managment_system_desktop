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
            $table->foreignId("u_id")->constrained("users");
            $table->foreignId("cat_id")->nullable()->constrained("categories")->onDelete("no action");
            $table->string("name");
            $table->integer("n_pieces_in_packet");
            $table->float("original_packet_price");
            $table->float("selling_packet_price");
            $table->float("piece_price");
            $table->float("exicting_number_of_pieces");
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
