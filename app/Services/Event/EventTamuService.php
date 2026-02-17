<?php
namespace App\Services\Event;

use App\Models\Event\EventTamu;
use Illuminate\Support\Facades\DB;

class EventTamuService
{
    public function store(array $data): EventTamu
    {
        return DB::transaction(function () use ($data) {
            $tamu = EventTamu::create($data);

            if (isset($data['foto'])) {
                $tamu->addMedia($data['foto'])->toMediaCollection('guest_photo');
            }

            if (isset($data['ttd'])) {
                $tamu->addMedia($data['ttd'])->toMediaCollection('guest_signature');
            }

            return $tamu;
        });
    }

    public function update(EventTamu $tamu, array $data): EventTamu
    {
        return DB::transaction(function () use ($tamu, $data) {
            $tamu->update($data);

            if (isset($data['foto'])) {
                $tamu->clearMediaCollection('guest_photo');
                $tamu->addMedia($data['foto'])->toMediaCollection('guest_photo');
            }

            if (isset($data['ttd'])) {
                $tamu->clearMediaCollection('guest_signature');
                $tamu->addMedia($data['ttd'])->toMediaCollection('guest_signature');
            }

            return $tamu;
        });
    }

    public function destroy(EventTamu $tamu): void
    {
        DB::transaction(function () use ($tamu) {
            $tamu->delete();
        });
    }

    public function storeFromPublic(array $data): EventTamu
    {
        return DB::transaction(function () use ($data) {
            $tamu = EventTamu::create($data);

            if (! empty($data['foto'])) {
                $tamu->addMediaFromBase64($data['foto'])
                    ->usingFileName('guest_photo_' . time() . '.jpg')
                    ->toMediaCollection('guest_photo');
            }

            if (! empty($data['ttd'])) {
                $tamu->addMediaFromBase64($data['ttd'])
                    ->usingFileName('guest_signature_' . time() . '.png')
                    ->toMediaCollection('guest_signature');
            }

            return $tamu;
        });
    }
}
