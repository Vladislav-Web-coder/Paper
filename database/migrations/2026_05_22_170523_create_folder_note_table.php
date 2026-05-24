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
        Schema::create('folder_note', function (Blueprint $table) {
            $table->foreignId('folder_id')->index()->constrained()->cascadeOnDelete();
            $table->foreignId('note_id')->index()->constrained()->cascadeOnDelete();
            $table->primary(['folder_id', 'note_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folder_note');
    }
};
