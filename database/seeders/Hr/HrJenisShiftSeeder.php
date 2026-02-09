<?php
namespace Database\Seeders\Hr;

use App\Models\Hr\JenisShift;
use Illuminate\Database\Seeder;

class HrJenisShiftSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'jenis_shift' => 'Shift Normal',
                'jam_masuk'   => '08:00:00',
                'jam_pulang'  => '16:00:00',
                'is_active'   => 1,
            ],
            [
                'jenis_shift' => 'Shift Pagi',
                'jam_masuk'   => '07:00:00',
                'jam_pulang'  => '15:00:00',
                'is_active'   => 1,
            ],
            [
                'jenis_shift' => 'Shift Sore',
                'jam_masuk'   => '15:00:00',
                'jam_pulang'  => '22:00:00',
                'is_active'   => 1,
            ],
        ];

        foreach ($data as $item) {
            JenisShift::updateOrCreate(['jenis_shift' => $item['jenis_shift']], $item);
        }
    }
}
