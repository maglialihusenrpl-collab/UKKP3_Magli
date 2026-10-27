<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;

class DashboardController extends Controller
{
    /** Mengarahkan pengguna yang sudah masuk ke dashboard sesuai peran. */
    public function redirectByRole()
    {
        return redirect()->route(
            auth()->user()->role === 'admin' ? 'dashboard.admin' : 'dashboard.customer'
        );
    }

    /** Menyediakan ringkasan seluruh pengaduan untuk dashboard admin. */
    public function index()
    {
        $totalPengaduan = Pengaduan::count();
        $menunggu = Pengaduan::where('status', 'Menunggu')->count();
        $diproses = Pengaduan::where('status', 'Diproses')->count();
        $selesai = Pengaduan::where('status', 'Selesai')->count();
        $pengaduans = Pengaduan::latest()
            ->limit(8)
            ->get();

        return view('dashboard', compact(
            'totalPengaduan',
            'menunggu',
            'diproses',
            'selesai',
            'pengaduans'
        ));
    }

    /** Menyediakan ringkasan dan daftar pengaduan milik customer yang masuk. */
    public function customer()
    {
        abort_unless(auth()->user()->role === 'customer', 403);

        $pengaduans = Pengaduan::where('email', auth()->user()->email)
            ->latest()
            ->get();

        return view('dashboard-customer', [
            'totalPengaduan' => $pengaduans->count(),
            'menunggu' => $pengaduans->where('status', 'Menunggu')->count(),
            'diproses' => $pengaduans->where('status', 'Diproses')->count(),
            'selesai' => $pengaduans->where('status', 'Selesai')->count(),
            'pengaduans' => $pengaduans,
        ]);
    }
}
