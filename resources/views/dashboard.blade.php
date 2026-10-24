@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
	<style>
		.dashboard-heading {
			display: flex;
			justify-content: space-between;
			align-items: end;
			gap: 16px;
			margin-bottom: 24px;
		}

		.dashboard-heading .page-description {
			margin-bottom: 0;
		}

		.stats-grid {
			display: grid;
			grid-template-columns: repeat(4, minmax(0, 1fr));
			gap: 16px;
			margin-bottom: 28px;
		}

		.stat-card {
			background: #fff;
			border: 1px solid #e2e8f0;
			border-top: 3px solid #2563eb;
			border-radius: 8px;
			padding: 18px 20px;
		}

		.stat-card.waiting {
			border-top-color: #d97706;
		}

		.stat-card.processing {
			border-top-color: #0891b2;
		}

		.stat-card.completed {
			border-top-color: #16a34a;
		}

		.stat-label {
			color: #64748b;
			font-size: 14px;
			margin: 0 0 10px;
		}

		.stat-value {
			color: #0f172a;
			font-size: 28px;
			font-weight: 700;
			line-height: 1;
			margin: 0;
		}

		.table-wrap {
			overflow-x: auto;
		}

		.status-pill {
			display: inline-block;
			border-radius: 999px;
			font-size: 12px;
			font-weight: 600;
			padding: 4px 9px;
			white-space: nowrap;
		}

		.status-menunggu {
			background: #fef3c7;
			color: #92400e;
		}

		.status-diproses {
			background: #cffafe;
			color: #155e75;
		}

		.status-selesai {
			background: #dcfce7;
			color: #166534;
		}

		.empty-state {
			color: #64748b;
			padding: 32px 16px !important;
			text-align: center !important;
		}

		@media (max-width: 800px) {
			.stats-grid {
				grid-template-columns: repeat(2, minmax(0, 1fr));
			}
		}

		@media (max-width: 520px) {
			.stats-grid {
				gap: 10px;
			}

			.stat-card {
				padding: 15px;
			}

			.stat-value {
				font-size: 24px;
			}
		}
	</style>
@endpush

@section('content')
	<div class="dashboard-heading">
		<div>
			<h1 class="page-title">Dashboard Pengaduan</h1>
			<p class="page-description">Ringkasan dan pengaduan terbaru yang masuk.</p>
		</div>
		<a href="{{ route('pengaduan.index') }}">Kelola tabel pengaduan</a>
	</div>

	<section class="stats-grid" aria-label="Ringkasan pengaduan">
		<article class="stat-card">
			<p class="stat-label">Total pengaduan</p>
			<p class="stat-value">{{ $totalPengaduan }}</p>
		</article>
		<article class="stat-card waiting">
			<p class="stat-label">Menunggu</p>
			<p class="stat-value">{{ $menunggu }}</p>
		</article>
		<article class="stat-card processing">
			<p class="stat-label">Diproses</p>
			<p class="stat-value">{{ $diproses }}</p>
		</article>
		<article class="stat-card completed">
			<p class="stat-label">Selesai</p>
			<p class="stat-value">{{ $selesai }}</p>
		</article>
	</section>

	<section class="card">
		<h2>Pengaduan terbaru</h2>
		<div class="table-wrap">
			<table class="data-table" data-datatable data-order-column="5">
				<thead>
					<tr>
						<th>Pelapor</th>
						<th>Meja</th>
						<th>Kategori</th>
						<th>Judul</th>
						<th>Status</th>
						<th>Tanggal</th>
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
							<td>{{ $pengaduan->nomor_meja ?: '-' }}</td>
							<td>{{ $pengaduan->kategori }}</td>
							<td>{{ $pengaduan->judul }}</td>
							<td><span class="status-pill {{ $statusClass }}">{{ $pengaduan->status }}</span></td>
							<td data-order="{{ $pengaduan->created_at?->timestamp }}">{{ $pengaduan->created_at?->format('d/m/Y H:i') }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</section>
@endsection
