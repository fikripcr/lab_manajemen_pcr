<?php

namespace Database\Seeders;

use App\Models\Lab as LabModel;
use Illuminate\Database\Seeder;

class LabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $labNames = [
            'Laboratorium Komputer 1', 'Laboratorium Komputer 2', 'Laboratorium Komputer 3',
            'Laboratorium Jaringan', 'Laboratorium Multimedia', 'Laboratorium Elektronika',
            'Laboratorium Mikrokontroler', 'Laboratorium Robotika', 'Laboratorium Database',
            'Laboratorium Mobile Development', 'Laboratorium Web Development', 'Laboratorium AI',
            'Laboratorium IoT', 'Laboratorium Sistem Operasi', 'Laboratorium Keamanan Siber',
            'Laboratorium Basis Data', 'Laboratorium Pemrograman', 'Laboratorium Jaringan Komputer',
            'Laboratorium Teknik Informatika', 'Laboratorium Sistem Informasi', 'Laboratorium Riset 1',
            'Laboratorium Riset 2', 'Laboratorium Game Development', 'Laboratorium VR/AR',
            'Laboratorium Cloud Computing', 'Laboratorium Big Data', 'Laboratorium Machine Learning',
            'Laboratorium Deep Learning', 'Laboratorium Blockchain', 'Laboratorium Mobile App',
            'Laboratorium Web Design', 'Laboratorium Software Engineering', 'Laboratorium System Analysis',
            'Laboratorium Hardware', 'Laboratorium Software Testing', 'Laboratorium DevOps',
            'Laboratorium Cloud Infrastructure', 'Laboratorium Data Science', 'Laboratorium Cyber Security',
            'Laboratorium Network Security', 'Laboratorium Information Systems', 'Laboratorium Data Mining',
            'Laboratorium Data Analytics', 'Laboratorium Web Services', 'Laboratorium API Development',
            'Laboratorium Distributed Systems', 'Laboratorium Parallel Computing', 'Laboratorium Quantum Computing',
            'Laboratorium Embedded Systems', 'Laboratorium Wireless Networks', 'Laboratorium Satellite Communications',
            'Laboratorium Digital Signal Processing', 'Laboratorium Image Processing', 'Laboratorium Computer Vision',
            'Laboratorium Natural Language Processing', 'Laboratorium Speech Recognition', 'Laboratorium Pattern Recognition',
            'Laboratorium Biomedical Engineering', 'Laboratorium Control Systems', 'Laboratorium Power Electronics',
            'Laboratorium Renewable Energy', 'Laboratorium Smart Grid', 'Laboratorium Electric Vehicles',
            'Laboratorium Autonomous Systems', 'Laboratorium Mechatronics', 'Laboratorium Nanotechnology',
            'Laboratorium MEMS', 'Laboratorium Sensors', 'Laboratorium Actuators', 'Laboratorium Feedback Control',
            'Laboratorium Process Control', 'Laboratorium Real-time Systems', 'Laboratorium Embedded Linux',
            'Laboratorium RTOS', 'Laboratorium FPGA', 'Laboratorium ASIC Design', 'Laboratorium VLSI',
            'Laboratorium CAD Tools', 'Laboratorium PCB Design', 'Laboratorium Soldering Workshop',
            'Laboratorium 3D Printing', 'Laboratorium Rapid Prototyping', 'Laboratorium CAD/CAM',
            'Laboratorium CNC Machining', 'Laboratorium Laser Cutting', 'Laboratorium Woodworking',
            'Laboratorium Metalworking', 'Laboratorium Plastics Processing', 'Laboratorium Composites',
            'Laboratorium Materials Testing', 'Laboratorium Metallurgy', 'Laboratorium Ceramics',
            'Laboratorium Polymers', 'Laboratorium Biomaterials', 'Laboratorium Nanomaterials',
            'Laboratorium Thin Films', 'Laboratorium Coatings', 'Laboratorium Surface Engineering'
        ];

        $labLocations = [
            'Gedung A Lantai 1', 'Gedung A Lantai 2', 'Gedung A Lantai 3',
            'Gedung B Lantai 1', 'Gedung B Lantai 2', 'Gedung B Lantai 3',
            'Gedung C Lantai 1', 'Gedung C Lantai 2', 'Gedung C Lantai 3',
            'Gedung D Lantai 1', 'Gedung D Lantai 2', 'Gedung E Lantai 1',
            'Gedung F Lantai 1', 'Gedung G Lantai 1', 'Gedung H Lantai 1',
            'Gedung I Lantai 1', 'Gedung J Lantai 1', 'Gedung K Lantai 1',
            'Gedung L Lantai 1', 'Gedung M Lantai 1', 'Gedung N Lantai 1',
            'Gedung O Lantai 1', 'Gedung P Lantai 1', 'Gedung Q Lantai 1',
            'Gedung R Lantai 1', 'Gedung S Lantai 1', 'Gedung T Lantai 1'
        ];

        // Create 100 sample lab records
        for ($i = 1; $i <= 100; $i++) {
            $labName = $labNames[array_rand($labNames)] . ' ' . $i;
            $labLocation = $labLocations[array_rand($labLocations)];
            
            LabModel::create([
                'name' => $labName,
                'location' => $labLocation,
                'capacity' => rand(10, 50),
                'description' => fake()->sentence(10, true),
            ]);
        }

        $this->command->info('Created 100 sample lab records.');
    }
}