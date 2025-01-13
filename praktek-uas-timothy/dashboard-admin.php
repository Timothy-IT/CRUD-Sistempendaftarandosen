<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elegant Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #667eea, #764ba2);
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen flex">
    <!-- side navbar -->
    <div class="w-80 bg-gradient-to-br from-indigo-600 to-purple-700 text-white h-screen fixed left-0 top-0 shadow-2xl z-50">
        <div class="p-8 border-b border-white/20">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-tiktok text-3xl text-white"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">Dashboard Admin</h2>
                    <p class="text-sm text-white/70">Teacher Management System</p>
                </div>
            </div>
        </div>
        <nav class="mt-10 space-y-2 px-4">
            <a href="#kelola-data" class="sidebar-link flex items-center p-3 rounded-xl hover:bg-white/10 transition-all group">
                <i class="fas fa-list-alt mr-4 text-xl opacity-70 group-hover:opacity-100"></i>
                <span class="font-semibold">Kelola Data</span>
            </a>
            <a href="#tambahkan-data" class="sidebar-link flex items-center p-3 rounded-xl hover:bg-white/10 transition-all group">
                <i class="fas fa-plus-circle mr-4 text-xl opacity-70 group-hover:opacity-100"></i>
                <span class="font-semibold">Tambahkan Data</span>
            </a>
            
            <!-- logout -->
            <a href="login-admin.php" class="flex items-center p-3 rounded-xl hover:bg-white/10 transition-all group text-white/80 hover:text-white">
                <i class="fas fa-sign-out-alt mr-4 text-xl opacity-70 group-hover:opacity-100"></i>
                <span class="font-semibold">Logout</span>
            </a>
        </nav>
    </div>
        
    
    <div class="ml-80 flex-1 p-10">
        <!-- Kelola Data Section -->
        <section id="kelola-data" class="block">
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl p-8 shadow-2xl">
                <h1 class="text-4xl font-extrabold mb-8 text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Kelola Data Dosen</h1>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                   <?php
                //    koneksi
                    $conn = mysqli_connect("localhost", "root", "", "uprak2");
                    if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    // menampilkan data yang ada
                    $query = "SELECT * FROM tb_sekolah";
                    $result = mysqli_query($conn, $query);
                    // perulangan query
                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <div class="bg-white rounded-3xl overflow-hidden shadow-xl transform hover:scale-105 hover:rotate-1 transition-all duration-300 border-2 border-transparent hover:border-indigo-300">
                            <div class="relative">
                                <img src="uploads/<?php echo $row['foto_guru']; ?>" class="w-full h-64 object-cover" alt="<?php echo $row['nama_guru']; ?>">
                                    <!-- icon edit data -->
                                <div class="absolute top-4 right-4 space-x-2">
                                    <a href="edit.php?id=<?php echo $row['nisn']; ?>" class="bg-yellow-400 p-3 rounded-full text-white hover:bg-yellow-500 transition-all shadow-md">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <!-- icon menghapus data -->
                                    <a href="#" onclick="confirmDelete('<?php echo $row['nisn']; ?>')" class="bg-red-500 p-3 rounded-full text-white hover:bg-red-600 transition-all shadow-md">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="p-6 space-y-4 bg-gradient-to-br from-white to-gray-50">
                                <h3 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600"><?php echo $row['nama_guru']; ?></h3>
                                <div class="space-y-3 text-gray-700">
                                    <p class="flex items-center"><i class="fas fa-id-card mr-3 text-indigo-500"></i><?php echo $row['nisn']; ?></p>
                                    <p class="flex items-center"><i class="fas fa-calendar-alt mr-3 text-purple-500"></i><?php echo $row['tgl_mulai_tugas']; ?></p>
                                    <p class="flex items-center"><i class="fas fa-graduation-cap mr-3 text-indigo-500"></i><?php echo $row['jenjang_pendidikan']; ?></p>
                                    <p class="flex items-center"><i class="fas fa-book mr-3 text-purple-500"></i><?php echo $row['bidang_keahlian']; ?></p>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>

        <section id="tambahkan-data" class="hidden">
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-700 p-8">
                    <h1 class="text-3xl font-bold text-white flex items-center">
                        <i class="fas fa-user-plus mr-4 text-white/80"></i>Tambahkan Data Dosen
                    </h1>
                </div>
        <!-- Tambahkan Data Section -->

                <form action="process.php" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                <div class="grid grid-cols-1 gap-6">
                    
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-id-card text-blue-500"></i>
                        </div>
                        <input type="text" name="nisn" placeholder="NISN" 
                            class="w-full pl-10 px-4 py-3 border-2 border-blue-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" 
                            required>
                    </div>

                
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-blue-500"></i>
                        </div>
                        <input type="text" name="nama_guru" placeholder="Nama Dosen" 
                            class="w-full pl-10 px-4 py-3 border-2 border-blue-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" 
                            required>
                    </div>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-calendar-alt text-blue-500"></i>
                        </div>
                        <input type="date" name="tgl_mulai_tugas" 
                            class="w-full pl-10 px-4 py-3 border-2 border-blue-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" 
                            required>
                    </div>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-graduation-cap text-blue-500"></i>
                        </div>
                        <select name="jenjang_pendidikan" 
                            class="w-full pl-10 px-4 py-3 border-2 border-blue-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                            <option value="D3">D3</option>
                            <option value="S1">S1</option>
                        </select>
                    </div>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-book text-blue-500"></i>
                        </div>
                        <input type="text" name="bidang_keahlian" placeholder="Bidang Keahlian" 
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
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" name="add" 
                        class="w-full bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700 transition-all flex items-center justify-center space-x-3">
                        <i class="fas fa-plus-circle"></i>
                        <span>Tambahkan Dosen</span>
                    </button>
                </div>
            </form>
            </div>
        </section>
    </div>

    <script>
      const sidebarLinks = document.querySelectorAll('.sidebar-link');
    const sections = {
        'kelola-data': document.getElementById('kelola-data'),
        'tambahkan-data': document.getElementById('tambahkan-data')
    };

    sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            sidebarLinks.forEach(l => l.classList.remove('active', 'bg-blue-700'));
            
            this.classList.add('active', 'bg-blue-700');

            Object.values(sections).forEach(section => {
                section.classList.add('hidden');
            });

            const targetSectionId = this.getAttribute('href').substring(1);
            sections[targetSectionId].classList.remove('hidden');
        });
    });

    function confirmDelete(nisn) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: "Anda yakin ingin menghapus data dosen?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `process.php?delete=${nisn}`;
            }
        });
    }

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