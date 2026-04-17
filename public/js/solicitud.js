
$(function () {
    const urlBase = "/sc502-jn-caso2-eelizondogene-rgb/index.php";

    // Cargar solicitudes pendientes al iniciar (solo si estamos en el panel admin)
    if ($("#solicitudes-body").length > 0) {
        cargarSolicitudes();
    }

    function cargarSolicitudes() {
        $.get(urlBase + "?option=solicitudes_json", function (data) {
            console.log(data);
            let tbody = $("#solicitudes-body");
            tbody.html("");

            if (data.length === 0) {
                tbody.html("<tr><td colspan='5' class='text-center'>No hay solicitudes pendientes</td></tr>");
                return;
            }

            data.forEach(function (solicitud) {
                tbody.append(
                    "<tr id='fila-" + solicitud.id + "'>" +
                    "<td>" + solicitud.id + "</td>" +
                    "<td>" + solicitud.taller + "</td>" +
                    "<td>" + solicitud.solicitante + "</td>" +
                    "<td>" + solicitud.fecha_solicitud + "</td>" +
                    "<td>" +
                    "<button class='btn btn-success btn-sm me-2 btnAprobar' data-id='" + solicitud.id + "'>Aprobar</button>" +
                    "<button class='btn btn-danger btn-sm btnRechazar' data-id='" + solicitud.id + "'>Rechazar</button>" +
                    "</td>" +
                    "</tr>"
                );
            });
        });
    }

        // Aprobar solicitud
    $(document).on("click", ".btnAprobar", function () {
        let solicitudId = $(this).data("id");

        $.post(urlBase,
            { id_solicitud: solicitudId, option: "aprobar" },
            function (data) {
                let mensaje = $("#mensaje");
                if (data.success) {
                    mensaje.removeClass("d-none alert-danger").addClass("alert-success").text("Solicitud aprobada correctamente.");
                    $("#fila-" + solicitudId).remove();
                    if ($("#solicitudes-body tr").length === 0) {
                        $("#solicitudes-body").html("<tr><td colspan='5' class='text-center'>No hay solicitudes pendientes</td></tr>");
                    }
                } else {
                    mensaje.removeClass("d-none alert-success").addClass("alert-danger").text(data.error);
                }
                setTimeout(function () { mensaje.addClass("d-none"); }, 4000);
            }, "json"
        );
    });

    // Rechazar solicitud
    $(document).on("click", ".btnRechazar", function () {
        let solicitudId = $(this).data("id");

        $.post(urlBase,
            { id_solicitud: solicitudId, option: "rechazar" },
            function (data) {
                let mensaje = $("#mensaje");
                if (data.success) {
                    mensaje.removeClass("d-none alert-danger").addClass("alert-success").text("Solicitud rechazada.");
                    $("#fila-" + solicitudId).remove();
                    if ($("#solicitudes-body tr").length === 0) {
                        $("#solicitudes-body").html("<tr><td colspan='5' class='text-center'>No hay solicitudes pendientes</td></tr>");
                    }
                } else {
                    mensaje.removeClass("d-none alert-success").addClass("alert-danger").text(data.error);
                }
                setTimeout(function () { mensaje.addClass("d-none"); }, 4000);
            }, "json"
        );
    });

    // Cerrar sesión (para admin)
    $("#btnLogout").on("click", function () {
        $.post(urlBase, { option: "logout" }, function () {
            window.location = "index.php?page=login";
        });
    });

})

