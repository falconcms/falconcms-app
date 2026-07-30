<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slider_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->default('My designs');
            // A reusable design saved by the user: the slider's settings + slides.
            $table->json('settings')->nullable();
            $table->json('slides')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slider_templates');
    }
};
