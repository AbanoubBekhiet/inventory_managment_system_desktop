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
            Schema::rename('product_bill_relation', 'product_bill_relations');
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
            Schema::rename('product_bill_relations', 'product_bill_relation');

   
    }
};
