@extends('layouts.app')

@section('title', 'Daftar Pengaduan')

@push('styles')
    <style>
        .list-heading {
            display: flex;
            justify-content: space-between;
            align-items: end;
            gap: 16px;
            margin-bottom: 24px;
        }

        .list-heading .page-description {
            margin-bottom: 0;
        }

        .table-wrap {
            overflow-x: auto;
        }

        .status-pill {
            display: inline-block;
            border-radius: 999px;
            padding: 4px 9px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-menunggu { background: #fef3c7; color: #92400e; }
        .status-diproses { background: #cffafe; color: #155e75; }
        .status-selesai { background: #dcfce7; color: #166534; }

        .attachment-link {
            color: #1d4ed8;
            font-weight: 600;
            text-decoration: none;
        }

        .attachment-link:hover {
            text-decoration: underline;
        }

        .row-actions,
        .status-form {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .row-actions a,
        .row-actions button,
        .status-form button {
            border: 0;
            border-radius: 4px;
            padding: 7px 9px;
            color: #fff;
            font: inherit;
            font-size: 12px;
            text-decoration: none;
            white-space: nowrap;
            cursor: pointer;
        }

        .edit-link { background: #1d4ed8; }
        .delete-button { background: #b91c1c; }
        .status-form button { background: #0f766e; }

        .status-form select {
            min-width: 120px;
            padding: 7px;
        }

        .flash-message {
            margin-bottom: 18px;
            padding: 12px 14px;
            border: 1px solid #86efac;
            border-radius: 5px;
            background: #f0fdf4;
            color: #166534;
        }
    </style>
@endpush

@section('content')
    <div class="list-heading">
        <div>
            <h1 class="page-title">Daftar Pengaduan</h1>
            <p class="page-description">Cari, urutkan, dan telusuri pengaduan yang masuk.</p>
        </div>
        <a href="{{ route('dashboard') }}">Kembali ke dashboard</a>
    </div>

    <section class="card">
        @if (session('success'))
            <div class="flash-message" role="status">{{ session('success') }}</div>
        @endif
        <div class="table-wrap">
            <table class="data-table" data-datatable data-order-column="7">
                <thead>
                    <tr>
                        <th>Pelapor</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Meja</th>
                        <th>Kategori</th>
                        <th>Judul</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Lampiran</th>
                        @if (auth()->user()->role === 'admin')
                            <th>Aksi admin</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengaduans as $pengaduan)
                        @php
                            $statusClass = match ($pengaduan->status) {
                                'Menunggu' => 'status-menunggu',
                                'Diproses' => 'status-diproses',
                                'Selesai' => 'status-selesai',
                                default => '',
                            };
                        @endphp
                        <tr>
                            <td>{{ $pengaduan->nama_pelapor }}</td>
                            <td>{{ $pengaduan->email }}</td>
                            <td>{{ $pengaduan->no_hp }}</td>
                            <td>{{ $pengaduan->nomor_meja ?: '-' }}</td>
                            <td>{{ $pengaduan->kategori }}</td>
                            <td>{{ $pengaduan->judul }}</td>
                            <td><span class="status-pill {{ $statusClass }}">{{ $pengaduan->status }}</span></td>
                            <td data-order="{{ $pengaduan->created_at?->timestamp }}">{{ $pengaduan->created_at?->format('d/m/Y H:i') }}</td>
                            <td>
                                @if ($pengaduan->lampiran)
                                    @if (in_array(strtolower(pathinfo($pengaduan->lampiran, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png'], true))
                                        <a href="{{ route('pengaduan.attachment', $pengaduan) }}" target="_blank" rel="noopener">
                                            <img class="attachment-preview" src="{{ route('pengaduan.attachment', $pengaduan) }}" alt="Foto lampiran pengaduan" loading="lazy">
                                        </a>
                                    @else
                                        <a class="attachment-link" href="{{ route('pengaduan.attachment', $pengaduan) }}">Lihat file</a>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            @if (auth()->user()->role === 'admin')
                                <td>
                                    <div class="row-actions">
                                        <a class="edit-link" href="{{ route('pengaduan.edit', $pengaduan) }}">Edit</a>
                                        <form method="POST" action="{{ route('pengaduan.destroy', $pengaduan) }}" onsubmit="return confirm('Hapus pengaduan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="delete-button" type="submit">Hapus</button>
                                        </form>
                                    </div>
                                    <form class="status-form" method="POST" action="{{ route('pengaduan.status', $pengaduan) }}">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" aria-label="Status pengaduan">
                                            @foreach (['Menunggu', 'Diproses', 'Selesai'] as $status)
                                                <option value="{{ $status }}" @selected($pengaduan->status === $status)>{{ $status }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit">Simpan</button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection