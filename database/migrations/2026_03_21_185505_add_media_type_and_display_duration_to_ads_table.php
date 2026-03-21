<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->string('media_type')->default('video')->after('title'); // video | image
            $table->unsignedInteger('display_duration')->nullable()->after('media_type'); // seconds, for images
        });
    }

    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn(['media_type', 'display_duration']);
        });
    }
};
