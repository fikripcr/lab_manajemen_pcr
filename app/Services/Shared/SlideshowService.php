<?php
namespace App\Services\Shared;

use App\Models\Shared\Slideshow;
use Illuminate\Support\Facades\DB;

class SlideshowService
{
    public function getFilteredQuery(array $filters = [])
    {
        return Slideshow::query();
    }

    public function createSlideshow(array $data): Slideshow
    {
        return DB::transaction(function () use ($data) {
            $slideshow = Slideshow::create($data);

            if (isset($data['slideshow_image'])) {
                $slideshow->addMedia($data['slideshow_image'])
                    ->toMediaCollection('slideshow_image');
            }

            logActivity('slideshow_management', "Menambah slideshow baru: {$slideshow->title}", $slideshow);

            return $slideshow;
        });
    }

    public function updateSlideshow($id, array $data): bool
    {
        return DB::transaction(function () use ($id, $data) {
            $slideshow = Slideshow::findOrFail($id);

            $slideshow->update($data);

            if (isset($data['slideshow_image'])) {
                $slideshow->addMedia($data['slideshow_image'])
                    ->toMediaCollection('slideshow_image');
            }

            logActivity('slideshow_management', "Memperbarui slideshow: {$slideshow->title}", $slideshow);

            return true;
        });
    }

    public function deleteSlideshow($id): bool
    {
        return DB::transaction(function () use ($id) {
            $slideshow = Slideshow::findOrFail($id);
            $title     = $slideshow->title;

            $slideshow->delete();

            logActivity('slideshow_management', "Menghapus slideshow: {$title}");

            return true;
        });
    }
}
