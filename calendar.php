<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="hu" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Naptár - Procrastinator Exciter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="bi bi-fire text-danger"></i> Procrastinator Exciter 3000
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Számlálók</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="calendar.php">Naptár</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <select class="form-select form-select-sm me-3" id="themeSelector">
                        <option value="dark">Sötét Téma</option>
                        <option value="light">Világos Téma</option>
                        <option value="nje">NJE Téma</option>
                    </select>
                    <span class="navbar-text me-3">
                        Szia, <?= htmlspecialchars($_SESSION['username']) ?>!
                    </span>
                    <a href="logout.php" class="btn btn-outline-danger btn-sm">Kijelentkezés</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <h1 class="main-title mb-4">Naptár Nézet</h1>
        <?php
        // Get current month and year
        $month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
        $year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

        // Adjust if out of bounds
        if ($month < 1) { $month = 12; $year--; }
        if ($month > 12) { $month = 1; $year++; }

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $firstDayOfMonth = date('N', strtotime("$year-$month-01")); // 1=Monday, 7=Sunday

        // Fetch counters for the month
        $startDate = "$year-$month-01";
        $endDate = "$year-$month-$daysInMonth";
        $stmt = $pdo->prepare("SELECT id, title, deadline, status_message FROM counters WHERE user_id = ? AND DATE(deadline) BETWEEN ? AND ? ORDER BY deadline");
        $stmt->execute([$_SESSION['user_id'], $startDate, $endDate]);
        $counters = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Group counters by date
        $countersByDate = [];
        foreach ($counters as $counter) {
            $date = date('Y-m-d', strtotime($counter['deadline']));
            $countersByDate[$date][] = $counter;
        }

        // Month name
        $monthName = date('F Y', strtotime("$year-$month-01"));
        ?>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="?month=<?= $month - 1 ?>&year=<?= $year ?>" class="btn btn-outline-secondary">&larr; Előző</a>
            <h2><?= $monthName ?></h2>
            <a href="?month=<?= $month + 1 ?>&year=<?= $year ?>" class="btn btn-outline-secondary">Következő &rarr;</a>
        </div>
        <table class="table table-bordered calendar-table">
            <thead>
                <tr>
                    <th>Hétfő</th>
                    <th>Kedd</th>
                    <th>Szerda</th>
                    <th>Csütörtök</th>
                    <th>Péntek</th>
                    <th>Szombat</th>
                    <th>Vasárnap</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $day = 1;
                $week = 0;
                while ($day <= $daysInMonth) {
                    echo '<tr>';
                    for ($i = 1; $i <= 7; $i++) {
                        if ($week == 0 && $i < $firstDayOfMonth) {
                            echo '<td class="bg-light"></td>';
                        } elseif ($day > $daysInMonth) {
                            echo '<td class="bg-light"></td>';
                        } else {
                            $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
                            $isToday = $dateStr == date('Y-m-d');
                            $class = $isToday ? 'bg-warning' : '';
                            echo "<td class='$class' style='height: 120px; vertical-align: top;'>";
                            echo "<strong>$day</strong><br>";
                            if (isset($countersByDate[$dateStr])) {
                                foreach ($countersByDate[$dateStr] as $counter) {
                                    echo "<div class='small text-truncate' title='" . htmlspecialchars($counter['title']) . "'>";
                                    echo htmlspecialchars($counter['title']);
                                    if ($counter['status_message']) {
                                        echo " - " . htmlspecialchars($counter['status_message']);
                                    }
                                    echo "</div>";
                                }
                            }
                            echo '</td>';
                            $day++;
                        }
                    }
                    echo '</tr>';
                    $week++;
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>