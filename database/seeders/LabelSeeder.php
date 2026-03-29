<?php

namespace Database\Seeders;

use App\Models\Pemutu\Label;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if there are existing records, if so we might skip or clear them if needed.
        // For safe seeding, we will clear or use firstOrCreate
        Label::query()->delete();

        $labels = [
            [
                'name' => 'LAM Teknik',
                'color' => 'blue',
                'children' => ['C1', 'C2', 'C3'],
            ],
            [
                'name' => 'LAM Emba',
                'color' => 'indigo',
                'children' => ['C1', 'C2', 'C3', 'C4', 'C5', 'C6', 'C7', 'C8', 'C9'],
            ],
            [
                'name' => 'BAN PT',
                'color' => 'purple',
                'children' => ['C1', 'C2', 'C3', 'C4'],
            ],
            [
                'name' => 'SN DIKTI',
                'color' => 'pink',
                'children' => ['Standar Pendidikan', 'Standar Penelitian', 'Standar PkM'],
            ],
            [
                'name' => 'RENOP',
                'color' => 'orange',
                'children' => [],
            ],
        ];

        foreach ($labels as $parentData) {
            $parent = Label::create([
                'name' => $parentData['name'],
                'slug' => Str::slug($parentData['name']),
                'color' => $parentData['color'],
                'created_by' => 'System',
            ]);

            if (! empty($parentData['children'])) {
                foreach ($parentData['children'] as $childName) {
                    Label::create([
                        'parent_id' => $parent->label_id,
                        'name' => $childName,
                        'slug' => Str::slug($parentData['name'].' '.$childName),
                        'color' => $parentData['color'],
                        'created_by' => 'System',
                    ]);
                }
            }
        }
    }
}
