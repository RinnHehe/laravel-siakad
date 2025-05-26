<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Inertia\Inertia;
use App\Models\Fee;
use App\Models\AcademicYear;
use App\Models\FeeStatus;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(FacultySeeder::class);
        $this->call(FeeGroupSeeder::class);

        User::factory()->create([
            'name' => 'Monkey D Luffy',
            'email' => 'luffy@siakubwa.test',
        ])->assignRole(
            Role::firstOrCreate([
                'name' => 'Admin',
            ])
        );

        $operator = User::factory()->create([
            'name' => 'Zoro',
            'email' => 'zoro@siakubwa.test',
        ])->assignRole(
            Role::firstOrCreate([
                'name' => 'Operator',
            ])
        );

        $operator->operator()->create([
            'faculty_id' => 1,
            'department_id' => 1,
            'employee_number' => str()->padLeft(mt_rand(0, 999999), 6, '0'),
        ]);

        $teacher = User::factory()->create([
            'name' => 'Sanji',
            'email' => 'sanji@siakubwa.test',
        ])->assignRole(
            Role::firstOrCreate([
                'name' => 'Teacher',
            ])
        );

        $teacher->teacher()->create([
            'faculty_id' => 1,
            'department_id' => 1,
            'teacher_number' => str()->padLeft(mt_rand(0, 999999), 6, '0'),
            'academic_title' => 'Asisten Ahli',
        ]);

        $student = User::factory()->create([
            'name' => 'Usop',
            'email' => 'usop@siakubwa.test',
        ])->assignRole(
            Role::firstOrCreate([
                'name' => 'Student',
            ])
        );

        $student->student()->create([
            'faculty_id' => 1,
            'department_id' => 1,
            'fee_group_id' => rand(1, 6),
            'student_number' => str()->padLeft(mt_rand(0, 999999), 6, '0'),
            'semester' => 1,
            'batch' => 2025,
        ]);
    }
}
