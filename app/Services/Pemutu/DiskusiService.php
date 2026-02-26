<?php

namespace App\Services\Pemutu;

use App\Models\Pemutu\Diskusi;
use App\Models\Pemutu\IndikatorOrgUnit;
use Illuminate\Support\Facades\Storage;

class DiskusiService
{
    /**
     * Simpan pesan diskusi baru untuk IndikatorOrgUnit.
     */
    public function store(IndikatorOrgUnit $indOrg, array $data, $attachmentFile = null): Diskusi
    {
        // Bangun array links dari input
        $linksArray = [];
        if (! empty($data['diskusi_links_name']) && is_array($data['diskusi_links_name'])) {
            foreach ($data['diskusi_links_name'] as $i => $name) {
                $url = $data['diskusi_links_url'][$i] ?? null;
                if (! empty($name) && ! empty($url)) {
                    $linksArray[] = ['name' => $name, 'url' => $url];
                }
            }
        }

        $payload = [
            'pengirim_user_id' => auth()->id(),
            'jenis_pengirim'   => $data['jenis_pengirim'] ?? 'auditor',
            'jenis_diskusi'    => $data['jenis_diskusi'] ?? 'ami',
            'isi'              => $data['isi'],
            'attachment_link'  => ! empty($linksArray) ? $linksArray : null,
            'is_done'          => false,
        ];

        if ($attachmentFile) {
            $payload['attachment_file'] = $attachmentFile->store('public/pemutu/ami_diskusi');
        }

        return $indOrg->diskusi()->create($payload);
    }

    /**
     * Toggle status is_done pada sebuah diskusi.
     */
    public function toggleDone(Diskusi $diskusi): Diskusi
    {
        $diskusi->update(['is_done' => ! $diskusi->is_done]);
        return $diskusi;
    }
}
