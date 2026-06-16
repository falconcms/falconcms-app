<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('custom_taxonomies')->insert([
            [
                'name' => 'Product Categories',
                'singular_name' => 'Product Category',
                'slug' => 'product_cat',
                'description' => 'Categories for products',
                'post_types' => json_encode(['product']),
                'hierarchical' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Product Tags',
                'singular_name' => 'Product Tag',
                'slug' => 'product_tag',
                'description' => 'Tags for products',
                'post_types' => json_encode(['product']),
                'hierarchical' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('custom_taxonomies')->whereIn('slug', ['product_cat', 'product_tag'])->delete();
    }
};
