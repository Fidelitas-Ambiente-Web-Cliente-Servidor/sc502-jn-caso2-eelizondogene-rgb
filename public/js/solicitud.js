$(function () {
    const urlBase = "index.php";

    // Cargar solicitudes pendientes al iniciar
    cargarSolicitudes();

    function cargarSolicitudes() {
        $.get(urlBase + "?option=solicitudes_json", function (data) {
            data = typeof data === "string" ? JSON.parse(data) : data;
            const tbody = $("#solicitudes-body");
            tbody.html("");

            if (!Array.isArray(data) || data.length === 0) {
                tbody.append('<tr><td colspan="6" class="text-center">No hay solicitudes pendientes.</td></tr>');
                return;
            }

            data.forEach(function (s) {
                const fecha = new Date(s.fecha_solicitud).toLocaleDateString('es-CR');
                tbody.append(`
                    <tr id="fila-${s.id}">
                        <td>${s.id}</td>
                        <td>${s.taller_nombre}</td>
                        <td>${s.usuario_nombre}</td>
                        <td>${s.usuario_nombre}</td>
                        <td>${fecha}</td>
                        <td>
                            <button class="btn btn-sm btn-success btn-aprobar" data-id="${s.id}">Aprobar</button>
                            <button class="btn btn-sm btn-danger btn-rechazar" data-id="${s.id}">Rechazar</button>
                        </td>
                    </tr>
                `);
            });
        });
    }

    // Aprobar
    $(document).on("click", ".btn-aprobar", function () {
        const id = $(this).data("id");
        if (!confirm("¿Aprobar esta solicitud?")) return;

        $.post(urlBase, { option: "aprobar", id_solicitud: id }, function (data) {
            data = typeof data === "string" ? JSON.parse(data) : data;
            mostrarMensaje(data.success ? data.message : data.error, data.success ? "success" : "danger");
            if (data.success) {
                $("#fila-" + id).fadeOut(400, function () { $(this).remove(); });
            }
        });
    });

    // Rechazar
    $(document).on("click", ".btn-rechazar", function () {
        const id = $(this).data("id");
        if (!confirm("¿Rechazar esta solicitud?")) return;

        $.post(urlBase, { option: "rechazar", id_solicitud: id }, function (data) {
            data = typeof data === "string" ? JSON.parse(data) : data;
            mostrarMensaje(data.success ? data.message : data.error, data.success ? "success" : "danger");
            if (data.success) {
                $("#fila-" + id).fadeOut(400, function () { $(this).remove(); });
            }
        });
    });

    // Logout
    $("#btnLogout").on("click", function () {
        $.post(urlBase, { option: "logout" }, function () {
            window.location.href = "index.php?page=login";
        });
    });

    function mostrarMensaje(texto, tipo) {
        const div = $("#mensaje");
        div.removeClass().addClass("alert alert-" + tipo).text(texto).show();
        setTimeout(function () { div.fadeOut(); }, 4000);
    }
});
