<?php
    // koneksi
$conn = mysqli_connect("localhost", "root", "", "uprak2");
    // Mengambil data dari database berdasarkan ID yang diberikan melalui parameter GET
$nisn = $_GET['id'];
// menampilkan data dari tabel 
$query = "SELECT * FROM tb_sekolah WHERE nisn='$nisn'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dosen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white/70 backdrop-blur-lg rounded-2xl shadow-2xl border border-white/30 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6">
                <h1 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-user-edit mr-4 text-white/80"></i>Edit Data Dosen
                </h1>
            </div>
        <!-- Form untuk menampilkan data yang akan diupdate -->
            <form action="process.php" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                <input type="hidden" name="nisn" value="<?php echo $row['nisn']; ?>">
                
                <div class="flex items-center justify-center mb-6">
                    <div class="relative">
                        <img src="uploads/<?php echo $row['foto_guru']; ?>" 
                             class="w-40 h-40 rounded-full object-cover border-4 border-blue-200 shadow-lg" 
                             alt="Current photo">
                        <div class="absolute bottom-0 right-0 bg-blue-500 text-white rounded-full p-2 shadow-lg">
                            <i class="fas fa-camera"></i>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-id-card text-blue-500"></i>
                        </div>
                        <input type="text" value="<?php echo $row['nisn']; ?>" 
                            class="w-full pl-10 px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-100 cursor-not-allowed" 
                            disabled>
                    </div>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-blue-500"></i>
                        </div>
                        <input type="text" name="nama_guru" value="<?php echo $row['nama_guru']; ?>" placeholder="Nama Dosen" 
                            class="w-full pl-10 px-4 py-3 border-2 border-blue-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" 
                            required>
                    </div>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-calendar-alt text-blue-500"></i>
                        </div>
                        <input type="date" name="tgl_mulai_tugas" value="<?php echo $row['tgl_mulai_tugas']; ?>"
                            class="w-full pl-10 px-4 py-3 border-2 border-blue-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" 
                            required>
                    </div>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-graduation-cap text-blue-500"></i>
                        </div>
                        <select name="jenjang_pendidikan" 
                            class="w-full pl-10 px-4 py-3 border-2 border-blue-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                            <option value="D3" <?php echo $row['jenjang_pendidikan'] == 'D3' ? 'selected' : ''; ?>>D3</option>
                            <option value="S1" <?php echo $row['jenjang_pendidikan'] == 'S1' ? 'selected' : ''; ?>>S1</option>
                        </select>
                    </div>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-book text-blue-500"></i>
                        </div>
                        <input type="text" name="bidang_keahlian" value="<?php echo $row['bidang_keahlian']; ?>" placeholder="Bidang Keahlian" 
                            class="w-full pl-10 px-4 py-3 border-2 border-blue-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" 
                            required>
                    </div>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-upload text-blue-500"></i>
                        </div>
                        <input type="file" name="foto_guru" 
                            class="w-full pl-10 px-4 py-3 border-2 border-blue-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all 
                            file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="mt-2 text-sm text-gray-500 pl-10">Kosongkan jika tidak ingin mengganti foto</p>
                    </div>
                </div>

                <div class="flex space-x-4 mt-6">
                    <a href="dashboard-admin.php" class="w-1/2 bg-gray-300 text-gray-800 py-3 rounded-xl hover:bg-gray-400 transition-all flex items-center justify-center space-x-3">
                        <i class="fas fa-times-circle"></i>
                        <span>Batal</span>
                    </a>
                    <button type="submit" name="update" 
                        class="w-1/2 bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700 transition-all flex items-center justify-center space-x-3">
                        <i class="fas fa-save"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // Success and Error Message Handling
    <?php if (isset($_GET['success'])): ?>
        Swal.fire({
            title: 'Berhasil!',
            text: '<?php echo $_GET["success"]; ?>',
            icon: 'success',
            confirmButtonColor: '#3085d6'
        });
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        Swal.fire({
            title: 'Error!',
            text: '<?php echo $_GET["error"]; ?>',
            icon: 'error',
            confirmButtonColor: '#3085d6'
        });
    <?php endif; ?>
    </script>
</body>
</html>
