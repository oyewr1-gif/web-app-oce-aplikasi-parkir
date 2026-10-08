<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= APP_NAME ?></title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        .login-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            overflow: hidden;
        }
        .login-header {
            background: #2563eb;
            color: white;
            padding: 30px 24px;
            text-align: center;
        }
        .login-body {
            padding: 32px 24px;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 16px;
        }
        .btn-primary {
            background: #2563eb;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
        }
        .btn-primary:hover {
            background: #1d4ed8;
        }
        .demo-btn {
            font-size: 0.8rem;
            border-radius: 6px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <i class="fa-solid fa-square-parking fa-3x mb-2"></i>
        <h4 class="fw-bold mb-1">Aplikasi Parkir MVC</h4>
        <p class="mb-0 text-white-50 small">Silakan login untuk masuk ke dalam sistem</p>
    </div>
    <div class="login-body">
        <?php Session::flash(); ?>

        <form action="<?= BASE_URL ?>/auth/processLogin" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label fw-medium text-secondary">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-user text-muted"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0" id="username" name="username" placeholder="Masukkan username" required autofocus>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="password" class="form-label fw-medium text-secondary">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                    <input type="password" class="form-control border-start-0 ps-0" id="password" name="password" placeholder="Masukkan password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">
                <i class="fa-solid fa-right-to-bracket me-2"></i> Masuk Sekarang
            </button>
        </form>

        <div class="bg-light p-3 rounded mt-3">
            <div class="small fw-bold text-muted mb-2 text-center"><i class="fa-solid fa-key me-1"></i> Akun Demo Uji Coba:</div>
            <div class="d-flex justify-content-between gap-2">
                <button type="button" class="btn btn-outline-primary btn-sm flex-fill demo-btn" onclick="fillDemo('admin', '123456')">
                    <i class="fa-solid fa-user-shield me-1"></i> Admin
                </button>
                <button type="button" class="btn btn-outline-success btn-sm flex-fill demo-btn" onclick="fillDemo('kasir', '123456')">
                    <i class="fa-solid fa-cash-register me-1"></i> Petugas Kasir
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function fillDemo(username, password) {
        document.getElementById('username').value = username;
        document.getElementById('password').value = password;
    }
</script>

</body>
</html>
