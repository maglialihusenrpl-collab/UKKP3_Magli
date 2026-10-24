@extends('layouts.app')

@section('title', 'Edit Pengaduan')

@push('styles')
    <style>
        .form-card { max-width: 900px; margin: 0 auto; }
        .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
        .form-field { display: flex; flex-direction: column; gap: 7px; }
        .form-field.full-width { grid-column: 1 / -1; }
        .form-field label { font-size: 14px; font-weight: 600; }
        .form-field input,
        .form-field select,
        .form-field textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            background: #fff;
            font: inherit;
        }
        .form-field textarea { min-height: 140px; resize: vertical; }
        .form-error { color: #b91c1c; font-size: 13px; }
        .form-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px; }
        .submit-button {
            border: 0;
            border-radius: 5px;
            padding: 11px 18px;
            background: #1e40af;
            color: #fff;
            font: inherit;
            font-weight: 600;
            cursor: pointer;
        }
        @media (max-width: 640px) {
            .form-grid { grid-template-columns: 1fr; }
            .form-field.full-width { grid-column: auto; }
        }
    </style>
@endpush

@section('content')
    <div class="form-card">
        <h1 class="page-title">Edit Pengaduan</h1>
        <p class="page-description">Perbarui informasi pengaduan. Ubah status dari tabel pengaduan.</p>

        <section class="card">
            <form method="POST" action="{{ route('pengaduan.update', $pengaduan) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <div class="form-field">
                        <label for="nama_pelapor">Nama pelapor</label>
                        <input id="nama_pelapor" name="nama_pelapor" value="{{ old('nama_pelapor', $pengaduan->nama_pelapor) }}" required maxlength="255">
                        @error('nama_pelapor') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-field">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $pengaduan->email) }}" required maxlength="255">
                        @error('email') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-field">
                        <label for="no_hp">Nomor telepon</label>
                        <input id="no_hp" type="tel" name="no_hp" value="{{ old('no_hp', $pengaduan->no_hp) }}" required maxlength="30">
                        @error('no_hp') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-field">
                        <label for="nomor_meja">Nomor meja</label>
                        <select id="nomor_meja" name="nomor_meja" required>
                            @foreach (\App\Models\Pengaduan::TABLE_NUMBERS as $nomorMeja)
                                <option value="{{ $nomorMeja }}" @selected(old('nomor_meja', $pengaduan->nomor_meja) === $nomorMeja)>{{ $nomorMeja }}</option>
                            @endforeach
                        </select>
                        @error('nomor_meja') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-field">
                        <label for="kategori">Kategori</label>
                        <select id="kategori" name="kategori" required>
                            @foreach (\App\Models\Pengaduan::CATEGORIES as $kategori)
                                <option value="{{ $kategori }}" @selected(old('kategori', $pengaduan->kategori) === $kategori)>{{ $kategori }}</option>
                            @endforeach
                        </select>
                        @error('kategori') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-field">
                        <label for="judul">Judul pengaduan</label>
                        <input id="judul" name="judul" value="{{ old('judul', $pengaduan->judul) }}" required maxlength="255">
                        @error('judul') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-field full-width">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" required maxlength="10000">{{ old('deskripsi', $pengaduan->deskripsi) }}</textarea>
                        @error('deskripsi') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-field full-width">
                        <label for="lampiran">Lampiran baru (opsional, maks. 5 MB)</label>
                        <input id="lampiran" type="file" name="lampiran" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                        @if ($pengaduan->lampiran)
                            <a href="{{ route('pengaduan.attachment', $pengaduan) }}">Lihat lampiran saat ini</a>
                        @endif
                        @error('lampiran') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="form-actions">
                    <a href="{{ route('pengaduan.index') }}">Batal</a>
                    <button class="submit-button" type="submit">Simpan perubahan</button>
                </div>
            </form>
        </section>
    </div>
@endsection