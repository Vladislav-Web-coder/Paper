<?php

namespace Database\Seeders;

use App\Models\Folder;
use App\Models\Note;
use App\Models\User;
use App\Models\Tag;
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
    }
}
