<?php
session_start();
require_once '../src/config.php';
require_once '../src/functions.php';
require_login();

// Get meetings
$result = $mysqli->query('SELECT id, title, date, time FROM meetings ORDER BY date DESC');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard - HadirApp</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.5/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="p-6 bg-gray-100">
<h1 class="text-xl mb-4">Dashboard</h1>
<a href="logout.php" class="text-blue-600">Logout</a>
<div class="mt-6">
    <h2 class="text-lg mb-2">Daftar Rapat</h2>
    <a href="meeting_add.php" class="bg-green-500 text-white px-2 py-1 rounded">Tambah Rapat</a>
    <table class="w-full mt-2 border">
        <thead><tr><th class="border px-2">Judul</th><th class="border px-2">Tanggal</th><th class="border px-2">Waktu</th></tr></thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td class="border px-2"><a href="attendance.php?meeting_id=<?php echo $row['id']; ?>" class="text-blue-600"><?php echo htmlspecialchars($row['title']); ?></a></td>
                <td class="border px-2"><?php echo $row['date']; ?></td>
                <td class="border px-2"><?php echo $row['time']; ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
