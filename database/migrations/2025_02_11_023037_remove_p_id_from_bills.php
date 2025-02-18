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
    Schema::table('bills', function (Blueprint $table) {
        $table->dropForeign(['p_id']);
        $table->dropColumn('p_id');
    });
}

public function down(): void
{
    Schema::table('bills', function (Blueprint $table) {
        $table->foreignId("p_id")->constrained("products");
    });
}

};
