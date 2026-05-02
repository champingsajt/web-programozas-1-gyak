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
    <title>Dashboard - Procrastinator Exciter</title>
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
                        <a class="nav-link active" href="index.php">Számlálók</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="calendar.php">Naptár</a>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="main-title">A te határidőid</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#counterModal" onclick="openAddModal()">
                <i class="bi bi-plus-circle"></i> Új Számláló
            </button>
        </div>

        <div class="timer-grid" id="countersContainer">
            <!-- Counters will be loaded here via AJAX -->
        </div>
    </div>

    <!-- Modal for Create/Update -->
    <div class="modal fade" id="counterModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Új Számláló</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="counterForm">
                        <input type="hidden" id="counterId">
                        <div class="mb-3">
                            <label class="form-label">Cím</label>
                            <input type="text" class="form-control" id="title" required placeholder="Pl. Webes Projekt Beadandó">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Határidő</label>
                            <input type="datetime-local" class="form-control" id="deadline" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Státusz üzenet (opcionális)</label>
                            <input type="text" class="form-control" id="status_message" placeholder="Pl. Ketyeg az óra!">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégsem</button>
                    <button type="button" class="btn btn-primary" onclick="saveCounter()">Mentés</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Törlés megerősítése</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Biztosan törölni szeretnéd ezt a számlálót?
                    <input type="hidden" id="deleteCounterId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégsem</button>
                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">Törlés</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
