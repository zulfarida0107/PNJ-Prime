<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MitraEksternal;
use App\Models\Order;
use App\Models\PortalUser;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MitraB2BController extends Controller
{
    public const DEMO_MITRA_EMAIL = 'budi@inovasiteknologi.co.id';

    /**
     * Dashboard utama Mitra Industri (B2B):
     * Menampilkan KPI, status pesanan/invoice, dan otomatis beralih ke Empty State jika belum ada transaksi.
     */
    public function dashboard(Request $request): View
    {
        return view('mitra.dashboard', $this->getDashboardData($request));
    }

    /**
     * Mengambil data Dashboard Mitra B2B (dapat dipanggil dari controller maupun fallback /dashboard-mitra).
     */
    public function getDashboardData(Request $request): array
    {
        $mitra = $this->resolveMitraUser();
        $searchQuery = trim((string) $request->query('q', ''));
        $statusFilter = trim((string) $request->query('status', 'all'));

        $orders = collect();
        if ($mitra && isset($mitra->id) && $mitra->id > 0) {
            $orders = Order::query()
                ->with(['items.product.unit', 'items.product.category', 'invoice'])
                ->where('user_id', $mitra->id)
                ->where('segment', 'b2b')
                ->latest('created_at')
                ->get();
        }

        // Opsi preview empty state via query ?empty=1 tanpa menghapus data seeder
        if ($request->boolean('empty')) {
            $orders = collect();
        }

        $activeStatuses = ['menunggu_verifikasi', 'menunggu_pembayaran', 'diproses', 'siap_diambil'];

        $pesananAktifCount = $orders->whereIn('status', $activeStatuses)->count();
        $labTersewaCount = $orders
            ->where('order_type', 'lab_reservation')
            ->where('status', '!=', 'dibatalkan')
            ->count();

        $nonCancelledOrders = $orders->where('status', '!=', 'dibatalkan');
        $totalTransaksiCount = $nonCancelledOrders->count();
        $totalTransaksiNilai = $nonCancelledOrders->sum(fn ($order) => (float) ($order->invoice?->grand_total ?? 0));

        $unpaidOrders = $orders->filter(
            fn ($order) => $order->status !== 'dibatalkan' && $order->invoice && $order->invoice->payment_status === 'unpaid'
        );
        $tagihanBerjalanCount = $unpaidOrders->count();
        $tagihanBerjalanNilai = $unpaidOrders->sum(fn ($order) => (float) $order->invoice->grand_total);

        return [
            'mitra' => $mitra,
            'orders' => $orders,
            'searchQuery' => $searchQuery,
            'statusFilter' => $statusFilter,
            'kpis' => [
                'pesanan_aktif' => $pesananAktifCount,
                'lab_tersewa' => $labTersewaCount,
                'total_transaksi_count' => $totalTransaksiCount,
                'total_transaksi_nilai' => $totalTransaksiNilai,
                'tagihan_berjalan_count' => $tagihanBerjalanCount,
                'tagihan_berjalan_nilai' => $tagihanBerjalanNilai,
            ],
        ];
    }

    /**
     * Katalog Produk & Layanan Riset B2B dengan filter dinamis dan tarif komersial price_b2b.
     */
    public function katalog(Request $request): View
    {
        $mitra = $this->resolveMitraUser();

        $searchQuery = trim((string) $request->query('q', ''));
        $unitId = $request->query('unit_id');
        $categoryId = $request->query('category_id');
        $subCategoryId = $request->query('sub_category_id');
        $orderType = $request->query('order_type');

        $units = Unit::query()->orderBy('name')->get();
        $categories = Category::query()->orderBy('id')->get();
        $subCategories = SubCategory::query()->with('category')->orderBy('name')->get();

        $query = Product::query()
            ->with(['unit', 'category', 'subCategory'])
            ->where('status', 'tayang');

        if ($unitId) {
            $query->where('unit_id', $unitId);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($subCategoryId) {
            $query->where('sub_category_id', $subCategoryId);
        }

        if ($orderType && in_array($orderType, ['ready_stock', 'pre_order', 'lab_reservation'], true)) {
            $query->where('order_type', $orderType);
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

        $products = $query->orderBy('id')->get();
        $totalPublishedCount = Product::query()->where('status', 'tayang')->count();

        return view('mitra.katalog', [
            'mitra' => $mitra,
            'products' => $products,
            'totalPublishedCount' => $totalPublishedCount,
            'units' => $units,
            'categories' => $categories,
            'subCategories' => $subCategories,
            'filters' => [
                'q' => $searchQuery,
                'unit_id' => $unitId,
                'category_id' => $categoryId,
                'sub_category_id' => $subCategoryId,
                'order_type' => $orderType,
            ],
            'searchQuery' => $searchQuery,
        ]);
    }

    /**
     * Halaman detail produk / layanan riset B2B beserta simulasi pajak komersial (PPN 11%, PPh 23, e-Meterai, BAST).
     */
    public function detail(Request $request, string $product): View
    {
        $mitra = $this->resolveMitraUser();

        $item = Product::query()
            ->with(['unit', 'category', 'subCategory', 'submitter'])
            ->where('status', 'tayang')
            ->where(function ($q) use ($product) {
                $q->where('id', $product)->orWhere('code', $product);
            })
            ->firstOrFail();

        $relatedProducts = Product::query()
            ->with(['unit', 'category'])
            ->where('status', 'tayang')
            ->where('id', '!=', $item->id)
            ->orderByRaw('CASE WHEN category_id = ? THEN 0 ELSE 1 END', [$item->category_id])
            ->limit(3)
            ->get();

        $isServiceOrLab = in_array($item->category?->code, ['JSA', 'LAB'], true)
            || $item->order_type === 'lab_reservation';

        $dpp = (float) $item->price_b2b;
        $ppnRate = 0.11;
        $pph23Rate = $isServiceOrLab ? 0.02 : 0.0;
        $emeteraiAmount = 10000.0;

        $ppnAmount = round($dpp * $ppnRate);
        $pph23Amount = round($dpp * $pph23Rate);
        $grandTotal = $dpp + $ppnAmount - $pph23Amount + $emeteraiAmount;

        return view('mitra.detail', [
            'mitra' => $mitra,
            'product' => $item,
            'relatedProducts' => $relatedProducts,
            'isServiceOrLab' => $isServiceOrLab,
            'taxBreakdown' => [
                'dpp' => $dpp,
                'ppn_rate' => $ppnRate,
                'ppn_amount' => $ppnAmount,
                'pph23_rate' => $pph23Rate,
                'pph23_amount' => $pph23Amount,
                'emeterai_amount' => $emeteraiAmount,
                'grand_total' => $grandTotal,
            ],
            'searchQuery' => trim((string) $request->query('q', '')),
        ]);
    }

    /**
     * Halaman profil akun perusahaan Mitra Industri B2B (NIB, NPWP, KYC status, PIC, alamat penagihan).
     */
    public function profil(Request $request): View
    {
        $mitra = $this->resolveMitraUser();
        $mitraEksternal = null;

        if (Auth::check()) {
            $mitraEksternal = MitraEksternal::query()
                ->where('id_user', Auth::user()->id_pengguna)
                ->first();
        }

        return view('mitra.profil', [
            'mitra' => $mitra,
            'mitraEksternal' => $mitraEksternal,
            'searchQuery' => trim((string) $request->query('q', '')),
        ]);
    }

    /**
     * Pembaruan data kontak PIC dan alamat penagihan perusahaan mitra.
     */
    public function updateProfil(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama PIC wajib diisi.',
            'phone.required' => 'Nomor telepon PIC wajib diisi.',
            'address.required' => 'Alamat penagihan perusahaan wajib diisi.',
        ]);

        $mitra = $this->resolveMitraUser();

        if ($mitra instanceof PortalUser && $mitra->exists) {
            $mitra->update([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
            ]);
        }

        if (Auth::check()) {
            $authUser = Auth::user();
            $authUser->update([
                'no_telepon' => substr(preg_replace('/[^0-9+]/', '', $validated['phone']), 0, 20),
            ]);

            MitraEksternal::query()
                ->where('id_user', $authUser->id_pengguna)
                ->update([
                    'nama_pic' => $validated['name'],
                    'no_telepon' => substr(preg_replace('/[^0-9+]/', '', $validated['phone']), 0, 20),
                    'alamat_perusahaan' => $validated['address'],
                ]);
        }

        return redirect()
            ->route('b2b.profil')
            ->with('success', 'Profil kontak PIC dan alamat penagihan perusahaan berhasil diperbarui.');
    }

    /**
     * Memetakan user yang sedang login (tabel `user`) ke profil modular Mitra (`users` / `PortalUser`),
     * dengan fallback aman ke akun demo Budi Santoso / PT Inovasi Teknologi Nusantara.
     */
    public function resolveMitraUser(): PortalUser
    {
        $authUser = Auth::user();

        if ($authUser) {
            $portalUser = PortalUser::query()
                ->where('email', $authUser->email)
                ->first();

            if ($portalUser) {
                return $portalUser;
            }

            $mitraEksternal = MitraEksternal::query()
                ->where('id_user', $authUser->id_pengguna)
                ->first();

            if ($mitraEksternal) {
                $kycMapped = match ($mitraEksternal->status_verifikasi_dokumen) {
                    'terverifikasi', 'disetujui', 'approved' => 'approved',
                    'ditolak', 'rejected' => 'rejected',
                    default => 'pending',
                };

                $virtual = new PortalUser([
                    'name' => $mitraEksternal->nama_pic ?: $authUser->nama_lengkap,
                    'email' => $authUser->email,
                    'role' => 'mitra',
                    'phone' => $mitraEksternal->no_telepon ?: $authUser->no_telepon,
                    'address' => $mitraEksternal->alamat_perusahaan,
                    'company_name' => $mitraEksternal->nama_perusahaan,
                    'nib' => $mitraEksternal->nomor_identitas_lembaga,
                    'npwp' => $mitraEksternal->npwp_perusahaan,
                    'kyc_status' => $kycMapped,
                ]);
                $virtual->id = 0;

                return $virtual;
            }
        }

        $demoMitra = PortalUser::query()
            ->where('email', self::DEMO_MITRA_EMAIL)
            ->first();

        if ($demoMitra) {
            return $demoMitra;
        }

        $fallback = new PortalUser([
            'name' => 'Budi Santoso',
            'email' => self::DEMO_MITRA_EMAIL,
            'role' => 'mitra',
            'phone' => '081298765432',
            'address' => 'Jl. Raya Industri No. 88, Kawasan Jababeka, Cikarang',
            'company_name' => 'PT Inovasi Teknologi Nusantara',
            'nib' => '1234567890123',
            'npwp' => '0123456789012345',
            'kyc_status' => 'approved',
        ]);
        $fallback->id = 0;

        return $fallback;
    }
}
