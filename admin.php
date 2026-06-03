<?php

session_start();
if (!isset($_SESSION['idusuario'])) {
	header("Location: index.php");
	exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Sistema de citas medicas</title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<link rel="icon" href="imagenes/logoVS.jpg" type="image/x-icon" />

	<!-- Fonts and icons -->
	<script src="assets/js/plugin/webfont/webfont.min.js"></script>
	<script>
		WebFont.load({
			google: {
				"families": ["Lato:300,400,700,900"]
			},
			custom: {
				"families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
				urls: ['assets/css/fonts.min.css']
			},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>

	<!-- CSS Files -->
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/atlantis.min.css">

	<!-- CSS Just for demo purpose, don't include it in your project -->
	<link rel="stylesheet" href="assets/css/demo.css">
</head>

<body>
	<div class="wrapper">
		<div class="main-header">
			<!-- Logo Header -->
			<div class="logo-header" data-background-color="blue">

				<a href="admin.php" class="logo">
					<span style="color: #fff;">Clínica Vital Salud</span>
					<!--<img src="assets/img/logo.svg" alt="navbar brand" class="navbar-brand">-->
				</a>
				<button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon">
						<i class="icon-menu"></i>
					</span>
				</button>
				<button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
				<div class="nav-toggle">
					<button class="btn btn-toggle toggle-sidebar">
						<i class="icon-menu"></i>
					</button>
				</div>
			</div>
			<!-- End Logo Header -->

			<!-- Navbar Header -->
			<nav class="navbar navbar-header navbar-expand-lg" data-background-color="blue2">

				<div class="container-fluid">
					<div class="collapse" id="search-nav">

					</div>
					<ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
						<li class="nav-item toggle-nav-search hidden-caret">
							<a class="nav-link" data-toggle="collapse" href="#search-nav" role="button" aria-expanded="false" aria-controls="search-nav">
								<i class="fa fa-search"></i>
							</a>
						</li>

						<li class="nav-item dropdown hidden-caret">
							<a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
								<div class="avatar-sm">
									<img src="imagenes/icono.png" alt="..." class="avatar-img rounded-circle">
								</div>
							</a>
							<ul class="dropdown-menu dropdown-user animated fadeIn">
								<div class="dropdown-user-scroll scrollbar-outer">
									<li>
										<div class="user-box">
											<div class="avatar-lg"><img src="imagenes/icono.png" alt="image profile" class="avatar-img rounded"></div>
											<div class="u-text">
												<h4><?= $_SESSION['nombre']; ?></h4>
												<p class="text-muted"><?= $_SESSION['rol']; ?></p><a href="#" class="btn btn-xs btn-secondary btn-sm">Ver perfil</a>
											</div>
										</div>
									</li>
									<li>
										<a class="dropdown-item" href="index.php">Salir</a>
									</li>
								</div>
							</ul>
						</li>
					</ul>
				</div>
			</nav>
			<!-- End Navbar -->
		</div>

		<!-- Sidebar -->
		<div class="sidebar sidebar-style-2">
			<div class="sidebar-wrapper scrollbar scrollbar-inner">
				<div class="sidebar-content">
					<div class="user">
						<div class="avatar-sm float-left mr-2">
							<img src="imagenes/icono.png" alt="..." class="avatar-img rounded-circle">
						</div>
						<div class="info">
							<a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
								<span>
									Hola, <?= $_SESSION['nombre']; ?>
									<span class="user-level"><?= $_SESSION['rol']; ?></span>
									<span class="caret"></span>
								</span>
							</a>

							<div class="clearfix"></div>
							<!--
							<div class="collapse in" id="collapseExample">
								<ul class="nav">
									<li>
										<a href="#profile">
											<span class="link-collapse">Mi perfil</span>
										</a>
									</li>
									<li>
										<a href="#edit">
											<span class="link-collapse">Editar perfil</span>
										</a>
									</li>
									<li>
										<a href="#settings">
											<span class="link-collapse">Configuración</span>
										</a>
									</li>
								</ul>
							</div>-->
						</div>
					</div>
					<ul class="nav nav-primary">
						<li class="nav-section">
							<span class="sidebar-mini-icon">
								<i class="fa fa-calendar-alt"></i>
							</span>
							<h4 class="text-section">Calendario</h4>
						</li>

						<li class="nav-item active">
							<a href="#" class="nav-link enlace-navegacion" data-view="dashboard">
								<i class="fas fa-tachometer-alt"></i>
								<p>Dashboard</p>
							</a>
						</li>

						<li class="nav-section">
							<span class="sidebar-mini-icon">
								<i class="fa fa-calendar-check"></i>
							</span>
							<h4 class="text-section">Programar</h4>
						</li>

						<li class="nav-item">
							<a href="#" class="nav-link enlace-navegacion" data-view="citas">
								<i class="fas fa-calendar-plus"></i>
								<p>Citas</p>
							</a>
						</li>

						<li class="nav-section">
							<span class="sidebar-mini-icon">
								<i class="fa fa-cogs"></i>
							</span>
							<h4 class="text-section">Gestionar</h4>
						</li>

						<li class="nav-item">
							<a href="#" class="nav-link enlace-navegacion" data-view="pacientes">
								<i class="fas fa-users"></i>
								<p>Pacientes</p>
							</a>
						</li>

						<li class="nav-item">
							<a href="#" class="nav-link enlace-navegacion" data-view="especialidad">
								<i class="fas fa-stethoscope"></i>
								<p>Especialidad</p>
							</a>
						</li>

						<li class="nav-item">
							<a href="#" class="nav-link enlace-navegacion" data-view="medicos">
								<i class="fas fa-user-md"></i>
								<p>Médicos</p>
							</a>
						</li>

						<li class="nav-section">
							<span class="sidebar-mini-icon">
								<i class="fa fa-sliders-h"></i>
							</span>
							<h4 class="text-section">Configuración</h4>
						</li>

						<li class="nav-item">
							<a href="#" class="nav-link enlace-navegacion" data-view="reporte_citas">
								<i class="fas fa-chart-bar"></i>
								<p>Reportes del mes</p>
							</a>
						</li>

						<li class="nav-item">
							<a href="#" class="nav-link enlace-navegacion" data-view="usuarios">
								<i class="fas fa-user-cog"></i>
								<p>Usuarios</p>
							</a>
						</li>

						<li class="mx-4 mt-2" id="cerrar_sesion">
							<a href="index.php" class="btn btn-primary btn-block" style="background-color: #1e1e1e !important; border-color: #ff6464 !important;">
								<span class="btn-label mr-2">
									<i class="fas fa-sign-out-alt"></i>
								</span>
								Cerrar Sesión
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- End Sidebar -->

		<div class="main-panel">
			<div class="content" id="contenedor-central">
				<?php include("views/dashboard.php");
				?>
			</div>
			<footer class="footer">
				<div class="container-fluid">
					<nav class="pull-left">
						<ul class="nav">
							<li class="nav-item">
								<a class="nav-link" href="#">
									Inicio
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">
									Nosotros
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">
									Viital
								</a>
							</li>
						</ul>
					</nav>
					<div class="copyright ml-auto">
						2026, Vital Salud
					</div>
				</div>
			</footer>
		</div>
	</div>
	<!--   Core JS Files   -->
	<script src="assets/js/core/jquery.3.2.1.min.js"></script>
	<script src="assets/js/core/popper.min.js"></script>
	<script src="assets/js/core/bootstrap.min.js"></script>

	<!-- jQuery UI -->
	<script src="assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
	<script src="assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

	<!-- jQuery Scrollbar -->
	<script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

	<!-- Chart JS -->
	<script src="assets/js/plugin/chart.js/chart.min.js"></script>

	<!-- jQuery Sparkline -->
	<script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

	<!-- Chart Circle -->
	<script src="assets/js/plugin/chart-circle/circles.min.js"></script>

	<!-- Datatables -->
	<script src="assets/js/plugin/datatables/datatables.min.js"></script>


	<!-- jQuery Vector Maps -->
	<script src="assets/js/plugin/jqvmap/jquery.vmap.min.js"></script>
	<script src="assets/js/plugin/jqvmap/maps/jquery.vmap.world.js"></script>

	<!-- Sweet Alert -->
	<script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

	<!-- Atlantis JS -->
	<script src="assets/js/atlantis.min.js"></script>

	<!-- Atlantis DEMO methods, don't include it in your project! -->
	<script src="assets/js/setting-demo.js"></script>
	<script src="assets/js/demo.js"></script>
	<script>
		// Escucha el clic en cualquier enlace del menú
		$('.enlace-navegacion').click(function(e) {
			e.preventDefault(); // Detiene la recarga de página por completo

			let vistaSolicitada = $(this).data('view');


			//Quita la clase 'active' de cualquier otro elemento de la lista del menú
			$('.nav-primary .nav-item').removeClass('active');

			//Busca el contenedor <li> de este botón específico y agrégale la clase 'active'
			$(this).closest('.nav-item').addClass('active');

			// Cargamos el nuevo contenido de forma asíncrona dentro del contenedor central
			$('#contenedor-central').load('cargar_vista.php', {
				view: vistaSolicitada
			}, function() {
				console.log("Vista " + vistaSolicitada + " cargada sin recargar la página.");

			});
		});
	</script>

	<script>

	</script>
</body>

</html>