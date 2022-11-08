<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Note;
use App\Models\NoteCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);



        $user_1 = User::factory()->create([
            'name' => "Can Şık",
            'email' => "skcan17@gmail.com",
            "password" => bcrypt(123456)
        ]);
        // Note::factory(10)->create([
        //     "user_id" => $user_1->id
        // ]);


        $user_2 = User::factory()->create([
            'name' => "Jane Doe",
            'email' => "blegojcan@gmail.com",
            "password" => bcrypt(10203040)
        ]);
        // Note::factory(5)->create([
        //     "user_id" => $user_2->id
        // ]);


        $category_1 = NoteCategory::create(["title" => "education","status" => 1]);
        $category_2 = NoteCategory::create(["title" => "code","status" => 1]);
        $category_3 = NoteCategory::create(["title" => "shopping","status" => 1]);
        $category_4 = NoteCategory::create(["title" => "business","status" => 1]);
        $category_5 = NoteCategory::create(["title" => "fun","status" => 1]);
    }
}
