<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Especialidades</h4>
    </div>
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h4 class="card-title">Gestión de Especialidades</h4>
            <button class="btn btn-primary btn-round ml-auto" data-toggle="modal" data-target="#addEspecialidadModal">
                <i class="fa fa-plus"></i> Nueva Especialidad
            </button>
        </div>
        <div class="card-body">
            <!-- Modal Registro -->
            <div class="modal fade" id="addEspecialidadModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header no-bd">
                            <h5 class="modal-title">Registrar Especialidad</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <form id="formEspecialidad">
                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input type="text" id="nombre" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Descripción</label>
                                    <textarea id="descripcion" class="form-control"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer no-bd">
                            <button type="button" id="btnGuardarEspecialidad" class="btn btn-primary">Guardar</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Edición -->
            <div class="modal fade" id="editEspecialidadModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header no-bd">
                            <h5 class="modal-title">Editar Especialidad</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <form id="formEditarEspecialidad">
                                <input type="hidden" id="editId">
                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input type="text" id="editNombre" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Descripción</label>
                                    <textarea id="editDescripcion" class="form-control"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer no-bd">
                            <button type="button" id="btnEditarEspecialidad" class="btn btn-primary">Guardar Cambios</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla -->
            <div class="table-responsive">
                <table id="tablaEspecialidades" class="display table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
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
        let tabla = $('#tablaEspecialidades').DataTable({
            "ajax": {
                "url": "controllers/contEspecialidad.php",
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
                    "data": "descripcion"
                },
                {
                    "data": "id",
                    "render": function(data, type, row) {
                        return `
                  <button class="btn btn-primary btn-sm editar" 
                          data-id="${data}" 
                          data-nombre="${row.nombre}" 
                          data-descripcion="${row.descripcion}">
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

        // Guardar especialidad
        $('#btnGuardarEspecialidad').click(function() {
            let nombre = $('#nombre').val().trim();
            let descripcion = $('#descripcion').val().trim();

            if (!nombre) {
                alert("El nombre es obligatorio");
                return;
            }

            $.post("controllers/contEspecialidad.php", {
                proceso: "REGISTRAR",
                nombre: nombre,
                descripcion: descripcion
            }, function(resultado) {
                if (resultado == "1") {
                    alert("Especialidad registrada correctamente");
                    $('#addEspecialidadModal').modal('hide');
                    tabla.ajax.reload();
                } else if (resultado == "Nombre duplicado") {
                    alert("La especialidad ya existe");
                } else if (resultado == "Campos vacíos") {
                    alert("Completa todos los campos obligatorios");
                } else {
                    alert("Error al registrar especialidad");
                }
            });
        });

        // Abrir modal edición
        $('#tablaEspecialidades').on('click', '.editar', function() {
            $('#editId').val($(this).data('id'));
            $('#editNombre').val($(this).data('nombre'));
            $('#editDescripcion').val($(this).data('descripcion'));
            $('#editEspecialidadModal').modal('show');
        });

        // Guardar cambios edición
        $('#btnEditarEspecialidad').click(function() {
            let id = $('#editId').val();
            let nombre = $('#editNombre').val().trim();
            let descripcion = $('#editDescripcion').val().trim();

            if (!nombre) {
                alert("El nombre es obligatorio");
                return;
            }

            $.post("controllers/contEspecialidad.php", {
                proceso: "EDITAR",
                id: id,
                nombre: nombre,
                descripcion: descripcion
            }, function(resultado) {
                if (resultado == "1") {
                    alert("Especialidad actualizada correctamente");
                    $('#editEspecialidadModal').modal('hide');
                    tabla.ajax.reload();
                } else if (resultado == "Nombre duplicado") {
                    alert("La especialidad ya existe");
                } else if (resultado == "Campos vacíos") {
                    alert("Completa todos los campos obligatorios");
                } else {
                    alert("Error al actualizar especialidad");
                }
            });
        });

        // Eliminar especialidad
        $('#tablaEspecialidades').on('click', '.eliminar', function() {
            let id = $(this).data('id');
            if (confirm("¿Eliminar especialidad?")) {
                $.post("controllers/contEspecialidad.php", {
                    proceso: "ELIMINAR",
                    id: id
                }, function(resultado) {
                    if (resultado == "1") {
                        alert("Especialidad eliminada");
                        tabla.ajax.reload();
                    } else {
                        alert("Error al eliminar especialidad");
                    }
                });
            }
        });
    });
</script>