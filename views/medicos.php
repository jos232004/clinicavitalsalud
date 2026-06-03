<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Médicos</h4>
    </div>
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h4 class="card-title">Gestión de Médicos</h4>
            <button class="btn btn-primary btn-round ml-auto" data-toggle="modal" data-target="#addMedicoModal">
                <i class="fa fa-plus"></i> Nuevo Médico
            </button>
        </div>
        <div class="card-body">
            <div class="modal fade" id="addMedicoModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header no-bd">
                            <h5 class="modal-title">Registrar Médico</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <form id="formMedico">
                                <div class="form-group">
                                    <label>DNI</label>
                                    <input type="text" id="dni" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Nombres</label>
                                    <input type="text" id="nombres" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Apellidos</label>
                                    <input type="text" id="apellidos" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Teléfono</label>
                                    <input type="text" id="telefono" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" id="email" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>CMP</label>
                                    <input type="text" id="cmp" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Especialidad</label>
                                    <select id="especialidad_id" class="form-control"></select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer no-bd">
                            <button type="button" id="btnGuardarMedico" class="btn btn-primary">Guardar</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="editMedicoModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header no-bd">
                            <h5 class="modal-title">Editar Médico</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <form id="formEditarMedico">
                                <input type="hidden" id="editId">
                                <div class="form-group">
                                    <label>DNI</label>
                                    <input type="text" id="editDni" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Nombres</label>
                                    <input type="text" id="editNombres" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Apellidos</label>
                                    <input type="text" id="editApellidos" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Teléfono</label>
                                    <input type="text" id="editTelefono" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" id="editEmail" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>CMP</label>
                                    <input type="text" id="editCmp" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Especialidad</label>
                                    <select id="editEspecialidad" class="form-control"></select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer no-bd">
                            <button type="button" id="btnEditarMedico" class="btn btn-primary">Guardar Cambios</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="tablaMedicos" class="display table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>DNI</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>CMP</th>
                            <th>Especialidad</th>
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
        // Función para cargar especialidades en los selects
        function cargarEspecialidades(selectId) {
            $.post("controllers/contEspecialidad.php", {
                proceso: "LISTAR"
            }, function(data) {
                let especialidades = JSON.parse(data);
                let select = $(selectId);
                select.empty();
                especialidades.forEach(function(e) {
                    select.append(`<option value="${e.id}">${e.nombre}</option>`);
                });
            });
        }
        cargarEspecialidades("#especialidad_id");
        cargarEspecialidades("#editEspecialidad");

        // Inicializar DataTable
        let tabla = $('#tablaMedicos').DataTable({
            "ajax": {
                "url": "controllers/contMedico.php",
                "type": "POST",
                "data": {
                    "proceso": "LISTAR"
                },
                "dataSrc": ""
            },
            "columns": [{
                    "data": "dni"
                },
                {
                    "data": "nombres"
                },
                {
                    "data": "apellidos"
                },
                {
                    "data": "telefono"
                },
                {
                    "data": "email"
                },
                {
                    "data": "cmp"
                },
                {
                    "data": "especialidad"
                },
                {
                    // COLUMNA ESTADO: Renderiza un Switch interactivo de Bootstrap
                    "data": "activo",
                    "render": function(data, type, row) {
                        let checked = (parseInt(data) === 1) ? 'checked' : '';
                        return `
                            <label class="selectgroup-item">
                                <input type="checkbox" class="selectgroup-input switch-estado" data-id="${row.id}" ${checked}>
                                <span class="selectgroup-button selectgroup-button-icon ${parseInt(data) === 1 ? 'btn-success text-white' : 'btn-light'}">
                                    ${parseInt(data) === 1 ? '<i class="fa fa-check-circle"></i> Activo' : '<i class="fa fa-ban"></i> Inactivo'}
                                </span>
                            </label>
                        `;
                    }
                },
                {
                    "data": "id",
                    "render": function(data, type, row) {
                        return `
                            <button class="btn btn-primary btn-sm editar" 
                                    data-id="${data}" 
                                    data-dni="${row.dni}" 
                                    data-nombres="${row.nombres}" 
                                    data-apellidos="${row.apellidos}" 
                                    data-telefono="${row.telefono}" 
                                    data-email="${row.email}" 
                                    data-cmp="${row.cmp}" 
                                    data-especialidad="${row.especialidad}">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm eliminar" data-id="${data}">
                                <i class="fa fa-times"></i>
                            </button>`;
                    }
                }
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
            }
        });

        // NUEVO EVENTO: Cambiar estado Activo/Inactivo de forma dinámica
        $('#tablaMedicos').on('change', '.switch-estado', function() {
            let checkbox = $(this);
            let id = checkbox.data('id');
            let nuevoEstado = checkbox.is(':checked') ? 1 : 0;

            $.post("controllers/contMedico.php", {
                proceso: "ACTUALIZAR_ESTADO",
                id: id,
                estado: nuevoEstado
            }, function(resultado) {
                if (resultado == "1") {
                    // Refrescamos solo la fila o la tabla para que cambie el color del botón visualmente
                    tabla.ajax.reload(null, false); // false evita que se reinicie la paginación actual
                } else {
                    alert("Error al actualizar el estado del médico");
                    // Deshacemos el cambio visual del checkbox si falló el backend
                    checkbox.prop('checked', !checkbox.is(':checked'));
                }
            });
        });

        // Guardar médico
        $('#btnGuardarMedico').click(function() {
            let dni = $('#dni').val().trim();
            let nombres = $('#nombres').val().trim();
            let apellidos = $('#apellidos').val().trim();
            let telefono = $('#telefono').val().trim();
            let email = $('#email').val().trim();
            let cmp = $('#cmp').val().trim();
            let especialidad_id = $('#especialidad_id').val();

            $.post("controllers/contMedico.php", {
                proceso: "REGISTRAR",
                dni: dni,
                nombres: nombres,
                apellidos: apellidos,
                telefono: telefono,
                email: email,
                cmp: cmp,
                especialidad_id: especialidad_id
            }, function(resultado) {
                if (resultado == "1") {
                    alert("Médico registrado correctamente");
                    $('#addMedicoModal').modal('hide');
                    $('#formMedico')[0].reset();
                    tabla.ajax.reload();
                } else if (resultado == "DNI duplicado") {
                    alert("El DNI ya está registrado");
                } else if (resultado == "DNI inválido") {
                    alert("Formato de DNI incorrecto");
                } else if (resultado == "Campos vacíos") {
                    alert("Completa todos los campos obligatorios");
                } else {
                    alert("Error al registrar médico");
                }
            });
        });

        // Abrir modal edición
        $('#tablaMedicos').on('click', '.editar', function() {
            $('#editId').val($(this).data('id'));
            $('#editDni').val($(this).data('dni'));
            $('#editNombres').val($(this).data('nombres'));
            $('#editApellidos').val($(this).data('apellidos'));
            $('#editTelefono').val($(this).data('telefono'));
            $('#editEmail').val($(this).data('email'));
            $('#editCmp').val($(this).data('cmp'));
            cargarEspecialidades("#editEspecialidad");
            $('#editMedicoModal').modal('show');
        });

        // Guardar cambios edición
        $('#btnEditarMedico').click(function() {
            let id = $('#editId').val();
            let dni = $('#editDni').val().trim();
            let nombres = $('#editNombres').val().trim();
            let apellidos = $('#editApellidos').val().trim();
            let telefono = $('#editTelefono').val().trim();
            let email = $('#editEmail').val().trim();
            let cmp = $('#editCmp').val().trim();
            let especialidad_id = $('#editEspecialidad').val();

            $.post("controllers/contMedico.php", {
                proceso: "EDITAR",
                id: id,
                dni: dni,
                nombres: nombres,
                apellidos: apellidos,
                telefono: telefono,
                email: email,
                cmp: cmp,
                especialidad_id: especialidad_id
            }, function(resultado) {
                if (resultado == "1") {
                    alert("Médico actualizado correctamente");
                    $('#editMedicoModal').modal('hide');
                    tabla.ajax.reload();
                } else if (resultado == "DNI duplicado") {
                    alert("El DNI ya está registrado por otro médico");
                } else if (resultado == "DNI inválido") {
                    alert("Formato de DNI incorrecto");
                } else if (resultado == "Campos vacíos") {
                    alert("Completa todos los campos obligatorios");
                } else {
                    alert("Error al actualizar médico");
                }
            });
        });

        // Eliminar médico (Mantiene la función por si se remueve por completo)
        $('#tablaMedicos').on('click', '.eliminar', function() {
            let id = $(this).data('id');
            if (confirm("¿Eliminar médico?")) {
                $.post("controllers/contMedico.php", {
                    proceso: "ELIMINAR",
                    id: id
                }, function(resultado) {
                    if (resultado == "1") {
                        alert("Médico eliminado");
                        tabla.ajax.reload();
                    } else {
                        alert("Error al eliminar médico");
                    }
                });
            }
        });
    });
</script>