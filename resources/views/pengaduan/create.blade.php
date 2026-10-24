@extends('layouts.app')

@section('title', 'Buat Pengaduan')

@push('styles')
    <style>
        .form-card {
            max-width: 900px;
            margin: 0 auto;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .form-field {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .form-field.full-width {
            grid-column: 1 / -1;
        }

        .form-field label {
            font-size: 14px;
            font-weight: 600;
        }

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

        .form-field textarea {
            min-height: 140px;
            resize: vertical;
        }

        .form-field .attachment-preview {
            max-width: 240px;
            max-height: 180px;
            margin-top: 8px;
        }

        .form-error {
            color: #b91c1c;
            font-size: 13px;
        }

        .form-alert {
            margin-bottom: 18px;
            padding: 12px 14px;
            border: 1px solid #86efac;
            border-radius: 5px;
            background: #f0fdf4;
            color: #166534;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

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

        .submit-button:hover {
            background: #1d4ed8;
        }

        @media (max-width: 640px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-field.full-width {
                grid-column: auto;
            }
        }
    </style>
@endpush

@section('content')
    <div class="form-card">
        <h1 class="page-title">Buat Pengaduan</h1>
        <p class="page-description">Sampaikan kendala pesanan atau layanan Anda.</p>

        @if (session('success'))
            <div class="form-alert" role="status">{{ session('success') }}</div>
        @endif

        <section class="card">
            <form method="POST" action="{{ route('pengaduan.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-grid">
                    <div class="form-field">
                        <label for="nama_pelapor">Nama pelapor</label>
                        <input
                            id="nama_pelapor"
                            name="nama_pelapor"
                            value="{{ auth()->user()?->name ?? old('nama_pelapor') }}"
                            @readonly(auth()->check())
                            required
                            maxlength="255"
                            autocomplete="name"
                        >
                        @error('nama_pelapor') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-field">
                        <label for="email">Email</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ auth()->user()?->email ?? old('email') }}"
                            @readonly(auth()->check())
                            required
                            maxlength="255"
                            autocomplete="email"
                        >
                        @error('email') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-field">
                        <label for="no_hp">Nomor telepon</label>
                        <input id="no_hp" type="tel" name="no_hp" value="{{ old('no_hp') }}" required maxlength="30" autocomplete="tel">
                        @error('no_hp') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-field">
                        <label for="nomor_meja">Nomor meja</label>
                        <select id="nomor_meja" name="nomor_meja" required>
                            <option value="">Pilih nomor meja</option>
                            @foreach (\App\Models\Pengaduan::TABLE_NUMBERS as $nomorMeja)
                                <option value="{{ $nomorMeja }}" @selected(old('nomor_meja') === $nomorMeja)>{{ $nomorMeja }}</option>
                            @endforeach
                        </select>
                        @error('nomor_meja') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-field">
                        <label for="kategori">Kategori</label>
                        <select id="kategori" name="kategori" required>
                            <option value="">Pilih kategori</option>
                            @foreach (\App\Models\Pengaduan::CATEGORIES as $kategori)
                                <option value="{{ $kategori }}" @selected(old('kategori') === $kategori)>{{ $kategori }}</option>
                            @endforeach
                        </select>
                        @error('kategori') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-field">
                        <label for="judul">Judul pengaduan</label>
                        <input id="judul" name="judul" value="{{ old('judul') }}" required maxlength="255">
                        @error('judul') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-field full-width">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" required maxlength="10000">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-field full-width">
                        <label for="lampiran">Lampiran (opsional, maks. 5 MB)</label>
                        <input id="lampiran" type="file" name="lampiran" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                        <img id="lampiran-preview" class="attachment-preview" alt="" hidden>
                        @error('lampiran') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-actions">
                    <button class="submit-button" type="submit">Kirim pengaduan</button>
                </div>
            </form>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        const attachmentInput = document.getElementById('lampiran');
        const attachmentPreview = document.getElementById('lampiran-preview');
        let attachmentPreviewUrl;

        attachmentInput?.addEventListener('change', () => {
            if (attachmentPreviewUrl) {
                URL.revokeObjectURL(attachmentPreviewUrl);
            }

            const selectedFile = attachmentInput.files?.[0];
            if (!selectedFile?.type.startsWith('image/')) {
                attachmentPreview.hidden = true;
                attachmentPreview.removeAttribute('src');
                return;
            }

            attachmentPreviewUrl = URL.createObjectURL(selectedFile);
            attachmentPreview.src = attachmentPreviewUrl;
            attachmentPreview.hidden = false;
        });
    </script>
@endpush