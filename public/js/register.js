$(function () {
    let formRegister = $("#formRegister");
    const urlBase = "/sc502-jn-caso2-eelizondogene-rgb/index.php";

    formRegister.on("submit", function (event) {
        event.preventDefault();
        let username = $("#username");
        let password = $("#password");

        if (username.val() === "" || password.val() === "") {
            alert("Debe completar todos los campos");
        } else {
            $.ajax({
                url: urlBase,
                type: 'POST',
                data: $(this).serialize() + '&option=register',
                dataType: 'json',
                success: function (response) {
                    if (response.response === '00') {
                        window.location.href = 'index.php?page=talleres';
                    } else {
                        $('#mensaje').text(response.message).css('color', 'red').show();
                    }
                },
                error: function () {
                    $('#mensaje').text('Error de conexión').css('color', 'red').show();
                }
            });

        }


    })


})
