<div class="page-inner">
	<div class="page-header">
		<h4 class="page-title">Usuarios</h4>
	</div>
	<div class="card">
		<div class="card-header d-flex align-items-center">
			<h4 class="card-title">Gestión de Usuarios</h4>
			<button class="btn btn-primary btn-round ml-auto" data-toggle="modal" data-target="#addUsuarioModal">
				<i class="fa fa-plus"></i> Nuevo Usuario
			</button>
		</div>
		<div class="card-body">
			<div class="modal fade" id="addUsuarioModal" tabindex="-1" role="dialog">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header no-bd">
							<h5 class="modal-title">Registrar Usuario</h5>
							<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
						</div>
						<div class="modal-body">
							<form id="formUsuario">
								<div class="form-group">
									<label>Nombre</label>
									<input type="text" id="nombre" class="form-control" required>
								</div>
								<div class="form-group">
									<label>Apellido</label>
									<input type="text" id="apellido" class="form-control" required>
								</div>
								<div class="form-group">
									<label>Email</label>
									<input type="email" id="email" class="form-control" required>
								</div>
								<div class="form-group">
									<label>Clave</label>
									<input type="password" id="clave" class="form-control" required>
								</div>
								<div class="form-group">
									<label>Rol</label>
									<select id="rol" class="form-control">
										<option value="admin">Admin</option>
										<option value="admision">Admisión</option>
										<option value="medico">Médico</option>
									</select>
								</div>
							</form>
						</div>
						<div class="modal-footer no-bd">
							<button type="button" id="btnGuardarUsuario" class="btn btn-primary">Guardar</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
						</div>
					</div>
				</div>
			</div>

			<div class="modal fade" id="editUsuarioModal" tabindex="-1" role="dialog">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header no-bd">
							<h5 class="modal-title">Editar Usuario</h5>
							<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
						</div>
						<div class="modal-body">
							<form id="formEditarUsuario">
								<input type="hidden" id="editId">
								<div class="form-group">
									<label>Nombre</label>
									<input type="text" id="editNombre" class="form-control" required>
								</div>
								<div class="form-group">
									<label>Apellido</label>
									<input type="text" id="editApellido" class="form-control" required>
								</div>
								<div class="form-group">
									<label>Email</label>
									<input type="email" id="editEmail" class="form-control" required>
								</div>
								<div class="form-group">
									<label>Rol</label>
									<select id="editRol" class="form-control">
										<option value="admin">Admin</option>
										<option value="admision">Admisión</option>
										<option value="medico">Médico</option>
									</select>
								</div>
								<div class="form-group">
									<label>Estado</label>
									<select id="editActivo" class="form-control">
										<option value="1">Activo</option>
										<option value="0">Inactivo</option>
									</select>
								</div>
							</form>
						</div>
						<div class="modal-footer no-bd">
							<button type="button" id="btnEditarUsuario" class="btn btn-primary">Guardar Cambios</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
						</div>
					</div>
				</div>
			</div>

			<div class="table-responsive">
				<table id="tablaUsuarios" class="display table table-striped table-hover">
					<thead>
						<tr>
							<th>Nombre</th>
							<th>Apellido</th>
							<th>Email</th>
							<th>Rol</th>
							<th>Estado</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		// Inicializar DataTable con AJAX para listar usuarios
		let tabla = $('#tablaUsuarios').DataTable({
			"ajax": {
				"url": "controllers/contUsuario.php",//Demos permitir registrar un usuario con el rol de medico 
				"type": "POST",
				"data": {
					"proceso": "LISTAR"
				},
				"dataSrc": ""
			},
			"columns": [{
					"data": "nombre"
				},
				{
					"data": "apellido"
				},
				{
					"data": "email"
				},
				{
					"data": "rol",
					"render": function(data) {
						// Formateo visual estético para la columna Rol
						if (data === 'admin') return '<span class="badge badge-secondary">Admin</span>';
						if (data === 'admision') return '<span class="badge badge-info">Admisión</span>';
						if (data === 'medico') return '<span class="badge badge-primary">Médico</span>';
						return data;
					}
				},
				{
					"data": "activo",
					"render": function(data) {
						// Badges oficiales de Atlantis para activos/inactivos
						return data == 1 ? '<span class="badge badge-success">Activo</span>' : '<span class="badge badge-danger">Inactivo</span>';
					}
				},
				{
					"data": "id",
					"render": function(data, type, row) {
						return `<button class="btn btn-primary btn-sm editar mr-1" data-id="${data}" data-nombre="${row.nombre}" data-apellido="${row.apellido}" data-email="${row.email}" 
                                            data-rol="${row.rol}" 
                                            data-activo="${row.activo}">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm eliminar" data-id="${data}"><i class="fa fa-times"></i></button>`;
					}
				},
			],
			"language": {
				"url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
			}
		});

		// Agregar usuario
		$('#btnGuardarUsuario').click(function() {
			let nombre = $('#nombre').val().trim();
			let apellido = $('#apellido').val().trim();
			let email = $('#email').val().trim();
			let clave = $('#clave').val().trim();
			let rol = $('#rol').val();

			if (!nombre || !apellido || !email || !clave) {
				alert("Todos los campos son obligatorios");
				return;
			}
			if (!email.includes('@')) {
				alert("Ingresa un email válido");
				return;
			}
			if (clave.length < 6) {
				alert("La clave debe tener al menos 6 caracteres");
				return;
			}

			$.post("controllers/contUsuario.php", {
				proceso: "REGISTRAR",
				nombre: nombre,
				apellido: apellido,
				email: email,
				clave: clave,
				rol: rol
			}, function(resultado) {
				if (resultado == "1") {
					alert("Usuario registrado correctamente");
					$('#addUsuarioModal').modal('hide');
					$('#formUsuario')[0].reset();
					tabla.ajax.reload();
				} else if (resultado == "Email duplicado") {
					alert("El email que usted ingresó ya está registrado");
				} else if (resultado == "Email inválido") {
					alert("Formato de email incorrecto");
				} else if (resultado == "Campos vacíos") {
					alert("Completa todos los campos");
				} else {
					alert("Error al registrar usuario");
				}
			});
		});

		// Abrir modal con datos a editar
		$('#tablaUsuarios').on('click', '.editar', function() {
			$('#editId').val($(this).data('id'));
			$('#editNombre').val($(this).data('nombre'));
			$('#editApellido').val($(this).data('apellido'));
			$('#editEmail').val($(this).data('email'));
			$('#editRol').val($(this).data('rol'));
			$('#editActivo').val($(this).data('activo'));
			$('#editUsuarioModal').modal('show');
		});

		// Guardar cambios
		$('#btnEditarUsuario').click(function() {
			let id = $('#editId').val();
			let nombre = $('#editNombre').val().trim();
			let apellido = $('#editApellido').val().trim();
			let email = $('#editEmail').val().trim();
			let rol = $('#editRol').val();
			let activo = $('#editActivo').val();

			if (!nombre || !apellido || !email) {
				alert("Nombre, apellido y email son obligatorios");
				return;
			}
			if (!email.includes('@')) {
				alert("Ingresa un email válido");
				return;
			}

			$.post("controllers/contUsuario.php", {
				proceso: "EDITAR",
				id: id,
				nombre: nombre,
				apellido: apellido,
				email: email,
				rol: rol,
				activo: activo
			}, function(resultado) {
				if (resultado == "1") {
					alert("Usuario actualizado correctamente");
					$('#editUsuarioModal').modal('hide');
					tabla.ajax.reload();
				} else if (resultado == "Email duplicado") {
					alert("El email ya está registrado por otro usuario");
				} else if (resultado == "Email inválido") {
					alert("Formato de email incorrecto");
				} else if (resultado == "Campos vacíos") {
					alert("Completa todos los campos");
				} else {
					alert("Error al actualizar usuario");
				}
			});
		});

		// Eliminar usuario
		$('#tablaUsuarios').on('click', '.eliminar', function() {
			let id = $(this).data('id');
			if (confirm("¿Eliminar usuario?")) {
				$.post("controllers/contUsuario.php", {
					proceso: "ELIMINAR",
					id: id
				}, function(resultado) {
					if (resultado == "1") {
						alert("Usuario eliminado");
						tabla.ajax.reload();
					} else {
						alert("Error al eliminar usuario");
					}
				});
			}
		});
	});
</script>