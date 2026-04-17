$(function () {
    let formLogin = $("#formLogin");
    const urlBase = "/sc502-jn-caso2-eelizondogene-rgb/index.php";

    formLogin.on("submit", function (event) {
        event.preventDefault();
        let username = $("#username");
        let password = $("#password");

        if (username.val() === "" || password.val() === "") {
            alert("Debe completar todos los campos");
        } else {
            $.post(urlBase,
                {
                    username: username.val(),
                    password: password.val(),
                    option: "login"
                },
                function (data, status) {
                    data = JSON.parse(data);
                    console.log(data);
                    if(data.response == "00"){
                        window.location = data.rol == 'admin' ? "index.php?page=admin" : "index.php?page=talleres";
                    } else {
                        alert(data.message)
                    }
                });

        }
    })


})
