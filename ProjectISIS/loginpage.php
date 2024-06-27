<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
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
            background: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            max-width: 900px;
            width: 100%;
        }
        .quote {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: left;
            margin-right: 20px;
        }
        .login-box {
            text-align: center;
            flex: 1;
        }
        .login-box img {
            width: 100px;
            height: 80px;
            border-radius: 50%;
        }
        .login-box h2 {
            margin-bottom: 20px;
        }
        .textbox {
            margin-bottom: 20px;
        }
        .textbox input {
            width: calc(70% - 20px);
            padding: 10px;
            background: #2b2b2b;
            border: 1px solid #444;
            border-radius: 5px;
            color: #2b2b2b;
        }
        .btn, .btn-signup {
            background: #0C434B;
            color: #fff;
            padding: 10px 30px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.3s;
            width: 70%;
            box-sizing: border-box;
        }
        .btn:hover, .btn-signup:hover {
            background: #127784;
        }
        .btn-signup {
            background: transparent;
            border: 2px solid #1A716E;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Selamat Datang di Website Pengingat Tugas</h1>
    </header>
    <div class="container">
        <div class="quote">
            <h2>Tetap Produktif, Capai Impianmu.</h2>
            <p>Website pengingat tugas yang membantu Anda mengelola waktu dan mencapai setiap target dengan lebih efisien.</p>
        </div>
        <div class="login-box">
            <img src="profile.png" alt="Profile Picture">
            <h2>Login User</h2>
            <form id="loginForm" class="login-form" action="loginpage.php" method="POST">
                <div class="textbox">
                    <input type="text" id="username" name="username" placeholder="Username" required>
                </div>
                <div class="textbox">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <input type="submit" class="btn" value="Login">
            </form>
            <button id="signupBtn" class="btn-signup" onclick="window.location.href='signup.php'">Daftar Akun</button>
        </div>
    </div>

    <?php
    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $conn = new mysqli('localhost', 'root', '', 'tugas');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($userId, $hashedPassword);
        $stmt->fetch();

        if ($stmt->num_rows > 0 && password_verify($password, $hashedPassword)) {
            $_SESSION['user_id'] = $userId;
            header("Location: index.php");
            exit();
        } else {
            echo "<script>alert('Username atau password salah. Silakan coba lagi.');</script>";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>
