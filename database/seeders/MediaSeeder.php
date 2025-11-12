<?php

namespace Database\Seeders;

use App\Models\Pengumuman;
use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class MediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing pengumuman records to attach media
        $pengumumen = Pengumuman::all();
        
        if ($pengumumen->count() > 0) {
            foreach ($pengumumen as $index => $pengumuman) {
                // Create sample cover images for some pengumuman records
                if ($index % 3 == 0) { // Every 3rd record gets a cover
                    $this->createSampleMedia($pengumuman, 'info_cover');
                }
                
                // Create sample attachments for some pengumuman records
                if ($index % 2 == 0) { // Every 2nd record gets attachments
                    for ($i = 0; $i < 2; $i++) { // Add 2 attachments each
                        $this->createSampleMedia($pengumuman, 'info_attachment');
                    }
                }
            }
            
            $this->command->info('Created sample media for pengumuman records.');
        } else {
            $this->command->info('No pengumuman records found. Run PengumumanSeeder first.');
        }
    }
    
    private function createSampleMedia($pengumuman, $collectionName)
    {
        // Create a sample file (we'll use an existing image from the assets folder)
        $sourcePath = public_path('assets-guest/img/real-estate/property-exterior-3.webp');
        
        if (file_exists($sourcePath)) {
            // Create uploads directory if it doesn't exist
            $year = date('Y');
            $modelType = strtolower(class_basename($pengumuman));
            $folderPath = "uploads/{$modelType}/{$year}/{$collectionName}";
            $storagePath = storage_path('app/public/' . $folderPath);
            
            if (!is_dir($storagePath)) {
                mkdir($storagePath, 0755, true);
            }
            
            // Copy and rename the file
            $fileName = ($collectionName === 'info_cover' ? 'cover_' : 'attachment_') . time() . '_' . uniqid() . '.webp';
            $destinationPath = $storagePath . '/' . $fileName;
            
            copy($sourcePath, $destinationPath);
            
            // Create media record
            $relativePath = $folderPath . '/' . $fileName;
            
            Media::create([
                'file_name' => $fileName,
                'file_path' => $relativePath,
                'mime_type' => 'image/webp',
                'file_size' => filesize($destinationPath),
                'collection_name' => $collectionName,
            ]);
        }
    }
}