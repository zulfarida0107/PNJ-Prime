<?php

namespace App\Http\Controllers;

use App\Models\MitraEksternal;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class AuthController extends Controller
{
    /**
     * Halaman Login Utama (00a).
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Halaman Gateway SSO PNJ (00c).
     */
    public function showSso(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.sso');
    }

    /**
     * Halaman Login Mitra Non-SSO (00d).
     */
    public function showLoginMitra(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.login-mitra');
    }

    /**
     * Halaman Register & KYC Mitra Industri (00b).
     */
    public function showRegisterMitra(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.register-mitra');
    }

    /**
     * Proses Login Kredensial Langsung (Login Utama & Login Mitra B2B).
     */
    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! $user->password || ! Hash::check($validated['password'], $user->password)) {
            return back()
                ->withErrors(['email' => 'Email atau kata sandi yang Anda masukkan salah.'])
                ->onlyInput('email', 'remember');
        }

        if ($user->status_akun === 'pending') {
            return back()
                ->with('error', 'Akun Anda sedang menunggu verifikasi administrator.')
                ->onlyInput('email');
        }

        if ($user->status_akun !== 'aktif') {
            return back()
                ->with('error', 'Akun Anda saat ini tidak aktif. Silakan hubungi helpdesk.')
                ->onlyInput('email');
        }

        $remember = $request->boolean('remember') && Schema::hasColumn($user->getTable(), 'remember_token');
        Auth::login($user, $remember);
        $request->session()->regenerate();

        return $this->redirectByRole($user);
    }

    /**
     * Proses Login Gateway SSO PNJ (mendukung Email Institusi, NIM/NIP `saic_id`, atau `sso_id`).
     */
    public function loginSso(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'identifier' => ['required', 'string', 'max:150'],
            'password' => ['required', 'string'],
        ], [
            'identifier.required' => 'Email institusi, NIM, atau NIP wajib diisi.',
            'password.required' => 'Kata sandi akun SSO wajib diisi.',
        ]);

        $identifier = trim($validated['identifier']);

        $user = User::query()
            ->where('email', $identifier)
            ->orWhere('saic_id', $identifier)
            ->orWhere('sso_id', $identifier)
            ->first();

        if (! $user || ! $user->password || ! Hash::check($validated['password'], $user->password)) {
            return back()
                ->withErrors(['identifier' => 'Kredensial SSO PNJ tidak ditemukan atau kata sandi salah.'])
                ->onlyInput('identifier', 'remember');
        }

        if ($user->status_akun !== 'aktif') {
            return back()
                ->with('error', 'Akun SSO Anda belum aktif atau sedang dinonaktifkan.')
                ->onlyInput('identifier');
        }

        $remember = $request->boolean('remember') && Schema::hasColumn($user->getTable(), 'remember_token');
        Auth::login($user, $remember);
        $request->session()->regenerate();

        return $this->redirectByRole($user);
    }

    /**
     * Proses Pendaftaran & KYC Mitra Industri.
     */
    public function registerMitra(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_perusahaan' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:user,email'],
            'password' => ['required', 'string', 'min:8'],
            'no_telepon' => ['required', 'string', 'max:20'],
            'nik' => ['required', 'digits:16'],
            'npwp_perusahaan' => ['required', 'digits:16', 'unique:mitra_eksternal,npwp_perusahaan'],
            'dokumen_ktp' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'dokumen_npwp' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'dokumen_nib' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg', 'max:5120'],
            'dokumen_profil' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'agree_privacy' => ['accepted'],
        ], [
            'nama_perusahaan.required' => 'Nama perusahaan / lengkap wajib diisi.',
            'email.required' => 'Email kemitraan wajib diisi.',
            'email.email' => 'Format email kemitraan tidak valid.',
            'email.unique' => 'Email kemitraan sudah terdaftar.',
            'password.required' => 'Password akses wajib diisi.',
            'password.min' => 'Password akses minimal 8 karakter.',
            'no_telepon.required' => 'Nomor telepon / WhatsApp wajib diisi.',
            'nik.required' => 'NIK penanggung jawab wajib diisi.',
            'nik.digits' => 'NIK harus terdiri dari tepat 16 digit angka.',
            'npwp_perusahaan.required' => 'NPWP badan / usaha wajib diisi.',
            'npwp_perusahaan.digits' => 'NPWP harus terdiri dari tepat 16 digit angka.',
            'npwp_perusahaan.unique' => 'NPWP badan / usaha sudah terdaftar.',
            'dokumen_ktp.required' => 'Foto / dokumen KTP wajib diunggah.',
            'dokumen_ktp.mimes' => 'Format KTP harus PDF, JPG, atau PNG.',
            'dokumen_ktp.max' => 'Ukuran file KTP maksimal 2MB.',
            'dokumen_npwp.required' => 'Foto / dokumen NPWP wajib diunggah.',
            'dokumen_npwp.mimes' => 'Format NPWP harus PDF, JPG, atau PNG.',
            'dokumen_npwp.max' => 'Ukuran file NPWP maksimal 2MB.',
            'dokumen_nib.mimes' => 'Format NIB harus PDF atau JPG.',
            'dokumen_nib.max' => 'Ukuran file NIB maksimal 5MB.',
            'dokumen_profil.mimes' => 'Format Profil Perusahaan harus PDF.',
            'dokumen_profil.max' => 'Ukuran file Profil Perusahaan maksimal 10MB.',
            'agree_privacy.accepted' => 'Anda harus menyetujui Kebijakan Privasi pengelolaan data.',
        ]);

        $uploadedPaths = [];

        try {
            $ktpPath = $request->file('dokumen_ktp')->store('kyc', 'public');
            $uploadedPaths[] = $ktpPath;

            $npwpPath = $request->file('dokumen_npwp')->store('kyc', 'public');
            $uploadedPaths[] = $npwpPath;

            $nibPath = null;
            if ($request->hasFile('dokumen_nib')) {
                $nibPath = $request->file('dokumen_nib')->store('kyc', 'public');
                $uploadedPaths[] = $nibPath;
            }

            $profilPath = null;
            if ($request->hasFile('dokumen_profil')) {
                $profilPath = $request->file('dokumen_profil')->store('kyc', 'public');
                $uploadedPaths[] = $profilPath;
            }

            $cleanPhone = Str::limit(preg_replace('/[^0-9+]/', '', $validated['no_telepon']), 20, '');

            $catatanParts = [
                '2FA:' . ($request->boolean('enable_2fa') ? 'aktif' : 'nonaktif'),
            ];
            if ($nibPath) {
                $catatanParts[] = 'NIB:' . $nibPath;
            }
            if ($profilPath) {
                $catatanParts[] = 'Profil:' . $profilPath;
            }

            DB::transaction(function () use ($validated, $cleanPhone, $ktpPath, $npwpPath, $catatanParts) {
                $user = User::create([
                    'sso_id' => null,
                    'auth_provider' => 'manual',
                    'saic_id' => null,
                    'email' => $validated['email'],
                    'password' => $validated['password'],
                    'nama_lengkap' => $validated['nama_perusahaan'],
                    'no_telepon' => $cleanPhone,
                    'role' => 'mitra',
                    'status_akun' => 'aktif',
                ]);

                MitraEksternal::create([
                    'id_user' => $user->id_pengguna,
                    'nama_perusahaan' => $validated['nama_perusahaan'],
                    'nama_pic' => Str::limit($validated['nama_perusahaan'], 100, ''),
                    'tipe_mitra' => 'perusahaan',
                    'npwp_perusahaan' => $validated['npwp_perusahaan'],
                    'nomor_identitas_lembaga' => $validated['nik'],
                    'no_telepon' => $cleanPhone,
                    'alamat_perusahaan' => 'Belum dilengkapi',
                    'dokumen_ktp' => $ktpPath,
                    'dokumen_npwp' => $npwpPath,
                    'status_verifikasi_dokumen' => 'belum',
                    'catatan_verifikasi' => Str::limit(implode(' | ', $catatanParts), 255, ''),
                ]);
            });

            return redirect()
                ->route('login.mitra')
                ->with('success', 'Pendaftaran Mitra Industri berhasil! Dokumen KYC Anda telah tersimpan dan menunggu verifikasi. Silakan login.');
        } catch (Throwable $e) {
            foreach ($uploadedPaths as $path) {
                Storage::disk('public')->delete($path);
            }

            return back()
                ->withInput($request->except('password'))
                ->with('error', 'Terjadi kesalahan saat menyimpan pendaftaran. Silakan coba lagi.');
        }
    }

    /**
     * Logout user & invalidate session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Anda telah berhasil keluar dari PNJ Prime.');
    }

    /**
     * Redirect otomatis sesuai role user di tabel `user`.
     */
    protected function redirectByRole(User $user): RedirectResponse
    {
        return match ($user->role) {
            'mhs', 'dosen' => redirect('/dashboard-mahasiswa'),
            'mitra' => redirect('/dashboard-mitra'),
            'pimpinan' => redirect('/dashboard-pimpinan'),
            'p3m' => Route::has('admin.p3m.dashboard')
                ? redirect()->route('admin.p3m.dashboard')
                : redirect('/')->with('info', 'Berhasil masuk sebagai Admin P3M (' . $user->nama_lengkap . ').'),
            'rtpu', 'admin' => Route::has('admin.rtpu.dashboard')
                ? redirect()->route('admin.rtpu.dashboard')
                : redirect('/dashboard-pimpinan')->with('info', 'Berhasil masuk sebagai ' . strtoupper($user->role) . ' (' . $user->nama_lengkap . ').'),
            default => redirect('/'),
        };
    }
}
