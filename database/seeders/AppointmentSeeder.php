<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $users = User::where('role', 'user')->get();

        // Create sample appointments for users
        $appointments = [
            [
                'title' => 'Project Meeting',
                'description' => 'Discuss project requirements and timeline',
                'location' => 'Conference Room A',
                'start_time' => Carbon::now()->addDays(2),
                'end_time' => Carbon::now()->addDays(4),
                'status' => 'confirmed',
            ],
            [
                'title' => 'Client Presentation',
                'description' => 'Present the new design mockups',
                'location' => 'Board Room',
                'start_time' => Carbon::now()->addDays(3),
                'end_time' => Carbon::now()->addDays(3),
                'status' => 'pending',
            ],
            [
                'title' => 'Team Standup',
                'description' => 'Daily standup meeting',
                'location' => 'Virtual - Zoom',
                'start_time' => Carbon::now()->addDays(1),
                'end_time' => Carbon::now()->addDays(1),
                'status' => 'confirmed',
            ],
            [
                'title' => 'One-on-One Review',
                'description' => 'Performance review with manager',
                'location' => 'Manager Office',
                'start_time' => Carbon::now()->addDays(5),
                'end_time' => Carbon::now()->addDays(5),
                'status' => 'pending',
            ],
            [
                'title' => 'Training Session',
                'description' => 'Training on new software tools',
                'location' => 'Training Room',
                'start_time' => Carbon::now()->addDays(7),
                'end_time' => Carbon::now()->addDays(7),
                'status' => 'pending',
            ],
        ];

        foreach ($users as $user) {
            foreach ($appointments as $appointment) {
                Appointment::create([
                    ...$appointment,
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}
