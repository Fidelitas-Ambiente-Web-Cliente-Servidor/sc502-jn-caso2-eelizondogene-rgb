<!DOCTYPE html>
<html>

<head>

    <title>Listado Talleres</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">
    <script src="public/js/jquery-4.0.0.min.js"></script>
    <script src="public/js/taller.js"></script>
    <script src="public/js/solicitud.js"></script>
</head>

<body class="container mt-5">

    <nav class="navbar navbar-expand-lg bg-body-tertiary mb-4 px-3 rounded">
        <div class="d-flex gap-3">
            <a href="index.php?page=talleres" class="btn btn-outline-primary btn-sm">Talleres</a>
            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                <a href="index.php?page=admin" class="btn btn-outline-secondary btn-sm">Gestionar Solicitudes</a>
            <?php endif; ?>
        </div>
        <div class="ms-auto d-flex align-items-center gap-2">
            <span class="fw-bold">Bienvenido, <?= htmlspecialchars($_SESSION['nombre'] ?? $_SESSION['user'] ?? 'Usuario') ?></span>
            <button id="btnLogout" class="btn btn-danger btn-sm">Cerrar sesión</button>
        </div>
    </nav>

    <main>
        <h3>Talleres Disponibles</h3>

        <div id="mensaje" class="alert d-none mb-3"></div>

        <table class="table table-bordered" id="tablaTalleres">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Cupos disponibles</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody id="listaTalleres">
                <tr>
                    <td colspan="4" class="text-center">Cargando talleres...</td>
                </tr>
            </tbody>
        </table>
    </main>

</body>

</html>