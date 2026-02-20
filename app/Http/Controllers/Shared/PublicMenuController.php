<?php
namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shared\PublicMenuRequest;
use App\Models\Shared\PublicMenu;
use App\Models\Shared\PublicPage;
use App\Services\Shared\PublicMenuService;
use Exception;
use Illuminate\Http\Request;

class PublicMenuController extends Controller
{
    protected $MenuService;

    public function __construct(PublicMenuService $MenuService)
    {
        $this->MenuService = $MenuService;
    }

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
        try {
            $this->MenuService->createMenu($request->validated());
            return jsonSuccess('Menu berhasil ditambahkan.', route('shared.public-menu.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function edit(PublicMenu $public_menu)
    {
        $pages   = PublicPage::where('is_published', true)->orderBy('title')->get();
        $parents = PublicMenu::where('menu_id', '!=', $public_menu->menu_id)->orderBy('title')->get();

        return view('pages.shared.public-menu.create-edit-ajax', [
            'menu'    => $public_menu,
            'pages'   => $pages,
            'parents' => $parents,
        ]);
    }

    public function update(PublicMenuRequest $request, PublicMenu $public_menu)
    {
        try {
            $this->MenuService->updateMenu($public_menu, $request->validated());
            return jsonSuccess('Menu berhasil diperbarui.', route('shared.public-menu.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy(PublicMenu $public_menu)
    {
        try {
            $this->MenuService->deleteMenu($public_menu);
            return jsonSuccess('Menu berhasil dihapus.', route('shared.public-menu.index'));
        } catch (Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function reorder(Request $request)
    {
        $hierarchy = $request->input('hierarchy');
        if (is_array($hierarchy)) {
            $this->MenuService->reorderMenus($hierarchy);
            return jsonSuccess('Struktur menu berhasil diperbarui.');
        }
        return jsonError('Data struktur tidak valid.');
    }
}
