@extends('layouts.app')

@section('title', 'Edit Kategori')

@push('styles')
    <style>
        .category-form {
            display: flex;
            flex-direction: column;
            gap: 8px;
            max-width: 560px;
        }

        .category-form label {
            font-weight: 600;
        }

        .category-form input {
            width: 100%;
            margin-top: 7px;
        }

        .category-error {
            color: #b91c1c;
            font-size: 13px;
        }

        .category-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 18px;
        }

        .category-button {
            border: 0;
            border-radius: 5px;
            padding: 10px 14px;
            background: #1d4ed8;
            color: #fff;
            font: inherit;
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    <h1 class="page-title">Edit Kategori</h1>
    <p class="page-description">Perubahan nama juga akan memperbarui pengaduan yang memakai kategori ini.</p>

    <section class="card">
        <form class="category-form" method="POST" action="{{ route('kategori.update', $kategori) }}">
            @csrf
            @method('PUT')
            <label for="name">Nama kategori</label>
            <input id="name" name="name" value="{{ old('name', $kategori->name) }}" required maxlength="100">
            @error('name') <span class="category-error">{{ $message }}</span> @enderror
            <div class="category-actions">
                <a href="{{ route('kategori.index') }}">Batal</a>
                <button class="category-button" type="submit">Simpan perubahan</button>
            </div>
        </form>
    </section>
@endsection
