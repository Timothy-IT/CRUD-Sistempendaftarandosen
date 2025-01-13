<?php
$conn = new mysqli("localhost", "root", "", "uprak2");
$login_success = false;
// login proses
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
// pengkondisian jika username hrus admin dan pw admin
    if ($username == 'admin' && $password == 'admin') {
        $login_success = true;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 min-h-screen flex items-center justify-center">
    <div class="bg-white/20 backdrop-blur-lg shadow-2xl rounded-2xl p-10 w-[450px] border border-white/30">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-white drop-shadow-lg">Login Admin</h2>
            <p class="text-white/80 mt-2">Login ini khusus admin, dosen dilarang masuk!</p>
        </div>

        <form method="POST" class="space-y-6">
            <?php if(isset($error)): ?>
                <div class="bg-red-500/50 text-white px-4 py-3 rounded-lg text-center" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div>
                <label class="block text-white mb-2">Username</label>
                <input type="text" name="username" required 
                    class="w-full px-4 py-3 bg-white/20 backdrop-blur-lg border border-white/30 rounded-lg text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-pink-400">
            </div>

            <div>
                <label class="block text-white mb-2">Password</label>
                <input type="password" name="password" required 
                    class="w-full px-4 py-3 bg-white/20 backdrop-blur-lg border border-white/30 rounded-lg text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-pink-400">
            </div>

            <button type="submit" 
                class="w-full py-3 px-4 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg hover:from-pink-600 hover:to-purple-700 transition duration-300 transform hover:scale-105">
                Login
            </button>

            <div class="text-center mt-4">
                <a href="index.html" class="text-white/80 hover:text-white underline">
                    Kembali ke Beranda
                </a>
            </div>
        </form>
    </div>

    <?php if ($login_success): ?>
        <script>
            Swal.fire({
                title: 'Login Berhasil!',
                text: 'Anda akan diarahkan ke halaman admin.',
                icon: 'success',
                confirmButtonColor: '#8b5cf6',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'dashboard-admin.php';
                }
            });
        </script>
    <?php endif; ?>
</body>
</html>