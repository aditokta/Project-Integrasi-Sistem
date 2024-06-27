<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal dan Pengingat Tugas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #121212;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0;
            padding: 20px;
        }
        header {
            text-align: center;
            margin-bottom: 20px;
        }
        .container {
            background: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 900px;
        }
        form {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }
        label, input {
            margin-bottom: 10px;
        }
        input[type="text"], input[type="datetime-local"] {
            padding: 10px;
            background: #2b2b2b;
            border: 1px solid #444;
            border-radius: 5px;
            color: #fff;
            position: relative;
        }
        input[type="datetime-local"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }
        input[type="submit"] {
            background: #0C434B;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.3s;
        }
        input[type="submit"]:hover {
            background: #127784;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #444;
            text-align: left;
        }
        th {
            background: #0C434B;
        }
        tr:nth-child(even) {
            background: #2b2b2b;
        }
        tr:nth-child(odd) {
            background: #1e1e1e;
        }
        .delete-btn, .edit-btn {
            background: #0C434B;
            color: #fff;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .delete-btn:hover, .edit-btn:hover {
            background: #127784;
        }
    </style>
</head>
<body>
    <header>
        <h1>Jadwal dan Pengingat Tugas</h1>
    </header>
    <div class="container">
        <h2>Tambah Tugas Baru</h2>
        <form id="taskForm" action="index.php" method="POST">
            <label for="taskInput">Tugas:</label>
            <input type="text" id="taskInput" name="taskInput" placeholder="Masukkan tugas..." required>
            <label for="deadlineInput">Deadline:</label>
            <input type="datetime-local" id="deadlineInput" name="deadlineInput" required>
            <input type="submit" value="Tambah Tugas">
        </form>
        <h2>Daftar Tugas</h2>
        <table>
            <thead>
                <tr>
                    <th>Daftar Tugas</th>
                    <th>Deadline</th>
                    <th>Sisa Waktu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="taskList">
                <!-- Tugas akan ditampilkan di sini -->
                <?php
                session_start();

                if (!isset($_SESSION['user_id'])) {
                    header("Location: loginpage.php");
                    exit();
                }

                $conn = new mysqli('localhost', 'root', '', 'tugas');

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $userId = $_SESSION['user_id'];

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $taskInput = $_POST['taskInput'];
                    $deadlineInput = $_POST['deadlineInput'];

                    $stmt = $conn->prepare("INSERT INTO tasks (user_id, task, deadline) VALUES (?, ?, ?)");
                    $stmt->bind_param("iss", $userId, $taskInput, $deadlineInput);

                    if ($stmt->execute()) {
                        echo "<script>alert('Tugas berhasil ditambahkan');</script>";
                    } else {
                        echo "<script>alert('Gagal menambahkan tugas. Silakan coba lagi.');</script>";
                    }

                    $stmt->close();
                }

                $result = $conn->query("SELECT id, task, deadline FROM tasks WHERE user_id = $userId");

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['task']}</td>";
                    echo "<td>{$row['deadline']}</td>";
                    echo "<td><script>
                        var deadline = new Date('{$row['deadline']}');
                        function calculateTimeRemaining() {
                            var now = new Date();
                            var timeRemaining = deadline - now;
                            if (timeRemaining <= 0) {
                                return 'Deadline telah berlalu';
                            } else {
                                var hours = Math.floor(timeRemaining / (1000 * 60 * 60));
                                var minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
                                var seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);
                                return hours + ' jam ' + minutes + ' menit ' + seconds + ' detik';
                            }
                        }
                        setInterval(function() {
                            document.getElementById('timeLeft_{$row['id']}').innerText = calculateTimeRemaining();
                        }, 1000);
                        </script>
                        <span id='timeLeft_{$row['id']}'>".(new DateTime($row['deadline']) > new DateTime() ? '' : 'Deadline telah berlalu')."</span></td>";
                    echo "<td>
                        <form method='POST' action='delete_task.php' style='display:inline'>
                            <input type='hidden' name='taskId' value='{$row['id']}'>
                            <input type='submit' value='Hapus' class='delete-btn'>
                        </form>
                        <form method='POST' action='edit_task.php' style='display:inline'>
                            <input type='hidden' name='taskId' value='{$row['id']}'>
                            <input type='submit' value='Edit' class='edit-btn'>
                        </form>
                        </td>";
                    echo "</tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
