<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPRAKTA - Mahasiswa Dashboard</title>
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
            --success-color: #28a745;
            --error-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --shadow-light: 0 2px 8px rgba(0,0,0,0.05);
            --shadow-medium: 0 4px 15px rgba(0,0,0,0.1);
            --border-radius: 10px; /* Increased for softer look */
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

        .dashboard-container {
            max-width: 1200px;
            width: 100%;
            margin: 0 auto; /* Center the container */
            padding: 0; /* No extra padding on container itself, let child elements manage */
        }

        .dashboard-header {
            background-color: var(--white);
            border-radius: var(--border-radius);
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: var(--shadow-medium);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap; /* Allow wrapping on smaller screens */
        }

        .welcome-message h1 {
            color: var(--primary-blue);
            margin-bottom: 8px;
            font-size: 28px;
            font-weight: 700;
        }

        .welcome-message p {
            color: var(--dark-grey);
            font-size: 16px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 60px; /* Slightly larger */
            height: 60px;
            background-color: var(--light-blue-bg);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-blue);
            font-size: 24px; /* Larger icon */
            box-shadow: var(--shadow-light);
        }

        .user-info p {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark-blue);
        }

        .dashboard-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); /* Adjusted min-width for better fit */
            gap: 25px; /* Increased gap */
            margin-bottom: 30px;
        }

        .dashboard-card {
            background-color: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: var(--shadow-medium);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between; /* Push link to bottom */
            border: 1px solid var(--border-color); /* Added subtle border */
        }

        .dashboard-card:hover {
            transform: translateY(-8px); /* More pronounced lift */
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15); /* Stronger shadow on hover */
        }

        .card-icon {
            background-color: var(--light-blue-bg);
            width: 70px; /* Larger icon container */
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px; /* More space */
            color: var(--primary-blue);
            font-size: 32px; /* Larger icon */
            box-shadow: var(--shadow-light);
        }

        .card-title {
            font-size: 22px;
            margin-bottom: 10px;
            color: var(--dark-blue);
            font-weight: 600;
        }

        .card-description {
            color: var(--dark-grey);
            margin-bottom: 20px; /* More space */
            font-size: 15px;
            flex-grow: 1; /* Allow description to take available space */
        }

        .card-link {
            display: inline-flex; /* Use flex for icon and text alignment */
            align-items: center;
            gap: 8px; /* Space between icon and text */
            padding: 10px 20px;
            background-color: var(--primary-blue);
            color: var(--white);
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: background-color 0.3s ease, transform 0.2s ease;
            align-self: flex-start; /* Align link to the left within the card */
        }

        .card-link:hover {
            background-color: var(--dark-blue);
            transform: translateY(-2px);
        }

        .logout-section {
            background-color: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: var(--shadow-medium);
            text-align: right;
            border: 1px solid var(--border-color);
        }

        .logout-btn {
            padding: 12px 25px;
            background-color: var(--error-color); /* Using error color from root */
            color: var(--white);
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            font-size: 16px;
        }

        .logout-btn:hover {
            background-color: #c82333; /* Darker red */
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            .dashboard-header {
                flex-direction: column;
                text-align: center;
                gap: 20px; /* More space when stacked */
                padding: 20px;
            }
            
            .user-info {
                justify-content: center;
                width: 100%; /* Take full width */
            }

            .welcome-message h1 {
                font-size: 24px;
            }

            .welcome-message p {
                font-size: 14px;
            }

            .user-avatar {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }

            .user-info p {
                font-size: 16px;
            }

            .dashboard-content {
                gap: 20px;
            }

            .dashboard-card {
                padding: 20px;
            }

            .card-icon {
                width: 60px;
                height: 60px;
                font-size: 28px;
                margin-bottom: 15px;
            }
            .card-title {
                font-size: 20px;
            }
            .card-description {
                font-size: 14px;
                margin-bottom: 15px;
            }
            .card-link {
                width: 100%; /* Make links full width */
                justify-content: center; /* Center content of link */
            }
            .logout-section {
                padding: 20px;
            }
            .logout-btn {
                width: 100%;
                justify-content: center;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <header class="dashboard-header">
            <div class="welcome-message">
                <h1>Selamat datang di SIPRAKTA</h1>
                <p>Sistem Informasi Praktek Kerja Lapangan dan Tugas Akhir</p>
            </div>
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <p>Mahasiswa</p>
                    {{-- Optionally display user's name if available --}}
                    {{-- <p>{{ Auth::user()->name }}</p> --}} 
                </div>
            </div>
        </header>

        <main class="dashboard-content">
            <div class="dashboard-card">
                <div class="card-icon">
                    <i class="fas fa-list-alt"></i>
                </div>
                <h3 class="card-title">Pengajuan Saya</h3>
                <p class="card-description">Lihat dan kelola semua pengajuan PKL/TA Anda, mulai dari draf hingga status akhir.</p>
                <a href="{{ route('mahasiswa.pengajuan.index') }}" class="card-link">
                    <i class="fas fa-arrow-right"></i> Lihat Daftar
                </a>
            </div>

            <div class="dashboard-card">
                <div class="card-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3 class="card-title">Jadwal Sidang</h3>
                <p class="card-description">Periksa jadwal sidang PKL/TA Anda yang telah ditetapkan, termasuk waktu dan lokasi.</p>
                <a href="#" class="card-link">
                    <i class="fas fa-arrow-right"></i> Lihat Jadwal
                </a>
            </div>

            <div class="dashboard-card">
                <div class="card-icon">
                    <i class="fas fa-file-upload"></i>
                </div>
                <h3 class="card-title">Pengumpulan Dokumen</h3>
                <p class="card-description">Unggah dokumen-dokumen penting terkait PKL/TA Anda dengan mudah dan aman.</p>
                <a href="#" class="card-link">
                    <i class="fas fa-arrow-right"></i> Upload Dokumen
                </a>
            </div>

            <div class="dashboard-card">
                <div class="card-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <h3 class="card-title">Notifikasi</h3>
                <p class="card-description">Tetap terinformasi dengan pemberitahuan terbaru mengenai status pengajuan Anda.</p>
                <a href="#" class="card-link">
                    <i class="fas fa-arrow-right"></i> Lihat Notifikasi
                </a>
            </div>
        </main>

        <div class="logout-section">
            <form action="{{ route('mahasiswa.logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>
</body>
</html>