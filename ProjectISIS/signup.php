<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #121212;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        header {
            text-align: center;
            margin-bottom: 20px;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
        .signup-box {
            background: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .signup-box h2 {
            margin-bottom: 20px;
        }
        .textbox {
            margin-bottom: 20px;
        }
        .textbox input {
            width: calc(100% - 20px);
            padding: 10px;
            background: #2b2b2b;
            border: 1px solid #444;
            border-radius: 5px;
            color: #fff;
        }
        .btn {
            background: #0C434B;
            color: #fff;
            padding: 10px 30px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.3s;
            width: 100%;
            box-sizing: border-box;
        }
        .btn:hover {
            background: #127784;
        }
    </style>
</head>
<body>
    <header>
        <h1>Daftar Akun Untuk Login</h1>
    </header>
    <div class="container">
        <div class="signup-box">
            <h2>Daftar Akun</h2>
            <form id="signupForm" class="signup-form" action="signup.php" method="POST">
                <div class="textbox">
                    <input type="text" id="username" name="username" placeholder="Username" required>
                </div>
                <div class="textbox">
                    <input type="email" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="textbox">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <div class="textbox">
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
                </div>
                <input type="submit" class="btn" value="Daftar">
            </form>
        </div>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];

        if ($password !== $confirmPassword) {
            echo "<script>alert('Konfirmasi password tidak cocok. Silakan coba lagi.');</script>";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $conn = new mysqli('localhost', 'root', '', 'tugas');

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashedPassword);

            if ($stmt->execute()) {
                echo "<script>alert('Pendaftaran berhasil. Silakan login.'); window.location.href = 'loginpage.php';</script>";
            } else {
                echo "<script>alert('Pendaftaran gagal. Silakan coba lagi.');</script>";
            }

            $stmt->close();
            $conn->close();
        }
    }
    ?>
</body>
</html>
