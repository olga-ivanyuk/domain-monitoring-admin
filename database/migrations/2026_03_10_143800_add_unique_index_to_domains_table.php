<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('domains', static function (Blueprint $table) {
            $table->unique(['user_id', 'domain'], 'domains_user_id_domain_unique');
            $table->dropIndex('domains_user_id_domain_index');
        });
    }

    public function down(): void
    {
        Schema::table('domains', static function (Blueprint $table) {
            $table->index(['user_id', 'domain'], 'domains_user_id_domain_index');
            $table->dropUnique('domains_user_id_domain_unique');
        });
    }
};
