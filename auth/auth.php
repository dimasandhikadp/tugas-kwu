<?php
  session_start();
  require_once '../config/koneksi.php';
  
  /** @var mysqli $conn */

  $error = '';
  $success = '';
  $activeForm = 'login';

  if (isset($_POST['register_submit'])) {

    $activeForm = 'register';

    $username = trim($_POST['name']);
    $login = trim($_POST['login']);
    $password = $_POST['password'];

    $email = null;
    $no_hp = null;

    if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
        $email = $login;
    } else {
        $no_hp = preg_replace('/[^0-9]/', '', $login);
    }

    $cek = mysqli_prepare(
        $conn,
        "SELECT id FROM users
         WHERE username = ?
         OR email = ?
         OR no_hp = ?"
    );

    mysqli_stmt_bind_param(
        $cek,
        "sss",
        $username,
        $email,
        $no_hp
    );

    mysqli_stmt_execute($cek);

    $result = mysqli_stmt_get_result($cek);

    if (mysqli_num_rows($result) > 0) {

        $error = "Username, email, atau nomor HP sudah digunakan.";

    } else {

        $hash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO users
            (username,email,no_hp,password)
            VALUES (?,?,?,?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssss",
            $username,
            $email,
            $no_hp,
            $hash
        );

        if (mysqli_stmt_execute($stmt)) {

            $success = "Pendaftaran berhasil. Silakan login.";

        } else {

            $error = "Gagal melakukan pendaftaran.";

        }
    }
}

$loginClass = $activeForm === 'login' ? 'block' : 'hidden';
$registerClass = $activeForm === 'register' ? 'block' : 'hidden';

if (isset($_POST['login_submit'])) {

    $login = trim($_POST['login']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare(
        $conn,
        "SELECT *
         FROM users
         WHERE username = ?
         OR email = ?
         OR no_hp = ?
         LIMIT 1"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sss",
        $login,
        $login,
        $login
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $user = mysqli_fetch_assoc($result);

    if (
        $user &&
        password_verify(
            $password,
            $user['password']
        )
    ) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header('Location: ../index.php');
        exit;

    } else {

        $error = "Username, email, nomor HP, atau password salah.";

    }
}
?>

<!DOCTYPE html>
<html lang="id">
  <?php include '../includes/head.php'; ?>

  <body class="bg-slate-50 min-h-screen flex flex-col">
    <!-- HEADER / NAVBAR -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
      <div
        class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between"
      >
        <!-- Logo -->
        <div class="flex items-center space-x-1">
          <img
            src="../assets/img/logo.png"
            alt="Logo Segar"
            class="w-15 h-10 object-contain"
            style="transform: scaleX(1.5)"
          />
          <div>
            <span class="text-xl font-bold text-blue-600 block leading-none">
              Se<span class="text-blue-950">gar</span>
            </span>
            <span class="text-xs text-gray-400 leading-none">
              Hasil Laut Segar, Dari Laut Ke Meja Anda
            </span>
          </div>
        </div>

        <!-- Tombol kembali -->
        <a
          href="../index.php"
          class="text-sm font-medium text-gray-600 hover:text-blue-600 transition"
        >
          Kembali ke Beranda
        </a>
      </div>
    </header>

    <!-- MAIN CONTENT CONTAINER -->
    <main
      class="flex-grow flex items-center justify-center p-6 bg-gradient-to-b from-blue-50/50 to-white"
    >

      <!-- 1. FORM LOGIN -->
      <div
        id="loginCard"
        class="bg-white rounded-2xl shadow-xl max-w-md w-full overflow-hidden border border-slate-100 transition-all duration-300 <?= $loginClass ?>"
      >
        <!-- Form Header -->
        <div class="p-8 text-center pb-3">
          <h2 class="text-2xl font-bold text-slate-800 mb-1">
            Selamat Datang Kembali
          </h2>
          <p class="text-sm text-slate-500">
            Silakan masuk untuk melanjutkan belanja hasil laut Anda
          </p>
          <?php if($error): ?>
            <div class="mb-4 p-3 rounded-xl bg-red-50 text-red-600 text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- Form Body -->
        <div class="px-8 pb-8 pt-2">
          <form action="#" method="POST" class="space-y-4">
            <!-- Input Email -->
            <div>
              <label
                for="loginIdentifier"
                class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1"
              >
                No. Handphone/Email/Username
              </label>

              <input
                type="text"
                id="loginIdentifier"
                name="login"
                required
                placeholder="No. Hp/Email/Username"
                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#0066cc] focus:ring-2 focus:ring-blue-100 transition-all"
              />
            </div>

            <!-- Input Password -->
            <div>
              <div class="flex justify-between items-center mb-1">
                <label
                  for="loginPassword"
                  class="block text-xs font-bold uppercase tracking-wider text-slate-600"
                  >Kata Sandi</label
                >
              </div>
              <input
                type="password"
                id="loginPassword"
                name="password"
                required
                placeholder="Password"
                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#0066cc] focus:ring-2 focus:ring-blue-100 transition-all"
              />
            </div>

            <!-- Tombol Login -->
            <button
              name="login_submit"
              value="1"
              type="submit"
              class="w-full bg-[#0066cc] hover:bg-[#0052a3] text-white font-semibold py-3 rounded-xl transition-all shadow-lg shadow-blue-100 active:scale-[0.99] transform mt-2 cursor-pointer"
            >
              Masuk Sekarang
            </button>
          </form>

          <!-- Pembatas Garis -->
          <div class="relative flex py-6 items-center">
            <div class="flex-grow border-t border-slate-100"></div>
            <span
              class="flex-shrink mx-4 text-slate-400 text-xs uppercase tracking-wider"
              >Atau</span
            >
            <div class="flex-grow border-t border-slate-100"></div>
          </div>

          <!-- Link ke Halaman Daftar -->
          <p class="text-center text-sm text-slate-600">
            Belum punya akun di Segar?
            <button
              onclick="showRegister()"
              class="font-bold text-[#0066cc] hover:underline cursor-pointer focus:outline-none"
            >
              Daftar Sekarang
            </button>
          </p>
        </div>
      </div>

      <!-- 2. FORM DAFTAR -->
      <div
        id="registerCard"
        class="bg-white rounded-2xl shadow-xl max-w-md w-full overflow-hidden border border-slate-100 transition-all duration-300 <?= $registerClass ?>"
      >
        <!-- Form Header -->
        <div class="p-8 text-center pb-3">
          <h2 class="text-2xl font-bold text-slate-800 mb-1">Mulai Belanja</h2>
          <p class="text-sm text-slate-500">
            Buat akun Segar Anda untuk kemudahan memesan hasil laut terbaik
          </p>
          <?php if($success): ?>
            <div class="mt-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl px-4 py-3">
                <?= htmlspecialchars($success) ?>
            </div>
          <?php endif; ?>
          <?php if($error && isset($_POST['register_submit'])): ?>
            <div class="mt-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3">
                <?= htmlspecialchars($error) ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- Form Body -->
        <div class="px-8 pb-8 pt-2">
          <form action="#" method="POST" class="space-y-4">
            <!-- Input Nama Lengkap -->
            <div>
              <label
                for="regName"
                class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1"
                >Username</label
              >
              <input
                type="text"
                id="regName"
                name="name"
                required
                placeholder="Masukkan Username"
                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#0066cc] focus:ring-2 focus:ring-blue-100 transition-all"
              />
            </div>

            <!-- Input Email -->
            <div>
              <label
              for="loginIdentifier"
              class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1"
              >
                No. Handphone atau Email
              </label>

              <input
                type="text"
                id="loginIdentifier"
                name="login"
                required
                placeholder="No. Hp/Email"
                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#0066cc] focus:ring-2 focus:ring-blue-100 transition-all"
              />
            </div>

            <!-- Input Kata Sandi -->
            <div>
              <label
                for="regPassword"
                class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1"
                >Kata Sandi Baru</label
              >
              <input
                type="password"
                id="regPassword"
                name="password"
                required
                placeholder="Minimal 8 karakter"
                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#0066cc] focus:ring-2 focus:ring-blue-100 transition-all"
              />
            </div>

            <!-- Persetujuan Syarat Ketentuan -->
            <div class="flex items-start">
              <input
                type="checkbox"
                id="terms"
                name="terms"
                required
                class="h-4 w-4 mt-0.5 rounded border-slate-300 text-[#0066cc] focus:ring-[#0066cc]"
              />
              <label
                for="terms"
                class="ml-2 text-xs text-slate-600 select-none leading-normal"
              >
                Saya menyetujui
                <a href="#" class="text-[#0066cc] font-semibold hover:underline"
                  >Syarat & Ketentuan</a
                >
                serta
                <a href="#" class="text-[#0066cc] font-semibold hover:underline"
                  >Kebijakan Privasi</a
                >.
              </label>
            </div>

            <!-- Tombol Daftar -->
            <button
              name="register_submit"
              value="1"
              type="submit"
              class="w-full bg-[#0066cc] hover:bg-[#0052a3] text-white font-semibold py-3 rounded-xl transition-all shadow-lg shadow-blue-100 active:scale-[0.99] transform mt-2 cursor-pointer"
            >
              Daftar Akun Baru
            </button>
          </form>

          <!-- Pembatas Garis -->
          <div class="relative flex py-5 items-center">
            <div class="flex-grow border-t border-slate-100"></div>
            <span
              class="flex-shrink mx-4 text-slate-400 text-xs uppercase tracking-wider"
              >Atau</span
            >
            <div class="flex-grow border-t border-slate-100"></div>
          </div>

          <!-- Link kembali ke Halaman Login -->
          <p class="text-center text-sm text-slate-600">
            Sudah memiliki akun?
            <button
              onclick="showLogin()"
              class="font-bold text-[#0066cc] hover:underline cursor-pointer focus:outline-none"
            >
              Masuk di sini
            </button>
          </p>
        </div>
      </div>
    </main>

    <footer class="bg-blue-950 text-white mt-20">
      <!-- Bottom Footer -->
      <div class="border-t border-blue-900">
        <div
          class="max-w-7xl mx-auto px-4 py-5 flex flex-col md:flex-row justify-between items-center gap-3"
        >
          <p class="text-sm text-gray-400">
            © 2026 Segar. Semua hak dilindungi.
          </p>

          <div class="flex items-center gap-4 text-sm text-gray-400">
            <a href="#" class="hover:text-blue-400">Syarat & Ketentuan</a>
            <a href="#" class="hover:text-blue-400">Privasi</a>
          </div>
        </div>
      </div>
    </footer>

    <!-- INTERAKSI JAVASCRIPT GABUNGAN -->
    <script>
      const loginCard = document.getElementById("loginCard");
      const registerCard = document.getElementById("registerCard");

      function showRegister() {
        loginCard.classList.replace("block", "hidden");
        registerCard.classList.replace("hidden", "block");
        document.title = "Daftar Akun - Segar | Hasil Laut Segar";
      }

      function showLogin() {
        registerCard.classList.replace("block", "hidden");
        loginCard.classList.replace("hidden", "block");
        document.title = "Login - Segar | Hasil Laut Segar";
      }
    </script>
  </body>
</html>
