<?php
// memulai sesi output
ob_start();
// koneksi
$conn = mysqli_connect("localhost", "root", "", "uprak2");
// kondisi database
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$page = isset($_GET['page']) ? $_GET['page'] : 'pendaftaran';

// pengkondisian menampilkan suatu form dengan menggunakan get
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    try {
        $nisn = $_POST['nisn'];
        $nama_guru = $_POST['nama_guru'];
        $tgl_mulai_tugas = $_POST['tgl_mulai_tugas'];
        $jenjang_pendidikan = $_POST['jenjang_pendidikan'];
        $bidang_keahlian = $_POST['bidang_keahlian'];

        // validasi harus diisi
        $errors = [];
        if (empty($nisn)) $errors[] = "NISN harus diisi";
        if (empty($nama_guru)) $errors[] = "Nama Dosen harus diisi";
        if (empty($tgl_mulai_tugas)) $errors[] = "Tanggal mulai tugas harus diisi";
        if (empty($bidang_keahlian)) $errors[] = "Bidang keahlian harus diisi";

        // Mengecek apakah file foto_guru diunggah
        // Jika file diunggah, periksa apakah folder 'uploads/' ada
        $foto_guru = '';
        if (!empty($_FILES['foto_guru']['name'])) {
            $target_dir = "uploads/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            // ukuran maximal
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_file_size = 5 * 1024 * 1024; // 5MB
            // file cmn jpeg,png dan gif
            if (!in_array($_FILES['foto_guru']['type'], $allowed_types)) {
                $errors[] = "Hanya file gambar (JPEG, PNG, GIF) yang diperbolehkan";
            }
            // pengkondisian ukuran file gambar
            if ($_FILES['foto_guru']['size'] > $max_file_size) {
                $errors[] = "Ukuran file tidak boleh melebihi 5MB";
            }
            // Jika tidak ada kesalahan, proses upload dan simpan file dengan nama unik
            if (empty($errors)) {
                $foto_guru = uniqid() . '_' . basename($_FILES['foto_guru']['name']);
                $target_file = $target_dir . $foto_guru;
                move_uploaded_file($_FILES['foto_guru']['tmp_name'], $target_file);
            }
        }
            // menambahkan data logika database dml insert
        if (empty($errors)) {
            $query = "INSERT INTO tb_sekolah (nisn, nama_guru, tgl_mulai_tugas, jenjang_pendidikan, bidang_keahlian, foto_guru) 
                      VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ssssss", $nisn, $nama_guru, $tgl_mulai_tugas, $jenjang_pendidikan, $bidang_keahlian, $foto_guru);
            mysqli_stmt_execute($stmt);

            // SweetAlert Success Message
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Registrasi Berhasil!',
                    text: 'Data dosen berhasil ditambahkan.',
                    showConfirmButton: false,
                    timer: 2000,
                    background: '#f0f8ff',
                    iconColor: '#3b82f6',
                    timerProgressBar: true
                }).then(() => {
                    window.location.href = window.location.href;
                });
            </script>";
        } else {
            // Display validation errors
            $error_message = implode('\\n', $errors);
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Registrasi',
                    text: '$error_message',
                    confirmButtonColor: '#3b82f6',
                    background: '#fff0f0'
                });
            </script>";
        }
    } catch (Exception $e) {
        // SweetAlert Error Message
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Terjadi kesalahan: " . $e->getMessage() . "',
                confirmButtonColor: '#ef4444'
            });
        </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pendaftaran Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e6f2ff 0%, #f0f8ff 100%);
        }
        .soft-shadow {
            box-shadow: 0 10px 25px rgba(0, 123, 255, 0.1);
        }
        .glassmorphic {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">
<nav class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 shadow-md">
    <div class="container mx-auto flex justify-center space-x-6">
        <!-- Link Pendaftaran Dosen -->
        <a href="?page=pendaftaran" 
           class="px-6 py-2 rounded-full flex items-center space-x-2 transition-all 
                  <?= $page == 'pendaftaran' ? 'bg-white text-blue-700' : 'text-white hover:bg-blue-700 hover:text-white' ?>">
            <i class="fas fa-user-plus"></i>
            <span>Pendaftaran Dosen</span>
        </a>
        <!-- Link Lihat Data Dosen -->
        <a href="?page=lihat-data" 
           class="px-6 py-2 rounded-full flex items-center space-x-2 transition-all 
                  <?= $page == 'lihat-data' ? 'bg-white text-blue-700' : 'text-white hover:bg-blue-700 hover:text-white' ?>">
            <i class="fas fa-list"></i>
            <span>Lihat Data Dosen</span>
        </a>
        <!-- Link Kembali ke Beranda -->
        <a href="index.html" 
           class="flex items-center px-6 py-2 rounded-full transition-all group text-white/80 hover:bg-white/10 hover:text-white">
            <i class="fas fa-sign-out-alt mr-4 text-xl opacity-70 group-hover:opacity-100"></i>
            <span class="font-semibold">Kembali ke Beranda</span>
        </a>
    </div>
</nav>


    <div class="container mx-auto max-w-6xl flex-grow py-12 px-4">
        <?php if ($page == 'pendaftaran'): ?>
            <div class="bg-white glassmorphic rounded-2xl soft-shadow overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-700 p-6 text-center">
                    <h1 class="text-3xl font-bold text-white tracking-wide">
                        <i class="fas fa-user-graduate mr-3"></i>Sistem Pendaftaran Dosen
                    </h1>
                </div>
                <div class="p-8 space-y-6">
                      <!-- form registrasi -->   
                <form action="<?php echo $_SERVER['PHP_SELF'] . '?page=pendaftaran'; ?>" method="POST" enctype="multipart/form-data" class="space-y-5">

                        <div class="grid md:grid-cols-2 gap-5">
                            <div class="relative">
                                <label class="block text-blue-700 mb-2">NISN</label>
                                <div class="relative">
                                    <i class="fas fa-id-card absolute top-3.5 left-4 text-blue-500"></i>
                                    <input type="text" name="nisn" placeholder="Masukkan NISN" 
                                        class="w-full pl-12 px-4 py-3 rounded-xl border-2 border-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" 
                                        required>
                                </div>
                            </div>
                            <div class="relative">
                                <label class="block text-blue-700 mb-2">Nama Dosen</label>
                                <div class="relative">
                                    <i class="fas fa-user absolute top-3.5 left-4 text-blue-500"></i>
                                    <input type="text" name="nama_guru" placeholder="Nama lengkap" 
                                        class="w-full pl-12 px-4 py-3 rounded-xl border-2 border-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" 
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="grid md:grid-cols-2 gap-5">
                            <div class="relative">
                                <label class="block text-blue-700 mb-2">Tanggal Mulai Tugas</label>
                                <div class="relative">
                                    <i class="fas fa-calendar-alt absolute top-3.5 left-4 text-blue-500"></i>
                                    <input type="date" name="tgl_mulai_tugas" 
                                        class="w-full pl-12 px-4 py-3 rounded-xl border-2 border-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" 
                                        required>
                                </div>
                            </div>
                            <div class="relative">
                                <label class="block text-blue-700 mb-2">Jenjang Pendidikan</label>
                                <div class="relative">
                                    <i class="fas fa-graduation-cap absolute top-3.5 left-4 text-blue-500"></i>
                                    <select name="jenjang_pendidikan" 
                                        class="w-full pl-12 px-4 py-3 rounded-xl border-2 border-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                                        <option value="D3">D3</option>
                                        <option value="S1">S1</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="grid md:grid-cols-2 gap-5">
                            <div class="relative">
                                <label class="block text-blue-700 mb-2">Bidang Keahlian</label>
                                <div class="relative">
                                    <i class="fas fa-book absolute top-3.5 left-4 text-blue-500"></i>
                                    <input type="text" name="bidang_keahlian" placeholder="Bidang keahlian" 
                                        class="w-full pl-12 px-4 py-3 rounded-xl border-2 border-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" 
                                        required>
                                </div>
                            </div>
                            <div class="relative">
                                <label class="block text-blue-700 mb-2">Foto Profil</label>
                                <div class="relative">
                                    <i class="fas fa-upload absolute top-3.5 left-4 text-blue-500"></i>
                                    <input type="file" name="foto_guru" accept="image/*" 
                                        class="w-full pl-12 px-4 py-3 rounded-xl border-2 border-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all 
                                        file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                </div>
                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="submit" name="add" 
                                class="w-full bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700 transition-all flex items-center justify-center space-x-3 transform hover:scale-[1.02] active:scale-[0.98]">
                                <i class="fas fa-plus-circle"></i>
                                <span>Daftarkan Dosen</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php elseif ($page == 'lihat-data'): ?>
            <div class="bg-white glassmorphic rounded-2xl soft-shadow overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-700 p-6 text-center">
                    <h2 class="text-3xl font-bold text-white tracking-wide">
                        <i class="fas fa-list mr-3"></i>Daftar Dosen yang sudah mendaftar
                    </h1>
                </div>
                <div class="p-8">
                    <?php
                    // tampilkan smua data logik db dml
                    $query = "SELECT * FROM tb_sekolah ORDER BY tgl_mulai_tugas DESC";
                    $result = mysqli_query($conn, $query);
                    ?>
                    <div class="overflow-x-auto">
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php while($row = mysqli_fetch_assoc($result)): ?>
    <div class="bg-white shadow-lg rounded-2xl overflow-hidden transform transition hover:scale-105 hover:shadow-xl">
        <div class="relative">
            <?php if(!empty($row['foto_guru'])): ?>
                <img src="uploads/<?= htmlspecialchars($row['foto_guru']) ?>" 
                     alt="Foto Dosen" 
                     class="w-full h-48 object-cover">
            <?php else: ?>
                <div class="w-full h-48 bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-user-circle text-6xl text-blue-300"></i>
                </div>
            <?php endif; ?>
            <div class="absolute top-0 right-0 bg-blue-600 text-white px-3 py-1">
                <?= htmlspecialchars($row['jenjang_pendidikan']) ?>
            </div>
        </div>
        <div class="p-5">
            <h3 class="text-xl font-bold text-blue-800 mb-2">
                <?= htmlspecialchars($row['nama_guru']) ?>
            </h3>
            <div class="space-y-2 text-sm text-gray-600">
                <p><i class="fas fa-id-badge mr-2 text-blue-500"></i>NISN: <?= htmlspecialchars($row['nisn']) ?></p>
                <p><i class="fas fa-book mr-2 text-blue-500"></i>Bidang: <?= htmlspecialchars($row['bidang_keahlian']) ?></p>
                <p><i class="fas fa-calendar-alt mr-2 text-blue-500"></i>Mulai Tugas: <?= htmlspecialchars($row['tgl_mulai_tugas']) ?></p>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>
<?php if(mysqli_num_rows($result) == 0): ?>
    <div class="text-center text-gray-600 py-12">
        <i class="fas fa-inbox text-6xl mb-4 text-blue-300"></i>
        <p class="text-xl">Belum ada data dosen yang terdaftar</p>
    </div>
<?php endif; ?>
                        <?php if(mysqli_num_rows($result) == 0): ?>
                            <div class="text-center text-gray-600 py-6">
                                <i class="fas fa-inbox text-4xl mb-4"></i>
                                <p>Belum ada data dosen yang terdaftar</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <footer class="bg-blue-800 text-white py-4 text-center">
        <p>&copy; 2024 Sistem Pendaftaran Dosen. All Rights Reserved.</p>
    </footer>
</body>
</html>
<?php
// menyelesaikan sesi output
mysqli_close($conn);
ob_end_flush();
?>