<?php

namespace Database\Seeders;

use App\Models\Inventaris;
use App\Models\Lab;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $equipmentTypes = [
            'Laptop', 'Desktop Computer', 'Microscope', 'Projector', 
            'Whiteboard', 'Printer', 'Scanner', 'Camera', 'Audio System',
            'Table', 'Chair', 'Monitor', 'Keyboard', 'Mouse', 'Speakers',
            'Router', 'Switch', 'Server', 'Network Cable', 'UPS', 
            'Extension Cord', 'Power Strip', 'Headphones', 'Microphone',
            'TV', 'Smart Board', 'Document Camera', 'Laser Pointer', 
            'Computer Mouse Pad', 'Webcam', 'Calculator', 'Notebook',
            'Desk Lamp', 'Clock', 'Calendar', 'Stapler', 'Paper Shredder'
        ];

        $conditions = ['Baik', 'Rusak Ringan', 'Rusak Berat', 'Tidak Dapat Digunakan'];
        
        // Get all available lab IDs
        $labIds = Lab::pluck('lab_id')->toArray();

        if (empty($labIds)) {
            $this->command->info('No labs found. Creating sample labs first...');
            $this->call(LabSeeder::class);
            $labIds = Lab::pluck('lab_id')->toArray();
        }

        // Create 100 sample inventory records
        for ($i = 1; $i <= 100; $i++) {
            $equipmentType = $equipmentTypes[array_rand($equipmentTypes)];
            $equipmentName = $equipmentType . ' ' . rand(1, 1000);
            $equipmentTypeDetail = $equipmentTypes[array_rand($equipmentTypes)];
            
            Inventaris::create([
                'lab_id' => $labIds[array_rand($labIds)],
                'nama_alat' => $equipmentName,
                'jenis_alat' => $equipmentTypeDetail,
                'kondisi_terakhir' => $conditions[array_rand($conditions)],
                'tanggal_pengecekan' => fake()->dateBetween('-6 months', '+1 month'),
            ]);
        }

        $this->command->info('Created 100 sample inventory records.');
    }
}
