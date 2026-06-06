<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Pacientes</h4>
    </div>
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h4 class="card-title">Gestión de Pacientes</h4>
            <button class="btn btn-primary btn-round ml-auto" data-toggle="modal" data-target="#addPacienteModal">
                <i class="fa fa-plus"></i> Nuevo Paciente
            </button>
        </div>
        <div class="card-body">
            <div class="modal fade" id="addPacienteModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header no-bd">
                            <h5 class="modal-title">Registrar Paciente</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <form id="formPaciente">
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
                                    <label>Fecha de Nacimiento</label>
                                    <input type="date" id="fecha_nac" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Teléfono</label>
                                    <input type="text" id="telefono" class="form-control">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer no-bd">
                            <button type="button" id="btnGuardarPaciente" class="btn btn-primary">Guardar</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="editPacienteModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header no-bd">
                            <h5 class="modal-title">Editar Paciente</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <form id="formEditarPaciente">
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
                                    <label>Fecha de Nacimiento</label>
                                    <input type="date" id="editFechaNac" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Teléfono</label>
                                    <input type="text" id="editTelefono" class="form-control">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer no-bd">
                            <button type="button" id="btnEditarPaciente" class="btn btn-primary">Guardar Cambios</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="tablaPacientes" class="display table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>DNI</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Edad</th>
                            <th>Teléfono</th>
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
        let tabla = $('#tablaPacientes').DataTable({
            "ajax": {
                "url": "controllers/contPaciente.php",
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
                    "data": "edad",
                    "render": function(data) {
                        return data ? `${data} años` : `<span class="badge badge-count">--</span>`;
                    }
                },
                {
                    "data": "telefono"
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
                          data-fecha_nac="${row.fecha_nac ? row.fecha_nac : ''}" 
                          data-telefono="${row.telefono}">
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

        // Guardar paciente
        $('#btnGuardarPaciente').click(function() {
            let dni = $('#dni').val().trim();
            let nombres = $('#nombres').val().trim();
            let apellidos = $('#apellidos').val().trim();
            let fecha_nac = $('#fecha_nac').val();
            let telefono = $('#telefono').val().trim();

            if (!dni || !nombres || !apellidos) {
                alert("DNI, nombres y apellidos son obligatorios");
                return;
            }
            if (!/^[0-9]{8}$/.test(dni)) {
                alert("El DNI debe tener exactamente 8 dígitos numéricos");
                return;
            }

            $.post("controllers/contPaciente.php", {
                proceso: "REGISTRAR",
                dni: dni,
                nombres: nombres,
                apellidos: apellidos,
                fecha_nac: fecha_nac,
                telefono: telefono
            }, function(resultado) {
                if (resultado == "1") {
                    alert("Paciente registrado correctamente");
                    $('#addPacienteModal').modal('hide');
                    $('#formPaciente')[0].reset();
                    tabla.ajax.reload();
                } else if (resultado == "DNI duplicado") {
                    alert("El DNI ya está registrado");
                } else if (resultado == "DNI inválido") {
                    alert("Formato de DNI incorrecto");
                } else if (resultado == "Campos vacíos") {
                    alert("Completa todos los campos obligatorios");
                } else {
                    alert("Error al registrar paciente");
                }
            });
        });

        // Abrir modal edición
        $('#tablaPacientes').on('click', '.editar', function() {
            $('#editId').val($(this).data('id'));
            $('#editDni').val($(this).data('dni'));
            $('#editNombres').val($(this).data('nombres'));
            $('#editApellidos').val($(this).data('apellidos'));
            $('#editFechaNac').val($(this).data('fecha_nac'));
            $('#editTelefono').val($(this).data('telefono'));
            $('#editPacienteModal').modal('show');
        });

        // Guardar cambios edición
        $('#btnEditarPaciente').click(function() {
            let id = $('#editId').val();
            let dni = $('#editDni').val().trim();
            let nombres = $('#editNombres').val().trim();
            let apellidos = $('#editApellidos').val().trim();
            let fecha_nac = $('#editFechaNac').val();
            let telefono = $('#editTelefono').val().trim();

            if (!dni || !nombres || !apellidos) {
                alert("DNI, nombres y apellidos son obligatorios");
                return;
            }
            if (!/^[0-9]{8}$/.test(dni)) {
                alert("El DNI debe tener exactamente 8 dígitos numéricos");
                return;
            }

            $.post("controllers/contPaciente.php", {
                proceso: "EDITAR",
                id: id,
                dni: dni,
                nombres: nombres,
                apellidos: apellidos,
                fecha_nac: fecha_nac,
                telefono: telefono
            }, function(resultado) {
                if (resultado == "1") {
                    alert("Paciente actualizado correctamente");
                    $('#editPacienteModal').modal('hide');
                    tabla.ajax.reload();
                } else if (resultado == "DNI duplicado") {
                    alert("El DNI ya está registrado por otro paciente");
                } else if (resultado == "DNI inválido") {
                    alert("Formato de DNI incorrecto");
                } else if (resultado == "Campos vacíos") {
                    alert("Completa todos los campos obligatorios");
                } else {
                    alert("Error al actualizar paciente");
                }
            });
        });

        // Eliminar paciente
        $('#tablaPacientes').on('click', '.eliminar', function() {
            let id = $(this).data('id');
            if (confirm("¿Eliminar paciente?")) {
                $.post("controllers/contPaciente.php", {
                    proceso: "ELIMINAR",
                    id: id
                }, function(resultado) {
                    if (resultado == "1") {
                        alert("Paciente eliminado");
                        tabla.ajax.reload();
                    } else {
                        alert("Error al eliminar paciente");
                    }
                });
            }
        });
    });
</script>