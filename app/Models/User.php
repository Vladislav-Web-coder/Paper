<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
<<<<<<< HEAD
use App\Casts\UserSettingsCast;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
=======
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
>>>>>>> 2fcb0d02d284ef33586cab99db3b7e99f28e3c86
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
<<<<<<< HEAD
class User extends Authenticatable implements MustVerifyEmail
=======
class User extends Authenticatable
>>>>>>> 2fcb0d02d284ef33586cab99db3b7e99f28e3c86
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
<<<<<<< HEAD
            'settings' => UserSettingsCast::class,
            'two_factor_secret' => 'encrypted',
            'two_factor_recovery_codes' => 'encrypted:array',
        ];
    }
    public function folders(): HasMany
    {
        return $this->hasMany(Folder::class);
    }
    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }
=======
        ];
    }
>>>>>>> 2fcb0d02d284ef33586cab99db3b7e99f28e3c86
}
