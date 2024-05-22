<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\StatutCommande;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        StatutCommande::factory(3)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // StatutCommande::factory(3)->create([
        //     'type_statut' => 'en cours', 
        //     'type_statut' => 'traitée', 
        //     'type_statut' => 'annulée'
        // ]);
    }
}
