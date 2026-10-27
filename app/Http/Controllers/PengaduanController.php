<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use RuntimeException;

class PengaduanController extends Controller
{
    /** Menampilkan daftar pengaduan terbaru untuk dikelola admin. */
    public function index()
    {
        $pengaduans = Pengaduan::latest()->get();

        return view('pengaduan.index', compact('pengaduans'));
    }

    /** Menampilkan formulir pembuatan pengaduan customer. */
    public function create()
    {
        return view('pengaduan.create', [
            'kategoris' => Kategori::orderBy('name')->get(),
        ]);
    }

    /** Memvalidasi dan menyimpan pengaduan baru beserta lampiran opsional. */
    public function store(Request $request)
    {
        $validated = $this->validatePengaduan($request);
        $validated['nama_pelapor'] = $request->user()?->name ?? $validated['nama_pelapor'];
        $validated['email'] = $request->user()?->email ?? $validated['email'];
        $validated['lampiran'] = $request->file('lampiran')?->store('pengaduan', 'public');

        if ($request->hasFile('lampiran') && ! $validated['lampiran']) {
            throw new RuntimeException('File lampiran gagal disimpan.');
        }

        $validated['status'] = 'Menunggu';

        Pengaduan::create($validated);

        return redirect()
            ->route('pengaduan.create')
            ->with('success', 'Pengaduan berhasil ditambahkan.');
    }

    /** Menampilkan detail satu pengaduan. */
    public function show(Pengaduan $pengaduan)
    {
        return view('pengaduan.show', compact('pengaduan'));
    }

    /** Mengirim lampiran hanya kepada admin atau pemilik pengaduan. */
    public function attachment(Pengaduan $pengaduan)
    {
        abort_unless(
            auth()->user()->role === 'admin' || $pengaduan->email === auth()->user()->email,
            403
        );

        abort_unless(
            $pengaduan->lampiran && Storage::disk('public')->exists($pengaduan->lampiran),
            404,
            'File lampiran tidak ditemukan.'
        );

        $extension = strtolower(pathinfo($pengaduan->lampiran, PATHINFO_EXTENSION));

        if (in_array($extension, ['jpg', 'jpeg', 'png'], true)) {
            $image = @imagecreatefromstring(Storage::disk('public')->get($pengaduan->lampiran));
            abort_unless($image !== false, 404, 'Foto tidak dapat dibaca.');

            ob_start();
            if ($extension === 'png') {
                imagepng($image);
                $contentType = 'image/png';
            } else {
                imagejpeg($image, null, 90);
                $contentType = 'image/jpeg';
            }
            $imageContents = ob_get_clean();
            imagedestroy($image);

            return response($imageContents, 200, [
                'Content-Type' => $contentType,
                'Cache-Control' => 'private, max-age=3600',
            ]);
        }

        return Storage::disk('public')->response($pengaduan->lampiran);
    }

    /** Menampilkan formulir untuk mengubah data pengaduan. */
    public function edit(Pengaduan $pengaduan)
    {
        return view('pengaduan.edit', [
            'pengaduan' => $pengaduan,
            'kategoris' => Kategori::orderBy('name')->get(),
        ]);
    }

    /** Memvalidasi perubahan dan mengganti lampiran lama jika ada lampiran baru. */
    public function update(Request $request, Pengaduan $pengaduan)
    {
        $validated = $this->validatePengaduan($request, false, $pengaduan);
        $newAttachment = $request->file('lampiran')?->store('pengaduan', 'public');
        $oldAttachment = $pengaduan->lampiran;

        if ($request->hasFile('lampiran') && ! $newAttachment) {
            throw new RuntimeException('File lampiran gagal disimpan.');
        }

        if ($request->hasFile('lampiran')) {
            $validated['lampiran'] = $newAttachment;
        }

        $pengaduan->update($validated);

        if ($newAttachment && $oldAttachment) {
            Storage::disk('public')->delete($oldAttachment);
        }

        return redirect()
            ->route('pengaduan.index')
            ->with('success', 'Pengaduan berhasil diperbarui.');
    }

    /** Memperbarui status pengaduan dengan salah satu status yang diizinkan. */
    public function updateStatus(Request $request, Pengaduan $pengaduan)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['Menunggu', 'Diproses', 'Selesai'])],
        ]);

        $pengaduan->update($validated);

        return redirect()
            ->route('pengaduan.index')
            ->with('success', 'Status pengaduan berhasil diperbarui.');
    }

    /** Menghapus pengaduan dan lampiran yang terkait dengannya. */
    public function destroy(Pengaduan $pengaduan)
    {
        if ($pengaduan->lampiran) {
            Storage::disk('public')->delete($pengaduan->lampiran);
        }

        $pengaduan->delete();

        return redirect()
            ->route('pengaduan.index')
            ->with('success', 'Pengaduan berhasil dihapus.');
    }

    /**
     * Memvalidasi data pengaduan; status hanya divalidasi saat diminta.
     * Meja pengaduan yang sedang diedit tetap diizinkan meski tak lagi menjadi pilihan baru.
     */
    private function validatePengaduan(
        Request $request,
        bool $includeStatus = false,
        ?Pengaduan $pengaduan = null
    ): array
    {
        $rules = [
            'nama_pelapor' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_hp' => 'required|string|max:30',
            'nomor_meja' => [
                'required',
                'string',
                Rule::in(array_unique([
                    ...Pengaduan::TABLE_NUMBERS,
                    ...($pengaduan?->nomor_meja ? [$pengaduan->nomor_meja] : []),
                ])),
            ],
            'kategori' => ['required', 'string', 'max:100', Rule::in(Kategori::pluck('name')->all())],
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:10000',
            'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ];

        if ($includeStatus) {
            $rules['status'] = 'required|in:Menunggu,Diproses,Selesai';
        }

        return $request->validate($rules);
    }
}
