<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="shortcut icon" href="imagenes/logoVS.jpg">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Clínica Vital Salud</title>
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="dist/css/login.css">


</head>

<body class="hold-transition">
    <div class="container-fluid login-container d-flex align-items-center">
        <div class="row w-100">
            <!-- Login Form -->
            <div class="col-md-4 d-flex justify-content-center align-items-center">
                <div class="card w-75">
                    <div class="card-body login-card-body">
                        <div class="text-center mb-3">
                            <img src="imagenes/logoVS.jpg" class="img-fluid" style="max-width: 150px;">
                            <p class="login-box-msg mt-2">Acceso al Sistema</p>
                        </div>
                        <div class="welcome-text text-center">¡Bienvenido! Ingresa tus credenciales para continuar</div>
                        <form action="" method="post">
                            <div class="input-group mb-3">
                                <input type="email" class="form-control" placeholder="admin@vitalsalud.pe" name="email" id="email" required>
                                <div class="input-group-append">
                                    <div class="input-group-text"><span class="fas fa-user"></span></div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" placeholder="Clave" name="clave" id="clave" required>
                                <div class="input-group-append">
                                    <div class="input-group-text"><span class="fas fa-lock"></span></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-success btn-block d-flex align-items-center justify-content-center" onclick="IngresarSistema()" id="btnLogin">
                                        Ingresar
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" id="spinnerLogin"></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Image Section -->
            <div class="col-md-8 login-image d-none d-md-block"></div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>

    <script>
        
        function IngresarSistema() {
            let email = $('#email').val().trim().toLowerCase();
            let clave = $('#clave').val();
            let btn = $('#btnLogin');
            let spinner = $('#spinnerLogin');

            if (!email || !clave) {
                alert('Completa el email y la contraseña');
                return;
            }

            if (!email.includes('@')) {
                alert('Ingresa un email válido, por ejemplo admin@vitalsalud.pe');
                return;
            }

            if (email !== 'admin@vitalsalud.pe') {
                let confirmacion = confirm('El email ingresado es diferente a admin@vitalsalud.pe. ¿Deseas continuar?');
                if (!confirmacion) {
                    return;
                }
            }

            btn.prop('disabled', true);
            spinner.show();

            $.ajax({
                method: "POST",
                url: "/clinicavitalsalud/controllers/contUsuario.php",
                data: {
                    'proceso': 'LOGIN',
                    'email': email,
                    'clave': clave
                }
            }).done(function(resultado) {
                console.log('Login response:', resultado);
                if (resultado == "1") {
                    window.open("admin.php", "_self");
                } else {
                    alert("Email o clave incorrecta");
                    btn.prop('disabled', false);
                    spinner.hide();
                }
            }).fail(function() {
                alert("Error de conexión. Intenta nuevamente.");
                btn.prop('disabled', false);
                spinner.hide();
            });
        }
    </script>

</body>

</html>