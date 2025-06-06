<?php
session_start();
require_once '../src/config.php';
require_once '../src/functions.php';
require_login();

$meeting_id = (int)($_GET['meeting_id'] ?? 0);
if ($meeting_id <= 0) {
    header('Location: dashboard.php');
    exit;
}

$meeting = $mysqli->query("SELECT title, date FROM meetings WHERE id = $meeting_id")->fetch_assoc();

// Handle attendance submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $participant = sanitize_input($_POST['participant'] ?? '');
    $stmt = $mysqli->prepare('INSERT INTO attendance (meeting_id, participant) VALUES (?, ?)');
    $stmt->bind_param('is', $meeting_id, $participant);
    $stmt->execute();
}

$result = $mysqli->query("SELECT participant, timestamp FROM attendance WHERE meeting_id = $meeting_id ORDER BY timestamp DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kehadiran</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.5/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="p-6 bg-gray-100">
<h1 class="text-xl mb-4">Kehadiran - <?php echo htmlspecialchars($meeting['title']); ?></h1>
<form method="post" class="bg-white p-4 rounded shadow w-full max-w-md mb-4">
    <div class="mb-4">
        <label class="block mb-1">Nama Peserta</label>
        <input type="text" name="participant" class="w-full p-2 border rounded" required>
    </div>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Catat Kehadiran</button>
</form>
<table class="w-full border">
    <thead><tr><th class="border px-2">Peserta</th><th class="border px-2">Waktu</th></tr></thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr><td class="border px-2"><?php echo htmlspecialchars($row['participant']); ?></td><td class="border px-2"><?php echo $row['timestamp']; ?></td></tr>
    <?php endwhile; ?>
    </tbody>
</table>
</body>
</html>
