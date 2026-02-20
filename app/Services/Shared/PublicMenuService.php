<?php
namespace App\Services\Shared;

use App\Models\Shared\PublicMenu;
use Illuminate\Support\Facades\DB;

class PublicMenuService
{
    public function createMenu(array $data): PublicMenu
    {
        return DB::transaction(function () use ($data) {
            // Handle page_id encrypted ID if coming from request
            if (isset($data['page_id']) && ! is_numeric($data['page_id'])) {
                $data['page_id'] = decryptIdIfEncrypted($data['page_id'], false);
            }
            if (isset($data['parent_id']) && ! is_numeric($data['parent_id'])) {
                $data['parent_id'] = decryptIdIfEncrypted($data['parent_id'], false);
            }

            // Set default sequence if not provided
            if (! isset($data['sequence'])) {
                $maxSeq           = PublicMenu::where('parent_id', $data['parent_id'] ?? null)->max('sequence');
                $data['sequence'] = $maxSeq ? $maxSeq + 1 : 1;
            }

            $menu = PublicMenu::create($data);
            logActivity('public_menu', "Membuat menu: {$menu->title}", $menu);
            return $menu;
        });
    }

    public function updateMenu(PublicMenu $menu, array $data): bool
    {
        return DB::transaction(function () use ($menu, $data) {
            if (isset($data['page_id']) && ! is_numeric($data['page_id'])) {
                $data['page_id'] = decryptIdIfEncrypted($data['page_id'], false);
            }
            if (isset($data['parent_id']) && ! is_numeric($data['parent_id'])) {
                $data['parent_id'] = decryptIdIfEncrypted($data['parent_id'], false);
            }

            $menu->update($data);
            logActivity('public_menu', "Update menu: {$menu->title}", $menu);
            return true;
        });
    }

    public function deleteMenu(PublicMenu $menu): bool
    {
        return DB::transaction(function () use ($menu) {
            $title = $menu->title;
            $menu->delete();
            logActivity('public_menu', "Hapus menu: {$title}");
            return true;
        });
    }

    /**
     * Reorder menus recursively
     * Structure: [ {id: encrypted_menu_id, children: [...]}, ... ]
     */
    public function reorderMenus(array $hierarchy, $parentId = null)
    {
        return DB::transaction(function () use ($hierarchy, $parentId) {
            foreach ($hierarchy as $index => $item) {
                $id = isset($item['id']) ? decryptIdIfEncrypted($item['id'], false) : null;

                if ($id) {
                    $menu = PublicMenu::find($id);
                    if ($menu) {
                        $menu->update([
                            'parent_id' => $parentId,
                            'sequence'  => $index + 1,
                        ]);

                        if (isset($item['children']) && is_array($item['children'])) {
                            $this->reorderMenus($item['children'], $id);
                        }
                    }
                }
            }
            return true;
        });
    }
}
