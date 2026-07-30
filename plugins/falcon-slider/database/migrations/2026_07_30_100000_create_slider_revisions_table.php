<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slider_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('slider_id')->constrained()->cascadeOnDelete();
            // A point-in-time snapshot of the slider taken on each save — the safety net.
            $table->string('name');
            $table->json('settings')->nullable();
            $table->json('slides')->nullable();
            $table->timestamps();
            $table->index(['slider_id', 'id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slider_revisions');
    }
};
