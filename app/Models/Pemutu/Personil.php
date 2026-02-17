<?php
namespace App\Models\Pemutu;

/**
 * Alias for backward compatibility.
 * The canonical model is now App\Models\Shared\Pegawai.
 * Pemutu "personil" (auditors/staff) are actually pegawai.
 *
 * NOTE: Pemutu code that references Personil is actually
 * referencing direct employees, not outsource workers.
 */
class Personil extends \App\Models\Shared\Pegawai
{
    //
}
