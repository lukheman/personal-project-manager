<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add soft deletes to clients
        Schema::table('clients', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add soft deletes and deadline to projects
        Schema::table('projects', function (Blueprint $table) {
            $table->softDeletes();
            $table->date('deadline')->nullable()->after('notes');
        });

        // Add soft deletes to payments
        Schema::table('payments', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('deadline');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
