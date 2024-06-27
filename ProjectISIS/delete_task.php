<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: loginpage.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = $_POST['taskId'];

    $conn = new mysqli('localhost', 'root', '', 'tugas');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $taskId);

    if ($stmt->execute()) {
        echo "<script>alert('Tugas berhasil dihapus'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus tugas. Silakan coba lagi.'); window.location.href='index.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
