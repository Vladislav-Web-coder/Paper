<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Note extends Model
{
    use HasFactory;
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
            $note->folders()->detach();
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
        // Get tags directly form db (or empty string if none)
        $tagsName = $this->tags()->pluck('name')->implode(' ');

        DB::statement("
            UPDATE notes
            SET search_vector =
                setweight(to_tsvector('simple', coalesce(:name, '')), 'A') ||
                setweight(to_tsvector('simple', coalesce(:tags, '')), 'B') ||
                setweight(to_tsvector('simple', coalesce(:content, '')), 'C')
            WHERE id = :id
        ", [
            'name' => $this->name,
            'tags' => $tagsName,
            'content' => $this->content,
            'id' => $this->id,
        ]);
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if(!$value) return null;

                $timezone = config('app.timezone', 'UTC');

                return Carbon::parse($value, 'UTC')->setTimezone($timezone);
            },
            set: function ($value) {
                return Carbon::parse($value)->setTimezone('UTC');
            }
        );
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if(!$value) return null;

                $timezone = config('app.timezone', 'UTC');

                return Carbon::parse($value, 'UTC')->setTimezone($timezone);
            },
            set: function ($value) {
                return Carbon::parse($value)->setTimezone('UTC');
            }
        );
    }

}
