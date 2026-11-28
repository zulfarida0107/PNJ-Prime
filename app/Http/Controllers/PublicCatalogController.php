<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class PublicCatalogController extends Controller
{
    /**
     * Katalog Riset & Inovasi Publik (tanpa login).
     */
    public function index(Request $request): View
    {
        $searchQuery = trim((string) $request->query('q', ''));
        $categoryId = $request->query('category_id');
        $orderType = $request->query('order_type');
        $trlFilter = $request->query('trl');

        $isLabMode = $orderType === 'lab_reservation';

        $categories = Category::query()->orderBy('id')->get();
        $units = Unit::query()->orderBy('name')->get();

        $baseQuery = Product::query()
            ->where('status', 'tayang');

        if ($isLabMode) {
            $baseQuery->where('order_type', 'lab_reservation');
        } else {
            $baseQuery->whereIn('order_type', ['ready_stock', 'pre_order']);
        }

        $totalPublishedCount = (clone $baseQuery)->count();

        $query = (clone $baseQuery)
            ->with(['category', 'subCategory', 'unit']);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if (! $isLabMode && $orderType && in_array($orderType, ['ready_stock', 'pre_order'], true)) {
            $query->where('order_type', $orderType);
        }

        if ($trlFilter && in_array((string) $trlFilter, ['5', '6', '7'], true)) {
            $matchingOrderTypes = match ((string) $trlFilter) {
                '6' => $isLabMode ? [] : ['pre_order'],
                '7' => $isLabMode ? ['lab_reservation'] : ['ready_stock'],
                default => [],
            };

            if (! empty($matchingOrderTypes)) {
                $query->whereIn('order_type', $matchingOrderTypes);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if ($searchQuery !== '') {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('name', 'like', '%' . $searchQuery . '%')
                    ->orWhere('code', 'like', '%' . $searchQuery . '%')
                    ->orWhere('description', 'like', '%' . $searchQuery . '%')
                    ->orWhereHas('unit', fn ($u) => $u->where('name', 'like', '%' . $searchQuery . '%'))
                    ->orWhereHas('category', fn ($c) => $c->where('name', 'like', '%' . $searchQuery . '%'))
                    ->orWhereHas('subCategory', fn ($sc) => $sc->where('name', 'like', '%' . $searchQuery . '%'));
            });
        }

        $products = $query
            ->orderBy('id')
            ->paginate(12)
            ->withQueryString();

        return view('public.katalog', [
            'products' => $products,
            'totalPublishedCount' => $totalPublishedCount,
            'isLabMode' => $isLabMode,
            'categories' => $categories,
            'units' => $units,
            'filters' => [
                'q' => $searchQuery,
                'category_id' => $categoryId,
                'order_type' => $isLabMode ? 'lab_reservation' : (in_array($orderType, ['ready_stock', 'pre_order'], true) ? $orderType : null),
                'trl' => $trlFilter,
            ],
            'dashboardUrl' => $this->resolveDashboardUrl(Auth::user()),
            'productMetaMap' => $this->buildProductMetaMap($products->items()),
        ]);
    }

    /**
     * Halaman Detail Produk Riset Publik (tanpa login).
     */
    public function show(Request $request, string $product): View
    {
        $item = Product::query()
            ->with(['category', 'subCategory', 'unit'])
            ->where('status', 'tayang')
            ->where(function ($q) use ($product) {
                if (ctype_digit($product)) {
                    $q->where('id', (int) $product)->orWhere('code', $product);
                } else {
                    $q->where('code', $product);
                }
            })
            ->firstOrFail();

        $relatedProducts = Product::query()
            ->with(['category', 'subCategory', 'unit'])
            ->where('status', 'tayang')
            ->where('id', '!=', $item->id)
            ->orderByRaw('CASE WHEN category_id = ? THEN 0 ELSE 1 END', [$item->category_id])
            ->limit(3)
            ->get();

        return view('public.detail', [
            'product' => $item,
            'meta' => $this->resolveProductMeta($item),
            'relatedProducts' => $relatedProducts,
            'relatedMetaMap' => $this->buildProductMetaMap($relatedProducts->all()),
            'dashboardUrl' => $this->resolveDashboardUrl(Auth::user()),
            'searchQuery' => trim((string) $request->query('q', '')),
        ]);
    }

    /**
     * Tentukan URL dashboard sesuai role user yang sedang login.
     */
    protected function resolveDashboardUrl(?User $user): string
    {
        if (! $user) {
            return route('login');
        }

        return match ($user->role) {
            'mhs', 'dosen' => url('/dashboard-mahasiswa'),
            'mitra' => Route::has('b2b.dashboard') ? route('b2b.dashboard') : url('/dashboard-mitra'),
            'pimpinan' => url('/dashboard-pimpinan'),
            'p3m' => Route::has('admin.p3m.dashboard') ? route('admin.p3m.dashboard') : url('/'),
            'rtpu', 'admin' => Route::has('admin.rtpu.dashboard') ? route('admin.rtpu.dashboard') : url('/dashboard-pimpinan'),
            default => url('/'),
        };
    }

    /**
     * Bangun peta metadata tampilan (TRL, persentase kesiapan, estimasi, badge, CTA) per produk.
     *
     * @param  array<int, Product>  $products
     * @return array<int, array<string, mixed>>
     */
    protected function buildProductMetaMap(array $products): array
    {
        $map = [];
        foreach ($products as $product) {
            $map[$product->id] = $this->resolveProductMeta($product);
        }

        return $map;
    }

    /**
     * Metadata tampilan deterministik untuk kartu & halaman detail katalog publik.
     *
     * @return array<string, mixed>
     */
    protected function resolveProductMeta(Product $product): array
    {
        return match ($product->order_type) {
            'pre_order' => [
                'trl_level' => 6,
                'trl_label' => 'TRL 6',
                'readiness_pct' => 85,
                'order_label' => 'Pre-Order',
                'order_pill' => 'bg-white/95 text-sky-700 border-sky-200',
                'order_dot' => 'bg-sky-500',
                'estimasi' => '7 Hari Kerja',
                'satuan' => 'Unit',
                'stok_label' => 'Kuota Produksi',
                'cta_label' => 'Pre-Order Sekarang',
            ],
            'lab_reservation' => [
                'trl_level' => 7,
                'trl_label' => 'TRL 7',
                'readiness_pct' => 90,
                'order_label' => 'Reservasi Lab',
                'order_pill' => 'bg-white/95 text-[#00877A] border-teal-200',
                'order_dot' => 'bg-[#00877A]',
                'estimasi' => 'Tarif Per Jam',
                'satuan' => 'Jam',
                'stok_label' => 'Slot Tersedia',
                'cta_label' => 'Reservasi Lab',
            ],
            default => [
                'trl_level' => 7,
                'trl_label' => 'TRL 7',
                'readiness_pct' => 95,
                'order_label' => 'Ready Stock',
                'order_pill' => 'bg-white/95 text-emerald-700 border-emerald-200',
                'order_dot' => 'bg-emerald-500',
                'estimasi' => '1–2 Hari Kerja',
                'satuan' => 'Unit',
                'stok_label' => 'Stok Tersedia',
                'cta_label' => 'Pesan Sekarang',
            ],
        };
    }
}
