$(function () {
    const urlBase = "index.php";

    // Cargar talleres disponibles al iniciar
    cargarTalleres();

    function cargarTalleres() {
        $.get(urlBase + "?option=talleres_json", function (data) {
            data = typeof data === "string" ? JSON.parse(data) : data;
            const tbody = $("#talleres-body");
            tbody.html("");

            if (data.length === 0) {
                tbody.append('<tr><td colspan="5" class="text-center">No hay talleres disponibles en este momento.</td></tr>');
                return;
            }

            data.forEach(function (taller) {
                tbody.append(`
                    <tr>
                        <td>${taller.nombre}</td>
                        <td>${taller.descripcion}</td>
                        <td>${taller.cupo_disponible} / ${taller.cupo_maximo}</td>
                        <td>
                            <button class="btn btn-sm btn-success btn-solicitar"
                                data-id="${taller.id}"
                                data-nombre="${taller.nombre}">
                                Solicitar inscripción
                            </button>
                        </td>
                    </tr>
                `);
            });
        });
    }

    // Delegar el evento de click en botones de solicitar
    $(document).on("click", ".btn-solicitar", function () {
        const tallerId = $(this).data("id");
        const tallerNombre = $(this).data("nombre");

        if (!confirm(`¿Deseas solicitar inscripción al taller "${tallerNombre}"?`)) return;

        $.post(urlBase, {
            option: "solicitar",
            taller_id: tallerId
        }, function (data) {
            data = typeof data === "string" ? JSON.parse(data) : data;
            mostrarMensaje(data.success ? data.message : data.error, data.success ? "success" : "danger");
            if (data.success) {
                cargarTalleres();
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