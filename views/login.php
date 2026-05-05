<?php
session_start();
require_once "../config/database.php";

$error = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Email and password are required.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];

                // Important: use both keys for compatibility
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['role'] = $user['role'];

                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Pharma POS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(20, 184, 166, 0.35), transparent 30%),
                radial-gradient(circle at bottom right, rgba(14, 165, 233, 0.30), transparent 35%),
                linear-gradient(135deg, #ecfeff, #f8fafc);
            font-family: "Segoe UI", Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-wrapper {
            width: 100%;
            max-width: 1050px;
            padding: 24px;
        }

        .login-card {
            background: #ffffff;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 25px 70px rgba(15, 23, 42, 0.18);
            border: 1px solid rgba(255,255,255,0.8);
        }

        .login-left {
            background: linear-gradient(135deg, #0f766e, #14b8a6, #0ea5e9);
            color: white;
            padding: 55px 42px;
            min-height: 560px;
            position: relative;
        }

        .login-left::after {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(255,255,255,0.13);
            right: -70px;
            bottom: -70px;
        }

        .brand-icon {
            width: 78px;
            height: 78px;
            border-radius: 24px;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 42px;
            margin-bottom: 24px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.18);
        }

        .login-left h1 {
            font-size: 38px;
            font-weight: 900;
            margin-bottom: 14px;
        }

        .login-left p {
            font-size: 16px;
            opacity: .95;
            line-height: 1.7;
            max-width: 420px;
        }

        .feature-box {
            margin-top: 35px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            font-weight: 700;
        }

        .feature-item i {
            width: 34px;
            height: 34px;
            border-radius: 12px;
            background: rgba(255,255,255,0.18);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-right {
            padding: 55px 45px;
        }

        .login-title {
            font-size: 30px;
            font-weight: 900;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .login-subtitle {
            color: #64748b;
            font-weight: 600;
            margin-bottom: 30px;
        }

        .form-label {
            font-weight: 800;
            color: #334155;
            margin-bottom: 8px;
        }

        .input-group-text {
            background: #ecfeff;
            border: 1px solid #cbd5e1;
            border-right: none;
            color: #0f766e;
            border-radius: 16px 0 0 16px;
            font-size: 18px;
        }

        .form-control {
            border-radius: 0 16px 16px 0;
            padding: 13px 14px;
            border: 1px solid #cbd5e1;
            font-weight: 600;
        }

        .form-control:focus {
            border-color: #14b8a6;
            box-shadow: 0 0 0 .22rem rgba(20, 184, 166, .15);
        }

        .btn-login {
            background: linear-gradient(135deg, #0f766e, #14b8a6);
            border: none;
            border-radius: 16px;
            padding: 13px;
            font-weight: 900;
            color: white;
            box-shadow: 0 12px 25px rgba(20, 184, 166, 0.35);
            transition: .25s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 16px 32px rgba(20, 184, 166, 0.45);
        }

        .alert {
            border-radius: 16px;
            font-weight: 700;
        }

        .password-toggle {
            border-radius: 0 16px 16px 0;
            border-left: none;
            background: #fff;
        }

        .password-input {
            border-radius: 0;
        }

        .footer-note {
            margin-top: 25px;
            text-align: center;
            color: #94a3b8;
            font-size: 13px;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .login-left {
                min-height: auto;
                padding: 35px 28px;
            }

            .login-right {
                padding: 35px 28px;
            }

            .login-left h1 {
                font-size: 30px;
            }
        }
    </style>
</head>

<body>

<div class="login-wrapper">
    <div class="login-card">
        <div class="row g-0">

            <div class="col-md-6">
                <div class="login-left">
                    <div class="brand-icon">
                        <i class="bi bi-capsule-pill"></i>
                    </div>

                    <h1>Pharma POS</h1>

                    <p>
                        Manage medicines, purchases, stock, sales, reports, profit, and pharmacy operations from one clean dashboard.
                    </p>

                    <div class="feature-box">
                        <div class="feature-item">
                            <i class="bi bi-shield-check"></i>
                            Secure role-based access
                        </div>

                        <div class="feature-item">
                            <i class="bi bi-cart-check"></i>
                            Fast POS and billing
                        </div>

                        <div class="feature-item">
                            <i class="bi bi-graph-up-arrow"></i>
                            Sales and profit reports
                        </div>

                        <div class="feature-item">
                            <i class="bi bi-database-check"></i>
                            Backup and stock control
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="login-right">

                    <div class="login-title">Welcome Back</div>
                    <div class="login-subtitle">Login to continue to your pharmacy dashboard</div>

                    <?php if ($error != ""): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <?= htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" autocomplete="off">

                        <div class="mb-4">
                            <label class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" name="email" class="form-control"
                                       placeholder="Enter your email"
                                       value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                       required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>

                                <input type="password" name="password" id="password"
                                       class="form-control password-input"
                                       placeholder="Enter your password"
                                       required>

                                <button type="button" class="btn btn-outline-secondary password-toggle" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" name="login" class="btn btn-login w-100">
                            <i class="bi bi-box-arrow-in-right"></i>
                            Login
                        </button>

                    </form>

                    <div class="footer-note">
                        © <?= date('Y'); ?> Pharma POS. All rights reserved.
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.getElementById("togglePassword").addEventListener("click", function () {
    const password = document.getElementById("password");
    const icon = this.querySelector("i");

    if (password.type === "password") {
        password.type = "text";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");
    } else {
        password.type = "password";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");
    }
});
</script>

</body>
</html>