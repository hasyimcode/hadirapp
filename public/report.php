<?php
session_start();
require_once '../src/config.php';
require_once '../src/functions.php';
require_login();

$start = sanitize_input($_GET['start'] ?? '');
$end = sanitize_input($_GET['end'] ?? '');
$where = '';
$params = [];
if ($start && $end) {
    $where = 'WHERE a.timestamp BETWEEN ? AND ?';
    $params = [$start.' 00:00:00', $end.' 23:59:59'];
}
$sql = "SELECT m.title, a.participant, a.timestamp FROM attendance a JOIN meetings m ON a.meeting_id=m.id $where ORDER BY a.timestamp DESC";
$stmt = $mysqli->prepare($sql);
if ($where) $stmt->bind_param('ss', ...$params);
$stmt->execute();
$result = $stmt->get_result();

if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="attendance.csv"');
    echo "Judul,Peserta,Waktu\n";
    while ($row = $result->fetch_assoc()) {
        echo '"'.str_replace('"','""',$row['title']).'","'.str_replace('"','""',$row['participant']).'","'.$row['timestamp'].'"\n';
    }
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Kehadiran</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.5/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="p-6 bg-gray-100">
<h1 class="text-xl mb-4">Laporan Kehadiran</h1>
<form method="get" class="mb-4">
    <label>Mulai: <input type="date" name="start" value="<?php echo htmlspecialchars($start); ?>" class="border p-1 rounded"></label>
    <label>Sampai: <input type="date" name="end" value="<?php echo htmlspecialchars($end); ?>" class="border p-1 rounded"></label>
    <button class="px-2 py-1 bg-blue-500 text-white rounded">Filter</button>
    <?php if ($result->num_rows): ?>
    <a href="?start=<?php echo $start; ?>&end=<?php echo $end; ?>&export=csv" class="ml-2 px-2 py-1 bg-green-500 text-white rounded">Export CSV</a>
    <?php endif; ?>
</form>
<table class="w-full border">
    <thead><tr><th class="border px-2">Judul</th><th class="border px-2">Peserta</th><th class="border px-2">Waktu</th></tr></thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td class="border px-2"><?php echo htmlspecialchars($row['title']); ?></td>
            <td class="border px-2"><?php echo htmlspecialchars($row['participant']); ?></td>
            <td class="border px-2"><?php echo $row['timestamp']; ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
</body>
</html>
