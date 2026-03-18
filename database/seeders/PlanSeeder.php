<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Arzavo\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::create([
            'name' => 'Basic',
            'slug' => 'basic',
            'monthly_price' => 0,
            'yearly_price' => 0,
            'trial_days' => 7,
            'features' => ['basic_support'],
            'limits' => [
                'students' => 100,
                'storage_gb' => 2,
            ],
        ]);

        Plan::create([
            'name' => 'Pro',
            'slug' => 'pro',
            'monthly_price' => 999,
            'yearly_price' => 9999,
            'trial_days' => 7,
            'features' => ['custom_domain', 'analytics'],
            'limits' => [
                'students' => 500,
                'storage_gb' => 10,
            ],
        ]);

        Plan::create([
            'name' => 'Enterprise',
            'slug' => 'enterprise',
            'monthly_price' => 4999,
            'yearly_price' => 49999,
            'trial_days' => 7,
            'features' => ['all'], 
            'limits' => [
                'students' => 999999,
                'storage_gb' => 100,
            ],
        ]);
    }
}
