<?php
// koneksi
$conn = mysqli_connect("localhost", "root", "", "uprak2");

// Function untuk mengatasi file upload
function handleFileUpload($file) {
    // target foto ke folder upload
    $target_dir = "uploads/";
     // Menentukan path lengkap untuk file yang akan disimpan
    $target_file = $target_dir . basename($file["name"]);
    $allowed_extensions = array("jpg", "jpeg", "png");
        // Mengambil ekstensi file dari nama file dan mengubahnya menjadi huruf kecil
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

     // Mengecek apakah direktori 'uploads' sudah ada. Jika tidak, maka buat direktori baru
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

   
    if (!in_array($file_extension, $allowed_extensions)) {
        throw new Exception("Invalid file type. Only JPG, JPEG, and PNG are allowed.");
    }

  
    if (!move_uploaded_file($file["tmp_name"], $target_file)) {
        throw new Exception("Failed to upload file.");
    }
     // Mengembalikan nama file yang telah di-upload
    return basename($file["name"]);
}
 // pengecekan kondisi apakah tombol add sudah diklik
if (isset($_POST['add'])) {
    try {
        // mengambil data dari form
        $nisn = $_POST['nisn'];
        $nama_guru = $_POST['nama_guru'];
        $tgl_mulai_tugas = $_POST['tgl_mulai_tugas'];
        $jenjang_pendidikan = $_POST['jenjang_pendidikan'];
        $bidang_keahlian = $_POST['bidang_keahlian'];
        
           // Memanggil fungsi handleFileUpload untuk menangani upload foto dan mendapatkan nama file foto
        $foto_guru = handleFileUpload($_FILES['foto_guru']);
        // logika menambahkan data db dml
        $query = "INSERT INTO tb_sekolah (nisn, nama_guru, tgl_mulai_tugas, jenjang_pendidikan, bidang_keahlian, foto_guru) 
                  VALUES (?, ?, ?, ?, ?, ?)";
            // Menghubungkan data yang akan dimasukkan ke dalam query
            // "ssssss" berarti semua data yang dimasukkan bertipe string (nisn, nama_guru, dll.)
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssssss", $nisn, $nama_guru, $tgl_mulai_tugas, $jenjang_pendidikan, $bidang_keahlian, $foto_guru);
        // Menjalankan query untuk memasukkan data ke dalam database
        mysqli_stmt_execute($stmt);
        
        header("Location: dashboard-admin.php?success=Teacher added successfully!");
    } catch (Exception $e) {
        header("Location: dashboard-admin.php?error=" . urlencode($e->getMessage()));
    }
}
// update
if (isset($_POST['update'])) {
    try {
        // mengambil data dari form
        $nisn = $_POST['nisn'];
        $nama_guru = $_POST['nama_guru'];
        $tgl_mulai_tugas = $_POST['tgl_mulai_tugas'];
        $jenjang_pendidikan = $_POST['jenjang_pendidikan'];
        $bidang_keahlian = $_POST['bidang_keahlian'];
        // logic database dml update
        $query = "UPDATE tb_sekolah SET 
                  nama_guru=?, 
                  tgl_mulai_tugas=?, 
                  jenjang_pendidikan=?, 
                  bidang_keahlian=?";
        $params = array($nama_guru, $tgl_mulai_tugas, $jenjang_pendidikan, $bidang_keahlian);
        
        // mengatasi upload foto by dokumentasi php
        if ($_FILES['foto_guru']['name']) {
            $foto_guru = handleFileUpload($_FILES['foto_guru']);
            $query .= ", foto_guru=?";
            $params[] = $foto_guru;
        }
        
        $query .= " WHERE nisn=?";
        $params[] = $nisn;
        // Menyiapkan query untuk eksekusi
        $stmt = mysqli_prepare($conn, $query);
        // Membuat string tipe parameter berdasarkan jumlah parameter yang ada
        $types = str_repeat('s', count($params));
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        // Menjalankan query untuk memperbarui data
        mysqli_stmt_execute($stmt);
        
        header("Location: dashboard-admin.php?success=Teacher updated successfully!");
    } catch (Exception $e) {
        header("Location: edit.php?id=" . $nisn . "&error=" . urlencode($e->getMessage()));
    }
}
// hapus data
if (isset($_GET['delete'])) {
    try {
        $nisn = $_GET['delete'];
        
       
        $query = "SELECT foto_guru FROM tb_sekolah WHERE nisn=?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $nisn);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        if ($row && $row['foto_guru']) {
            $file_path = "uploads/" . $row['foto_guru'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        
        // logika database dml delete
        $query = "DELETE FROM tb_sekolah WHERE nisn=?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $nisn);
        mysqli_stmt_execute($stmt);
        
        header("Location: dashboard-admin.php?success=Teacher deleted successfully!");
    } catch (Exception $e) {
        header("Location: dashboard-admin.php?error=" . urlencode($e->getMessage()));
    }
}
?>