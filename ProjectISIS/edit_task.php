<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: loginpage.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = $_POST['taskId'];
    // Fetch the current task details and populate the form for editing.
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateTask'])) {
    $taskId = $_POST['taskId'];
    $taskInput = $_POST['taskInput'];
    $deadlineInput = $_POST['deadlineInput'];

    $conn = new mysqli('localhost', 'root', '', 'tugas');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("UPDATE tasks SET task = ?, deadline = ? WHERE id = ?");
    $stmt->bind_param("ssi", $taskInput, $deadlineInput, $taskId);

    if ($stmt->execute()) {
        echo "<script>alert('Tugas berhasil diperbarui'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui tugas. Silakan coba lagi.'); window.location.href='index.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
