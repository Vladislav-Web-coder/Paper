<?php

namespace Database\Seeders;

<<<<<<< HEAD
use App\Models\Folder;
use App\Models\Note;
use App\Models\User;
use App\Models\Tag;
=======
use App\Models\User;
>>>>>>> 2fcb0d02d284ef33586cab99db3b7e99f28e3c86
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

<<<<<<< HEAD
        $tags = Tag::factory()->count(10)->create();
        User::factory()
            ->has(
                Folder::factory()
                ->count(10)
                ->hasAttached(
                    Note::factory()
                        ->count(45)
                        ->state(function (array $attributes, Folder $folder) {
                            return [
                                'user_id' => $folder->user()->first()->id,
                            ];
                        })
                    ->hasAttached($tags->random(rand(1, 3)))
                )
            )
            ->create([
                'name' => 'Vladislav',
                'email' => 'ww@mail.com',
                'password' => bcrypt('password'),
            ]);
=======
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
>>>>>>> 2fcb0d02d284ef33586cab99db3b7e99f28e3c86
    }
}
