<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Product::create([
            'name' => 'Paracetamol 500mg',
            'composition' => 'Paracetamol 500mg IP',
            'mrp' => 4500,
            'sales_rate' => 4000,
            'total_strip' => 185,
            'medicine_per_strip' => 10,
            'image_url' => 'https://example.com/images/paracetamol.jpg'
        ]);

        \App\Models\Product::create([
            'name' => 'Amoxicillin 250mg',
            'composition' => 'Amoxicillin Trihydrate IP',
            'mrp' => 8500,
            'sales_rate' => 8000,
            'total_strip' => 78,
            'medicine_per_strip' => 10,
            'image_url' => 'https://example.com/images/amoxicillin.jpg'
        ]);

        \App\Models\Product::create([
            'name' => 'Ibuprofen 400mg',
            'composition' => 'Ibuprofen BP',
            'mrp' => 3000,
            'sales_rate' => 2800,
            'total_strip' => 200,
            'medicine_per_strip' => 15,
            'image_url' => 'https://example.com/images/ibuprofen.jpg'
        ]);

        \App\Models\Product::create([
            'name' => 'Cetirizine 10mg',
            'composition' => 'Cetirizine Hydrochloride IP',
            'mrp' => 2500,
            'sales_rate' => 2200,
            'total_strip' => 300,
            'medicine_per_strip' => 10,
            'image_url' => 'https://example.com/images/cetirizine.jpg'
        ]);

        \App\Models\Product::create([
            'name' => 'Atorvastatin 10mg',
            'composition' => 'Atorvastatin Calcium IP',
            'mrp' => 12000,
            'sales_rate' => 11000,
            'total_strip' => 120,
            'medicine_per_strip' => 10,
            'image_url' => 'https://example.com/images/atorvastatin.jpg'
        ]);

        \App\Models\Product::create([
            'name' => 'Metformin 500mg',
            'composition' => 'Metformin Hydrochloride IP',
            'mrp' => 6000,
            'sales_rate' => 5500,
            'total_strip' => 250,
            'medicine_per_strip' => 15,
            'image_url' => 'https://example.com/images/metformin.jpg'
        ]);
    }
}
