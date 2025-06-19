<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPRAKTA - Admin Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            --error-color: #dc3545;
            --shadow-medium: 0 4px 15px rgba(0,0,0,0.1);
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
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-medium);
            padding: 30px;
            border: 1px solid var(--border-color);
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--light-blue-bg);
            flex-wrap: wrap; /* Allow wrapping */
        }

        .header-section h2 {
            color: var(--primary-blue);
            font-size: 32px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 0; /* Remove default h2 margin */
        }

        .welcome-message p {
            color: var(--dark-grey);
            font-size: 16px;
            margin-top: 5px;
        }

        .logout-form {
            text-align: right;
            margin-left: auto; /* Push to the right */
        }

        .logout-btn {
            padding: 10px 20px;
            background-color: var(--error-color);
            color: var(--white);
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            font-size: 15px;
        }

        .logout-btn:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }

        .navigation-section {
            margin-bottom: 30px;
        }

        .navigation-section h3 {
            color: var(--dark-blue);
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--medium-grey);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navigation-list {
            list-style: none;
            padding: 0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* Responsive grid for links */
            gap: 15px;
        }

        .navigation-list li {
            background-color: var(--light-blue-bg);
            border-radius: var(--border-radius);
            padding: 15px 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: var(--shadow-light);
            border: 1px solid var(--border-color);
        }

        .navigation-list li:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 123, 255, 0.15);
        }

        .navigation-list a {
            color: var(--primary-blue);
            text-decoration: none;
            font-size: 17px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: color 0.3s ease;
        }

        .navigation-list a:hover {
            color: var(--dark-blue);
        }
        
        /* Specific icon styling for navigation items */
        .navigation-list a .fas {
            font-size: 1.1em;
            color: var(--dark-blue);
        }

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            .dashboard-container {
                padding: 20px;
            }
            .header-section {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            .header-section h2 {
                font-size: 28px;
            }
            .logout-form {
                width: 100%;
                text-align: center;
                margin-top: 15px;
            }
            .logout-btn {
                width: 100%;
                justify-content: center;
            }
            .navigation-section h3 {
                font-size: 20px;
                margin-bottom: 15px;
            }
            .navigation-list {
                grid-template-columns: 1fr; /* Stack links on small screens */
            }
            .navigation-list li {
                padding: 12px 15px;
            }
            .navigation-list a {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <header class="header-section">
            <div class="welcome-area">
                <h2><i class="fas fa-tachometer-alt"></i> Dashboard Admin</h2>
                <p>Selamat datang di panel administrasi SIPRAKTA!</p>
            </div>
            <div class="logout-form">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf 
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </header>

        <section class="navigation-section">
            <h3><i class="fas fa-bars"></i> Menu Navigasi</h3>
            <ul class="navigation-list">
                <li><a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="{{ route('admin.mahasiswa.index') }}"><i class="fas fa-user-graduate"></i> Manajemen Mahasiswa</a></li>
                <li><a href="{{ route('admin.dosen.index') }}"><i class="fas fa-chalkboard-teacher"></i> Manajemen Dosen</a></li>
                <li><a href="{{ route('admin.pengajuan.verifikasi.index') }}"><i class="fas fa-check-double"></i> Verifikasi Pengajuan Sidang</a></li>
                {{-- Link ke daftar pengajuan yang perlu diverifikasi admin --}}
                {{-- Anda bisa menambahkan link ke daftar pengajuan lama jika masih digunakan --}}
                {{-- <li><a href="{{ route('admin.pengajuan.index') }}"><i class="fas fa-list"></i> Daftar Semua Pengajuan (Lama)</a></li> --}}        
                <li><a href="{{ route('admin.sidang.index') }}"><i class="fas fa-calendar-alt"></i> Manajemen Sidang</a></li>
            </ul>
        </section>
    </div>
</body>
</html>