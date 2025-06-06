<?php
session_start();
require_once '../src/config.php';
require_once '../src/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize_input($_POST['title'] ?? '');
    $date = sanitize_input($_POST['date'] ?? '');
    $time = sanitize_input($_POST['time'] ?? '');
    $location = sanitize_input($_POST['location'] ?? '');
    $agenda = sanitize_input($_POST['agenda'] ?? '');
    $pin = (int)($_POST['pin'] ?? rand(1000,9999));
    $stmt = $mysqli->prepare('INSERT INTO meetings (title, date, time, location, agenda, pin) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('sssssi', $title, $date, $time, $location, $agenda, $pin);
    $stmt->execute();
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tambah Rapat</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.5/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="p-6 bg-gray-100">
<h1 class="text-xl mb-4">Tambah Rapat</h1>
<form method="post" class="bg-white p-4 rounded shadow w-full max-w-md">
    <div class="mb-4">
        <label class="block mb-1">Judul</label>
        <input type="text" name="title" class="w-full p-2 border rounded" required>
    </div>
    <div class="mb-4">
        <label class="block mb-1">Tanggal</label>
        <input type="date" name="date" class="w-full p-2 border rounded" required>
    </div>
    <div class="mb-4">
        <label class="block mb-1">Waktu</label>
        <input type="time" name="time" class="w-full p-2 border rounded" required>
    </div>
    <div class="mb-4">
        <label class="block mb-1">Lokasi</label>
        <input type="text" name="location" class="w-full p-2 border rounded">
    </div>
    <div class="mb-4">
        <label class="block mb-1">Agenda</label>
        <textarea name="agenda" class="w-full p-2 border rounded"></textarea>
    </div>
    <div class="mb-4">
        <label class="block mb-1">PIN Kehadiran</label>
        <input type="number" name="pin" class="w-full p-2 border rounded" value="<?php echo rand(1000,9999); ?>">
    </div>
    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
</form>
</body>
</html>
