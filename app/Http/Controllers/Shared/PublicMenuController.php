<?php
namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shared\PublicMenuRequest;
use App\Http\Requests\Shared\ReorderRequest;
use App\Models\Shared\PublicMenu;
use App\Models\Shared\PublicPage;
use App\Services\Shared\PublicMenuService;

class PublicMenuController extends Controller
{
    public function __construct(protected PublicMenuService $menuService)
    {}

    public function index()
    {
        $orgUnits = PublicMenu::whereNull('parent_id')
            ->orderBy('sequence')
            ->with(['children', 'page'])
            ->get();

        $pages = PublicPage::where('is_published', true)->orderBy('title')->get();

        return view('pages.shared.public-menu.index', compact('orgUnits', 'pages'));
    }

    public function create()
    {
        $pages = PublicPage::where('is_published', true)->orderBy('title')->get();
        // Get parent for select options if needed, but tree view usually handles adding child
        $parents = PublicMenu::orderBy('title')->get();

        return view('pages.shared.public-menu.create-edit-ajax', [
            'menu'    => new PublicMenu(),
            'pages'   => $pages,
            'parents' => $parents,
        ]);
    }

    public function store(PublicMenuRequest $request)
    {
        $this->menuService->createMenu($request->validated());
        return jsonSuccess('Menu berhasil ditambahkan.', route('shared.public-menu.index'));
    }

    public function edit(PublicMenu $publicMenu)
    {
        $pages   = PublicPage::where('is_published', true)->orderBy('title')->get();
        $parents = PublicMenu::where('menu_id', '!=', $publicMenu->menu_id)->orderBy('title')->get();

        return view('pages.shared.public-menu.create-edit-ajax', [
            'menu'    => $publicMenu,
            'pages'   => $pages,
            'parents' => $parents,
        ]);
    }

    public function update(PublicMenuRequest $request, PublicMenu $publicMenu)
    {
        $this->menuService->updateMenu($publicMenu, $request->validated());
        return jsonSuccess('Menu berhasil diperbarui.', route('shared.public-menu.index'));
    }

    public function destroy(PublicMenu $publicMenu)
    {
        $this->menuService->deleteMenu($publicMenu);
        return jsonSuccess('Menu berhasil dihapus.', route('shared.public-menu.index'));
    }

    public function reorder(ReorderRequest $request)
    {
        $hierarchy = $request->validated()['hierarchy'] ?? [];
        if ($hierarchy) {
            $this->menuService->reorderMenus($hierarchy);
            return jsonSuccess('Struktur menu berhasil diperbarui.');
        }
        return jsonError('Data struktur tidak valid.');
    }
}
