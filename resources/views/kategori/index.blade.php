@extends('layouts.app')

@section('title', 'Tabel Kategori')

@push('styles')
    <style>
        .category-form {
            display: flex;
            align-items: end;
            flex-wrap: wrap;
            gap: 12px;
        }

        .category-heading {
            display: flex;
            justify-content: space-between;
            align-items: end;
            gap: 16px;
            margin-bottom: 24px;
        }

        .category-heading .page-description {
            margin-bottom: 0;
        }

        .category-form label {
            display: flex;
            flex: 1;
            flex-direction: column;
            gap: 7px;
            min-width: 220px;
            font-weight: 600;
        }

        .category-form input {
            width: 100%;
        }

        .category-button,
        .category-actions a,
        .category-actions button {
            display: inline-block;
            border: 0;
            border-radius: 5px;
            padding: 9px 13px;
            color: #fff;
            font: inherit;
            text-decoration: none;
            cursor: pointer;
        }

        .category-button,
        .category-edit {
            background: #1d4ed8;
        }

        .category-delete {
            background: #b91c1c;
        }

        .category-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .category-actions form {
            margin: 0;
        }

        .category-message {
            margin-bottom: 16px;
            padding: 12px 14px;
            border-radius: 5px;
        }

        .category-message.success {
            border: 1px solid #86efac;
            background: #f0fdf4;
            color: #166534;
        }

        .category-message.error {
            border: 1px solid #fca5a5;
            background: #fef2f2;
            color: #991b1b;
        }

        .category-validation-error {
            color: #b91c1c;
            font-size: 13px;
        }
    </style>
@endpush

@section('content')
    <div class="category-heading">
        <div>
            <h1 class="page-title">Tabel Kategori</h1>
            <p class="page-description">Kategori yang tersedia untuk dipilih pada formulir pengaduan.</p>
        </div>
        <a href="{{ route('dashboard') }}">Kembali ke dashboard</a>
    </div>

    @if (session('success'))
        <div class="category-message success" role="status">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="category-message error" role="alert">{{ session('error') }}</div>
    @endif

    @if (auth()->user()->role === 'admin')
        <section class="card">
            <h2>Tambah kategori</h2>
            <form class="category-form" method="POST" action="{{ route('kategori.store') }}">
                @csrf
                <label for="name">
                    Nama kategori
                    <input id="name" name="name" value="{{ old('name') }}" required maxlength="100">
                    @error('name') <span class="category-validation-error">{{ $message }}</span> @enderror
                </label>
                <button class="category-button" type="submit">Tambah kategori</button>
            </form>
        </section>
    @endif

    <section class="card">
        <h2>Daftar kategori</h2>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama kategori</th>
                        <th>Jumlah pengaduan</th>
                        @if (auth()->user()->role === 'admin')
                            <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kategoris as $kategori)
                        <tr>
                            <td>{{ $kategori->name }}</td>
                            <td>{{ $kategori->pengaduans_count }}</td>
                            @if (auth()->user()->role === 'admin')
                                <td>
                                    <div class="category-actions">
                                        <a class="category-edit" href="{{ route('kategori.edit', $kategori) }}">Edit</a>
                                        <form method="POST" action="{{ route('kategori.destroy', $kategori) }}" onsubmit="return confirm('Hapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="category-delete" type="submit">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role === 'admin' ? 3 : 2 }}">Belum ada kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
