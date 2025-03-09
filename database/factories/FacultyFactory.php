<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str; 

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Faculty>
 */
class FacultyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            'name' => $name = $this->faker->unique()->randomElement([
                'Fakultas Ilmu Komputer',
                'Fakultas Teknik',
                'Fakultas Ilmu Sosial dan Ilmu Politik',
            ]),
            'slug' => Str::slug($name),
            'code' => Str::random(6),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($faculty) {
            $departments = match ($faculty->name) {
                'Fakultas Ilmu Komputer' => [
                    ['name' => 'Informatika'],
                    ['name' => 'Sistem Informasi'],
                ],
                'Fakultas Teknik' => [
                    ['name' => 'Teknik Industri'],
                    ['name' => 'Teknik Mesin'],
                    ['name' => 'Teknik Elektro'],
                ],
                'Fakultas Ilmu Sosial dan Ilmu Politik' => [
                    ['name' => 'Ilmu Komunikasi'],
                    ['name' => 'Ilmu Pemerintahan'],
                    ['name' => 'Hubungan Internasional'],
                ],
                default => [],
            };

            foreach ($departments as $department) {
                $faculty->departments()->create([
                    'name' => $department['name'],
                    'slug' => Str::slug($department['name']),
                    'code' => Str::random(6),
                ]);
            }
        });
    }
}