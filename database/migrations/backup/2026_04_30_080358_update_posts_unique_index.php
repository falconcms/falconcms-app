<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $indexes = Schema::getIndexListing('posts');

        Schema::table('posts', function (Blueprint $table) use ($indexes) {
            if (in_array('posts_slug_type_unique', $indexes)) {
                $table->dropUnique('posts_slug_type_unique');
            }

            if (in_array('posts_slug_type_deleted_at_unique', $indexes)) {
                $table->dropUnique('posts_slug_type_deleted_at_unique');
            }
            
            // Add new unique index including lang_code
            $table->unique(['slug', 'type', 'lang_code'], 'posts_slug_type_lang_unique');
        });
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropUnique('posts_slug_type_lang_unique');
            $table->unique(['slug', 'type'], 'posts_slug_type_unique');
        });
    }
};
