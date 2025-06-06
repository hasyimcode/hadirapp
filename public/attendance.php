<?php
session_start();
require_once '../src/config.php';
require_once '../src/functions.php';
// peserta tidak perlu login

$meeting_id = (int)($_GET['meeting_id'] ?? 0);
if ($meeting_id <= 0) {
    header('Location: dashboard.php');
    exit;
}

$meeting = $mysqli->query("SELECT title, date, pin FROM meetings WHERE id = $meeting_id")->fetch_assoc();
$error = '';

// Handle attendance submission with PIN verification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $participant = sanitize_input($_POST['participant'] ?? '');
    $pin = (int)($_POST['pin'] ?? 0);
    if ($pin === (int)$meeting['pin']) {
        $signature = $_POST['signature'] ?? null;
        $stmt = $mysqli->prepare('INSERT INTO attendance (meeting_id, participant, signature) VALUES (?, ?, ?)');
        $stmt->bind_param('iss', $meeting_id, $participant, $signature);
        $stmt->execute();
    } else {
        $error = 'PIN salah';
    }
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
<?php if ($error): ?>
<div class="text-red-600 mb-2"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post" class="bg-white p-4 rounded shadow w-full max-w-md mb-4">
    <div class="mb-4">
        <label class="block mb-1">Nama Peserta</label>
        <input type="text" name="participant" class="w-full p-2 border rounded" required>
    </div>
    <div class="mb-4">
        <label class="block mb-1">PIN Kehadiran</label>
        <input type="number" name="pin" class="w-full p-2 border rounded" required>
    </div>
    <div class="mb-4">
        <label class="block mb-1">Tanda Tangan</label>
        <canvas id="sig" class="border w-full h-24"></canvas>
        <input type="hidden" name="signature" id="signature">
        <button type="button" id="clear" class="text-sm text-blue-600 mt-1">Bersihkan</button>
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
<script>
const canvas = document.getElementById('sig');
if (canvas) {
  const ctx = canvas.getContext('2d');
  let drawing = false;
  canvas.addEventListener('mousedown', e => {drawing = true; ctx.moveTo(e.offsetX, e.offsetY);});
  canvas.addEventListener('mousemove', e => { if (drawing) { ctx.lineTo(e.offsetX, e.offsetY); ctx.stroke(); }});
  canvas.addEventListener('mouseup', () => {drawing = false; document.getElementById('signature').value = canvas.toDataURL();});
  document.getElementById('clear').onclick = () => { ctx.clearRect(0,0,canvas.width,canvas.height); document.getElementById('signature').value=''; };
}
</script>
</body>
</html>
