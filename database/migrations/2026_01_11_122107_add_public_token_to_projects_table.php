<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('public_token', 64)->nullable()->unique()->after('notes');
        });

        // Generate tokens for existing projects using DB to avoid model scopes
        $projects = DB::table('projects')->whereNull('public_token')->get();
        foreach ($projects as $project) {
            DB::table('projects')
                ->where('id', $project->id)
                ->update(['public_token' => Str::random(32)]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('public_token');
        });
    }
};
