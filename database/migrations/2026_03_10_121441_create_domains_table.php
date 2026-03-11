<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('domains', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('domain', 255);
            $table->unsignedInteger('check_interval');
            $table->unsignedInteger('timeout');
            $table->enum('method', ['GET', 'HEAD'])->default('GET');
            $table->timestamps();

            $table->index('check_interval');
            $table->unique(['user_id', 'domain']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
