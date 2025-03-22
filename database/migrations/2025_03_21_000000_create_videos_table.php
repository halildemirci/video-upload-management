<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('owner');
            $table->string('category');
            $table->string('country');
            $table->string('city');
            $table->string('video_path');
            $table->string('lat');
            $table->string('lng');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('videos');
    }
};
