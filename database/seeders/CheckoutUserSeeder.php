<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class CheckoutUserSeeder extends Seeder
{
    /**
     * Create checkout test customer
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'customer@example.com',
            ],
            [
                'name' => 'Checkout Customer',
                'password' => Hash::make('Customer@123'),
            ]
        );

        $this->command->info('Checkout customer created successfully.');
        $this->command->info('Email: customer@example.com');
        $this->command->info('Password: Customer@123');
    }
}
