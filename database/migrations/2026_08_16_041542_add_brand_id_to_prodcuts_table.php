<?php

use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint; // for SQL
use MongoDB\Laravel\Schema\Blueprint; // for mongoDB
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $collection) {
            //  // Relationship One-to-Many (Eloquent Relationship)
            $collection->foreign("brand_id");
            $collection->index("brand_id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $collection) {
            $collection->dropForeign("brand_id");
            $collection->dropIndex("brand_id");
        });
    }
};
