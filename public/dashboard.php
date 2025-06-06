<?php
session_start();
require_once '../src/config.php';
require_once '../src/functions.php';
require_login();

// Get meetings with search filter
$search = sanitize_input($_GET['q'] ?? '');
$sql = 'SELECT id, title, date, time, location, pin FROM meetings';
if ($search) {
    $stmt = $mysqli->prepare($sql . ' WHERE title LIKE ? ORDER BY date DESC');
    $like = "%$search%";
    $stmt->bind_param('s', $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $mysqli->query($sql . ' ORDER BY date DESC');
}
$chartLabels = [];
$chartData = [];
$countRes = $mysqli->query('SELECT m.title, COUNT(a.id) as total FROM meetings m LEFT JOIN attendance a ON m.id=a.meeting_id GROUP BY m.id');
while ($row = $countRes->fetch_assoc()) {
    $chartLabels[] = $row['title'];
    $chartData[] = $row['total'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard - HadirApp</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.5/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="p-6 bg-gray-100">
<h1 class="text-xl mb-4">Dashboard - <?php echo htmlspecialchars($_SESSION['level']); ?></h1>
<a href="logout.php" class="text-blue-600">Logout</a>
<div class="mt-6">
    <h2 class="text-lg mb-2">Daftar Rapat</h2>
    <form class="mb-2" method="get">
        <input type="text" name="q" value="<?php echo htmlspecialchars($search); ?>" placeholder="Cari judul" class="border p-1 rounded">
        <button class="px-2 py-1 bg-blue-500 text-white rounded">Cari</button>
        <a href="meeting_add.php" class="ml-2 bg-green-500 text-white px-2 py-1 rounded">Tambah Rapat</a>
    </form>
    <table class="w-full mt-2 border">
        <thead><tr><th class="border px-2">Judul</th><th class="border px-2">Tanggal</th><th class="border px-2">Waktu</th><th class="border px-2">Lokasi</th><th class="border px-2">PIN</th></tr></thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td class="border px-2"><a href="attendance.php?meeting_id=<?php echo $row['id']; ?>" class="text-blue-600"><?php echo htmlspecialchars($row['title']); ?></a></td>
                <td class="border px-2"><?php echo $row['date']; ?></td>
                <td class="border px-2"><?php echo $row['time']; ?></td>
                <td class="border px-2"><?php echo htmlspecialchars($row['location']); ?></td>
                <td class="border px-2"><?php echo $row['pin']; ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<div class="mt-6">
    <canvas id="chart" class="w-full h-64"></canvas>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const labels = <?php echo json_encode($chartLabels); ?>;
const data = <?php echo json_encode($chartData); ?>;
new Chart(document.getElementById('chart'), {
  type: 'bar',
  data: {labels: labels, datasets:[{label:'Kehadiran', data:data, backgroundColor:'rgba(75,192,192,0.4)'}]}
});
</script>
</body>
</html>
