<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => 'Study', 'color' => '#2563EB'],
            ['name' => 'Exams', 'color' => '#DC2626'],
            ['name' => 'Projects', 'color' => '#7C3AED'],
            ['name' => 'Personal', 'color' => '#059669'],
        ] as $category) {
            Category::updateOrCreate(
                [
                    'user_id' => null,
                    'name' => $category['name'],
                ],
                [
                    'color' => $category['color'],
                    'is_system' => true,
                ],
            );
        }

        $student = User::where('email', 'alammarimalak17@gmail.com')->first();
        $admin = User::where('email', 'malakammarie369@gmail.com')->first();
        $amal = User::where('email', 'amal@etuaide.test')->first();
        $inactive = User::where('email', 'inactive@etuaide.test')->first();

        if ($student) {
            $this->seedUserCategory($student, 'Internship', '#F97316');
            $this->seedUserCategory($student, 'Campus Club', '#14B8A6');
            $this->seedUserCategory($student, 'Deep Work', '#0F766E');
            $this->seedUserCategory($student, 'Exam Prep', '#E11D48');
            $this->seedUserCategory($student, 'Freelance', '#9333EA');
            $this->seedUserCategory($student, 'Wellness', '#F59E0B');
        }

        if ($admin) {
            $this->seedUserCategory($admin, 'Student Follow-up', '#2563EB');
            $this->seedUserCategory($admin, 'Email Campaigns', '#EC4899');
            $this->seedUserCategory($admin, 'Operations', '#0891B2');
            $this->seedUserCategory($admin, 'Reports', '#16A34A');
        }

        if ($amal) {
            $this->seedUserCategory($amal, 'Research', '#0EA5E9');
            $this->seedUserCategory($amal, 'Language Practice', '#8B5CF6');
        }

        if ($inactive) {
            $this->seedUserCategory($inactive, 'Backlog', '#64748B');
        }
    }

    protected function seedUserCategory(User $user, string $name, string $color): void
    {
        Category::updateOrCreate(
            [
                'user_id' => $user->id,
                'name' => $name,
            ],
            [
                'color' => $color,
                'is_system' => false,
            ],
        );
    }
}
