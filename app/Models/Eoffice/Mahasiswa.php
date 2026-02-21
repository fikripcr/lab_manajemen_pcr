<?php
namespace App\Models\Eoffice;

use App\Models\Shared\Mahasiswa as SharedMahasiswa;

/**
 * Alias for backward compatibility.
 * The canonical Mahasiswa model is now App\Models\Shared\Mahasiswa.
 * All new code should use App\Models\Shared\Mahasiswa directly.
 */
class Mahasiswa extends SharedMahasiswa
{
    //
}
