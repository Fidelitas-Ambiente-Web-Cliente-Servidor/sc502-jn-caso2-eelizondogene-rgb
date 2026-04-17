<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Solicitudes pendientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">
    <script src="public/js/jquery-4.0.0.min.js"></script>
    <script src="public/js/solicitud.js"></script>
</head>
<body class="container mt-5">
    <nav class="navbar navbar-expand-lg bg-body-tertiary mb-4 px-3 rounded">
        <div class="d-flex gap-3">
            <a href="index.php?page=talleres" class="btn btn-outline-primary btn-sm">Talleres</a>
            <a href="index.php?page=admin" class="btn btn-outline-secondary btn-sm">Gestionar Solicitudes</a>
        </div>
        <div class="ms-auto d-flex align-items-center gap-2">
            <span class="fw-bold">Admin: <?= htmlspecialchars($_SESSION['nombre'] ?? $_SESSION['user'] ?? 'Administrador') ?></span>
            <button id="btnLogout" class="btn btn-danger btn-sm">Cerrar sesión</button>
        </div>
    </nav>

    <main>
        <h2>Solicitudes pendientes de aprobación</h2>

        <div id="mensaje" class="alert d-none mb-3"></div>

        <div class="table-container">
            <table class="table table-bordered" id="tabla-solicitudes">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Taller</th>
                        <th>Solicitante</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="solicitudes-body">
                    <tr>
                        <td colspan="5" class="text-center">Cargando solicitudes...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>