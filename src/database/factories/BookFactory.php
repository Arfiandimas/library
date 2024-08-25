<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "title" => $this->faker->title,
            "description" => $this->faker->realText($maxNbChars = 1000, $indexSize = 2),
            "publish_date" => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            "author_id" => Author::inRandomOrder()->limit(1)->pluck('id')->first()
        ];
    }
}
