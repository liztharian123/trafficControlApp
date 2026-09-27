<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    $dept = Department::pluck('id', 'slug');

    User::firstOrCreate(
        ['email' => 'admin@trafficflow.test'],
        ['name' => 'Ava Admin', 'password' => 'password', 'role' => 'admin']
    );
    User::firstOrCreate(
        ['email' => 'quote@trafficflow.test'],
        ['name' => 'Quinn Quote', 'password' => 'password', 'department_id' => $dept['quote']]
    );
    User::firstOrCreate(
        ['email' => 'permit@trafficflow.test'],
        ['name' => 'Priya Permit', 'password' => 'password', 'department_id' => $dept['permit']]
    );
    User::firstOrCreate(
        ['email' => 'ops@trafficflow.test'],
        ['name' => 'Omar Ops', 'password' => 'password', 'department_id' => $dept['operations']]
    );

    User::factory()->count(4)->create(['department_id' => $dept['operations']]);
}
}
