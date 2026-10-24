
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Marketplace')</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        .app-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 250px minmax(0, 1fr);
        }

        .sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            background: #111827;
            color: white;
            padding: 24px 16px;
            display: flex;
            flex-direction: column;
            gap: 28px;
        }

        .navbar-brand {
            padding: 0 12px;
            font-size: 22px;
            font-weight: bold;
        }

        .navbar-menu {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .navbar-menu a {
            display: block;
            padding: 11px 12px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .navbar-menu a:hover {
            background: #374151;
        }

        .navbar-menu form {
            margin-top: 12px;
        }

        .logout-button {
            width: 100%;
            background: #b91c1c;
            color: white;
            border: 0;
            padding: 10px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        .main-shell {
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 28px;
            flex: 1;
        }

        .page-title {
            margin: 0 0 8px;
            font-size: 28px;
            color: #1e293b;
        }

        .page-description {
            color: #64748b;
            margin: 0 0 24px;
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 22px;
            box-shadow: 0 2px 8px rgba(15, 23, 42, .06);
            margin-bottom: 20px;
        }

        .card h2 {
            margin-top: 0;
            font-size: 20px;
        }

        .data-table-controls {
            display: flex;
            justify-content: space-between;
            align-items: end;
            flex-wrap: wrap;
            gap: 16px;
            margin: 18px 0;
        }

        .data-table-controls label {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            font-size: 14px;
        }

        input,
        select {
            padding: 9px 10px;
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            background: white;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .data-table th,
        .data-table td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .data-table th {
            background: #f8fafc;
            white-space: nowrap;
        }

        .attachment-preview {
            display: block;
            width: auto;
            max-width: 160px;
            max-height: 110px;
            border-radius: 4px;
            object-fit: cover;
        }

        .data-table th button {
            border: 0;
            background: transparent;
            font: inherit;
            font-weight: bold;
            color: #334155;
            cursor: pointer;
            padding: 0;
        }

        .data-table-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 18px;
            font-size: 14px;
        }

        .data-table-pagination {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .data-table-pagination button {
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            padding: 8px 12px;
            background: white;
            cursor: pointer;
        }

        .data-table-pagination button:disabled {
            opacity: .5;
            cursor: not-allowed;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #64748b;
            font-size: 13px;
        }

        @media (max-width: 720px) {
            .app-shell {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: static;
                height: auto;
                gap: 16px;
                padding: 16px;
            }

            .navbar-brand {
                padding: 0;
            }

            .navbar-menu {
                flex-direction: row;
                align-items: center;
                flex-wrap: wrap;
                gap: 4px;
            }

            .navbar-menu form {
                margin: 0 0 0 auto;
            }

            .logout-button {
                width: auto;
            }

            .container {
                padding: 16px;
            }

            .page-title {
                font-size: 23px;
            }

            .card {
                padding: 16px;
            }
        }
    </style>

    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
    @stack('styles')
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <div class="navbar-brand">Marketplace</div>

            <nav class="navbar-menu" aria-label="Navigasi utama">
                @auth
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('pengaduan.index') }}">Kelola tabel pengaduan</a>
                    @else
                        <a href="{{ route('pengaduan.create') }}">Buat pengaduan</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-button">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Login</a>
                @endauth
            </nav>
        </aside>

        <div class="main-shell">
            <main class="container">
                @yield('content')
            </main>

            <footer class="footer">
                &copy; {{ date('Y') }} Marketplace - Layanan Pengaduan
            </footer>
        </div>
    </div>

    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-datatable]').forEach((table) => {
                new DataTable(table, {
                    pageLength: 10,
                    order: [[Number(table.dataset.orderColumn || 0), 'desc']],
                    language: {
                        emptyTable: 'Belum ada data.',
                        info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                        infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
                        infoFiltered: '(disaring dari _MAX_ total data)',
                        lengthMenu: 'Tampilkan _MENU_ data',
                        loadingRecords: 'Memuat...',
                        processing: 'Memproses...',
                        search: 'Cari:',
                        zeroRecords: 'Data tidak ditemukan',
                        paginate: {
                            first: 'Pertama',
                            last: 'Terakhir',
                            next: 'Berikutnya',
                            previous: 'Sebelumnya'
                        }
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
