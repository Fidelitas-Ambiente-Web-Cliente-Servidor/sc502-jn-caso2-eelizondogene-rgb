

$(function () {
    const urlBase = "/sc502-jn-caso2-eelizondogene-rgb/index.php";

    // Cargar talleres disponibles al iniciar
    cargarTalleres();

    function cargarTalleres() {
        $.get(urlBase + "?option=talleres_json", function (data, status) {
            let listaTalleres = $("#listaTalleres");
            listaTalleres.html("");

            if (data.length === 0) {
                listaTalleres.html("<tr><td colspan='4' class='text-center'>No hay talleres disponibles en este momento</td></tr>");
                return;
            }

            data.forEach(function (taller) {
                listaTalleres.append(
                    "<tr>" +
                    "<td>" + taller.nombre + "</td>" +
                    "<td>" + taller.descripcion + "</td>" +
                    "<td>" + taller.cupo_disponible + "</td>" +
                    "<td><button class='btn btn-success btn-sm btnSolicitar' data-id='" + taller.id + "' data-nombre='" + taller.nombre + "'>Solicitar inscripción</button></td>" +
                    "</tr>"
                );
            });
        });
    }

    // Solicitar inscripción a un taller
    $(document).on("click", ".btnSolicitar", function () {
        let tallerId = $(this).data("id");
        let tallerNombre = $(this).data("nombre");

        if (!confirm("¿Deseas solicitar inscripción al taller: " + tallerNombre + "?")) {
            return;
        }

        $.post(urlBase,
            {
                taller_id: tallerId,
                option: "solicitar"
            },
            function (data, status) {
                data = JSON.parse(data);
                let mensaje = $("#mensaje");
                if (data.success) {
                    mensaje.removeClass("d-none alert-danger").addClass("alert-success").text(data.message);
                    cargarTalleres(); // Refresca la tabla sin recargar la página
                } else {
                    mensaje.removeClass("d-none alert-success").addClass("alert-danger").text(data.error);
                }
                // Ocultar mensaje después de 4 segundos
                setTimeout(function () {
                    mensaje.addClass("d-none");
                }, 4000);
            }
        );
    });

    // Cerrar sesión
    $("#btnLogout").on("click", function () {
        $.post(urlBase, { option: "logout" }, function () {
            window.location = "index.php?page=login";
        });
    });

})

