<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPRAKTA - Daftar Dosen</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #007bff;
            --dark-blue: #0056b3;
            --light-blue-bg: #e6f2ff;
            --white: #ffffff;
            --light-grey: #f8f9fa;
            --medium-grey: #dee2e6;
            --dark-grey: #495057;
            --text-color: #343a40;
            --border-color: #ced4da;
            --success-color: #28a745;
            --error-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --shadow-medium: 0 4px 15px rgba(0,0,0,0.1);
            --shadow-light: 0 2px 8px rgba(0,0,0,0.05);
            --border-radius: 10px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-grey);
            color: var(--text-color);
            line-height: 1.6;
            padding: 30px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            width: 100%;
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-medium);
            padding: 30px;
            border: 1px solid var(--border-color);
        }

        h2 {
            color: var(--primary-blue);
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--light-blue-bg);
            font-size: 28px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
            border: 1px solid var(--success-color);
            border-radius: var(--border-radius);
            padding: 15px;
            margin-bottom: 25px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .action-links {
            margin-bottom: 25px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: flex-start;
        }

        .action-link-btn {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            background-color: var(--primary-blue);
            color: var(--white);
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: var(--shadow-light);
        }

        .action-link-btn:hover {
            background-color: var(--dark-blue);
            transform: translateY(-2px);
        }
        .action-link-btn.secondary {
            background-color: var(--medium-grey);
            color: var(--text-color);
        }
        .action-link-btn.secondary:hover {
            background-color: #adb5bd;
        }
        .action-link-btn i {
            margin-right: 8px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 30px;
            background-color: var(--white);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-light);
        }

        th, td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid var(--medium-grey);
            font-size: 15px;
        }

        th {
            background-color: var(--primary-blue);
            color: var(--white);
            font-weight: 600;
            text-transform: uppercase;
        }

        th:first-child { border-top-left-radius: var(--border-radius); }
        th:last-child { border-top-right-radius: var(--border-radius); }

        tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover {
            background-color: var(--light-blue-bg);
        }

        .table-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .table-actions a,
        .table-actions button {
            display: inline-flex;
            align-items: center;
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9em;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            white-space: nowrap;
        }

        .table-actions a i,
        .table-actions button i {
            margin-right: 5px;
        }

        .btn-detail {
            background-color: var(--light-blue-bg);
            color: var(--primary-blue);
            border: 1px solid var(--primary-blue);
        }
        .btn-detail:hover {
            background-color: var(--primary-blue);
            color: var(--white);
            transform: translateY(-2px);
        }

        .btn-edit {
            background-color: var(--warning-color);
            color: var(--white);
        }
        .btn-edit:hover {
            background-color: #e0a800;
            transform: translateY(-2px);
        }

        .btn-delete {
            background-color: var(--error-color);
            color: var(--white);
        }
        .btn-delete:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            .container {
                padding: 20px;
            }
            h2 {
                font-size: 24px;
                flex-direction: column;
                gap: 8px;
            }
            h2 .fas {
                font-size: 28px;
            }
            .action-links {
                flex-direction: column;
                gap: 10px;
            }
            .action-link-btn {
                width: 100%;
                justify-content: center;
            }
            table, thead, tbody, th, td, tr {
                display: block;
            }
            thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            tr {
                border: 1px solid var(--medium-grey);
                margin-bottom: 15px;
                border-radius: var(--border-radius);
                overflow: hidden;
            }
            td {
                border: none;
                position: relative;
                padding-left: 50%;
                text-align: right;
                word-wrap: break-word;
            }
            td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                text-align: left;
                font-weight: 600;
                color: var(--dark-blue);
            }
            .table-actions {
                justify-content: flex-end;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-users-cog"></i> Daftar Dosen</h2>

        @if (session('success'))
            <div class="alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="action-links">
            <a href="{{ route('admin.dosen.create') }}" class="action-link-btn">
                <i class="fas fa-user-plus"></i> Tambah Dosen
            </a>
            <a href="{{ route('admin.dosen.import') }}" class="action-link-btn">
                <i class="fas fa-file-import"></i> Impor Dosen
            </a>
            <a href="{{ route('admin.dashboard') }}" class="action-link-btn secondary">
                <i class="fas fa-arrow-left"></i> Kembali Ke Dashboard
            </a>
        </div>

        @if($dosens->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>NIDN</th>
                    <th>Nama Lengkap</th>
                    <th>Jurusan</th>
                    <th>Prodi</th>
                    <th>Jenis Kelamin</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dosens as $dosen)
                <tr>
                    <td data-label="NIDN">{{ $dosen->nidn }}</td>
                    <td data-label="Nama Lengkap">{{ $dosen->nama }}</td>
                    <td data-label="Jurusan">{{ $dosen->jurusan }}</td>
                    <td data-label="Prodi">{{ $dosen->prodi }}</td>
                    <td data-label="Jenis Kelamin">{{ $dosen->jenis_kelamin }}</td>
                    <td data-label="Aksi">
                        <div class="table-actions">
                            <a href="{{ route('admin.dosen.show', $dosen->id) }}" class="btn-detail">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            <a href="{{ route('admin.dosen.edit', $dosen->id) }}" class="btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.dosen.destroy', $dosen->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus dosen ini?')" class="btn-delete">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="alert alert-info" style="text-align: center;">
            <i class="fas fa-info-circle"></i> Belum ada data dosen yang tersedia. Silakan tambahkan atau impor data.
        </div>
        @endif
    </div>
</body>
</html>