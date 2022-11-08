<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Note>
 */
class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'note' => fake()->text(50),
            'is_read' => $this->makeSpesificFaker([0,0,1,0,0]),
        ];
    }

    public function makeSpesificFakers($arr)
    {
        $randomNumber = random_int(0, count($arr));
        shuffle($arr);
        $strTags = "";
        for ($i = 0; $i < $randomNumber; $i++) {
            $strTags .= $arr[$i] . ",";
        }
        $result = rtrim($strTags, ",");
        return $result;
    }

    public function makeSpesificFaker($arr)
    {
        shuffle($arr);
        $result = $arr[0];
        return $result;
    }
}
