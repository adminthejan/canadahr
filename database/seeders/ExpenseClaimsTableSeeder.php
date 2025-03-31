<?php

namespace Database\Seeders;

use App\Models\ExpenseClaim;
use Illuminate\Database\Seeder;

class ExpenseClaimsTableSeeder extends Seeder
{
    public function run()
    {
        // Generate 10 random expense claims using the factory
        ExpenseClaim::factory()->count(10)->create();
    }
}