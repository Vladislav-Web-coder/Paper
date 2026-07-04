<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Note extends Model
{
    protected $fillable = [
        'name',
        'content',
    ];

    protected function casts(): array
    {
        return [
            'is_pinned' => 'boolean',
        ];
    }
    protected static function booted()
    {
        static::deleting(function ($note){
            $note->tags()->detach();
            $note->foleders()->detach();
        });
        static::saved(function ($note){
            $note->updateSearchVector();
        });
    }

    public function folders(): BelongsToMany
    {
        return $this->belongsToMany(Folder::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function updateSearchVector(): void
    {
        $tagsName = $this->tags()->pluck('name')->implode(' ');

        DB::table('notes')
            ->where('id', $this->id)
            ->update([
                'search_vector' => DB::raw(sprintf(
                    "setweight(to_tsvector('simple', %s), 'A') || ".
                    "setweight(to_tsvector('simple', %s), 'B') || " .
                    "setweight(to_tsvector('simple', %s), 'C') || ",
                    DB::getPdo()->quote($this->name),
                    DB::getPdo()->quote($tagsName),
                    DB::getPdo()->quote($this->content ?? ''),
                ))
            ]);
    }
}
