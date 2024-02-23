<?php

namespace Database\Seeders;

use App\Models\Feedback;
use Illuminate\Database\Seeder;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $feedbacks = [
            [
                'created_by' => 1,
                'company' => 'XYZ.IO',
                'moto' => 'Lorel ipsum',
                'name' => 'John Duo',
                'designation' => 'CO',
                'video_link' => 'https://www.youtube.com/watch?v=6UZds0GJxKI',
                'comments' => 'Lorel ipsum site amet'
            ],
            [
                'created_by' => 1,
                'company' => 'XYZ.HUB',
                'moto' => 'Lorel ipsum',
                'name' => 'John Doe',
                'designation' => 'CO',
                'video_link' => 'https://www.youtube.com/watch?v=6UZds0GJxKI',
                'comments' => 'Lorel ipsum site amet'
            ],
            [
                'created_by' => 1,
                'company' => 'XYZ.ONLINE',
                'moto' => 'Lorel ipsum',
                'name' => 'John Doa',
                'designation' => 'CO',
                'video_link' => 'https://www.youtube.com/watch?v=6UZds0GJxKI',
                'comments' => 'Lorel ipsum site amet'
            ]
        ];
        foreach($feedbacks as $feedback) {
            Feedback::create($feedback);
        }
    }
}
