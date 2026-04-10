<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Talleres Disponibles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">
    <script src="public/js/jquery-4.0.0.min.js"></script>
    <script src="public/js/taller.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary px-4">
        <span class="navbar-brand fw-bold">🎓 Talleres Académicos</span>
        <div class="ms-auto d-flex align-items-center gap-3">
            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                <a href="index.php?page=admin" class="btn btn-light btn-sm">Gestionar Solicitudes</a>
            <?php endif; ?>
            <span class="text-white">👤 <?= htmlspecialchars($_SESSION['nombre'] ?? $_SESSION['user'] ?? 'Usuario') ?></span>
            <button id="btnLogout" class="btn btn-outline-light btn-sm">Cerrar sesión</button>
        </div>
    </nav>

    <div class="container mt-4">
        <div id="mensaje" class="alert" style="display:none;"></div>

        <h3 class="mb-3">Talleres Disponibles</h3>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>Taller</th>
                        <th>Descripción</th>
                        <th>Cupos (disponible/máximo)</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="talleres-body">
                    <tr>
                        <td colspan="4" class="text-center">Cargando talleres...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>