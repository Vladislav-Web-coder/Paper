<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->tsvector('search_vector')->nullable();
        });

        DB::statement('
            CREATE INDEX notes_search_vector_idx
            ON notes USING gin(search_vector)
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            DB::statement('DROP INDEX IF EXISTS notes_search_vector_idx');

            Schema::table('notes', function (Blueprint $table) {
                $table->dropColumn('search_vector');
            });
        });
    }
};
