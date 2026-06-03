<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Citas Médicas</h4>
    </div>
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h4 class="card-title">Gestión de Agenda y Citas</h4>
            <button class="btn btn-primary btn-round ml-auto" id="btnNuevoCita" data-toggle="modal" data-target="#addCitaModal">
                <i class="fa fa-plus"></i> Agendar Cita
            </button>
        </div>
        <div class="card-body">

            <div class="modal fade" id="addCitaModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header no-bd">
                            <h5 class="modal-title">Agendar Nueva Cita</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <form id="formCita">
                                <input type="hidden" id="pacienteId" value="">

                                <div class="row bg-light p-3 mb-3 rounded border">
                                    <div class="col-12">
                                        <h6 class="text-primary font-weight-bold">1. Datos del Paciente</h6>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="selectTipoDoc">Tipo Documento</label>
                                            <select id="selectTipoDoc" class="form-control">
                                                <option value="DNI">DNI (Perú)</option>
                                                <option value="CE_PAS">C.E. / Pasaporte</option>
                                                <option value="SIN_DOC">Sin Documento / Emergencia</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label id="lblDocumento">N° de Documento</label>
                                            <div class="input-group">
                                                <input type="text" id="pacienteDni" class="form-control" placeholder="8 dígitos" maxlength="8" required>
                                                <div class="input-group-append">
                                                    <button class="btn btn-secondary" type="button" id="btnBuscarPaciente"><i class="fa fa-search"></i></button>
                                                </div>
                                            </div>
                                            <small id="statusPaciente" class="form-text text-muted">Ingrese DNI para verificar.</small>
                                        </div>
                                    </div>

                                    <div class="col-md-2"></div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombres</label>
                                            <input type="text" id="pacienteNombres" class="form-control" readonly required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Apellidos</label>
                                            <input type="text" id="pacienteApellidos" class="form-control" readonly required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Fecha de Nacimiento</label>
                                            <input type="date" id="pacienteFechaNac" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Teléfono</label>
                                            <input type="text" id="pacienteTelefono" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row pt-2">
                                    <div class="col-12">
                                        <h6 class="text-primary font-weight-bold">2. Detalles de la Cita</h6>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="selectEspecialidad">Especialidad</label>
                                            <select id="selectEspecialidad" class="form-control" required>
                                                <option value="">Seleccione especialidad...</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="selectMedico">Médico disponible</label>
                                            <select id="selectMedico" class="form-control" required disabled>
                                                <option value="">-- Primero elija una especialidad --</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fechaCita">Fecha</label>
                                            <input type="date" id="fechaCita" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="horaCita">Hora</label>
                                            <input type="time" id="horaCita" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="motivoCita">Motivo de Consulta (Opcional)</label>
                                            <input type="text" id="motivoCita" class="form-control" placeholder="Ej. Control de rutina...">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer no-bd">
                            <button type="button" id="btnGuardarCita" class="btn btn-primary" disabled>Guardar Todo</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalHomonimos" tabindex="-1" role="dialog" style="z-index: 1060;">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content border-warning shadow-lg">
                        <div class="modal-header bg-warning text-white">
                            <h5 class="modal-title font-weight-bold"><i class="fa fa-users"></i> Pacientes Coincidentes</h5>
                            <button type="button" class="close text-white" onclick="$('#modalHomonimos').modal('hide');"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-muted small">Se encontraron múltiples registros con ese criterio. Seleccione el correcto para evitar duplicados:</p>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm" id="tablaResultadosHomonimos">
                                    <thead>
                                        <tr class="bg-secondary text-white text-center">
                                            <th>Documento / ID</th>
                                            <th>Paciente</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="listaHomonimosBody"></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-success btn-block" id="btnMarcarComoNuevoAbsoluto">
                                <i class="fa fa-user-plus"></i> Ninguno coincide, registrar como NUEVO
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="editarCitaModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content border-info shadow-lg">
                        <div class="modal-header bg-info text-white">
                            <h5 class="modal-title font-weight-bold"><i class="fa fa-edit"></i> Gestionar Motivo de Cita</h5>
                            <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="editCitaId">
                            <div class="form-group mb-2">
                                <label class="text-muted mb-0 small">Paciente:</label>
                                <p id="editPacienteTxt" class="form-control-plaintext font-weight-bold p-0 mb-2"></p>
                            </div>
                            <div class="form-group mb-3">
                                <label class="text-muted mb-0 small">Médico / Especialidad:</label>
                                <p id="editMedicoTxt" class="form-control-plaintext text-muted p-0 mb-0"></p>
                            </div>
                            <div class="form-group">
                                <label for="editMotivoInput" class="font-weight-bold text-primary">Motivo de Consulta:</label>
                                <textarea class="form-control" id="editMotivoInput" rows="4" placeholder="Escriba el motivo clínico de la consulta..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-success" onclick="guardarMotivoCita()">Guardar Cambios</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="tablaCitas" class="display table table-striped table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Fecha/Hora</th>
                            <th>Paciente / Información</th>
                            <th>Médico</th>
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

        // 1. Inicializar DataTable Principal
        let tablaCitas = $('#tablaCitas').DataTable({
            "ajax": {
                "url": "controllers/contCita.php",
                "type": "POST",
                "data": {
                    "proceso": "LISTAR_TABLA_OPERATIVA"
                }
            },
            "order": [
                [0, "desc"]
            ],
            "columns": [{
                    "data": null,
                    "render": function(data, type, row) {
                        if (row.start) {
                            let partes = row.start.split('T');
                            return `<strong>${partes[0]}</strong> <br> <small class="text-muted">${partes[1] || ''}</small>`;
                        }
                        return '';
                    }
                },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        let edad = row.paciente_edad ? `${row.paciente_edad} años` : 'N/E';
                        let telf = row.paciente_telefono ? row.paciente_telefono : 'Sin telf.';
                        return `
                            <strong>${row.title}</strong> <br>
                            <small class="text-muted">
                                <i class="fa fa-id-card"></i> ${row.paciente_dni} | 
                                <i class="fa fa-birthday-cake"></i> ${edad} | 
                                <i class="fa fa-phone"></i> ${telf}
                            </small>
                        `;
                    }
                },
                {
                    "data": "medico"
                },
                {
                    "data": "especialidad"
                },
                {
                    "data": "estado",
                    "render": function(estado) {
                        let badges = {
                            'pendiente': '<span class="badge badge-warning">Pendiente</span>',
                            'confirmada': '<span class="badge badge-info">Confirmada</span>',
                            'atendida': '<span class="badge badge-success">Atendida</span>',
                            'cancelada': '<span class="badge badge-danger">Cancelada</span>'
                        };
                        return badges[estado] || `<span class="badge badge-count">${estado}</span>`;
                    }
                },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        if (row.estado === 'atendida' || row.estado === 'cancelada') {
                            return '<button class="btn btn-link text-muted" disabled><i class="fa fa-lock"></i> Finalizada</button>';
                        }

                        // NUEVO: Convertimos el objeto de la fila a Base64 de forma segura para evitar problemas con comillas en el HTML
                        let rowDataJson = btoa(unescape(encodeURIComponent(JSON.stringify(row))));

                        return `
                            <div class="form-button-action">
                                <button type="button" class="btn btn-link btn-primary btn-lg" onclick="abrirModalEditarMotivo('${rowDataJson}')" title="Editar Motivo"><i class="fa fa-edit"></i></button>
                                <button type="button" class="btn btn-link btn-info btn-lg btnConfirmarCita" data-id="${row.id}" title="Confirmar Cita"><i class="fa fa-check"></i></button>
                                <button type="button" class="btn btn-link btn-success btn-lg btnAtenderCita" data-id="${row.id}" title="Marcar como Atendida"><i class="fa fa-user-md"></i></button>
                                <button type="button" class="btn btn-link btn-danger btn-lg btnCancelarCita" data-id="${row.id}" title="Cancelar Cita"><i class="fa fa-ban"></i></button>
                            </div>
                        `;
                    }
                }
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
            }
        });


        // 2. Control del Modal y Carga Inicial de Especialidades (Original intacto)
        $('#btnNuevoCita').click(function() {
            $('#formCita')[0].reset();
            $('#pacienteId').val('');
            $('#selectTipoDoc').val('DNI');
            $('#btnGuardarCita').attr('disabled', true);
            setCamposPacienteReadOnly(true);
            ajustarReglasDocumento();

            $('#selectEspecialidad').html('<option value="">Cargando especialidades...</option>');
            $('#selectMedico').html('<option value="">-- Primero elija una especialidad --</option>').prop('disabled', true);

            $.post("controllers/contEspecialidad.php", {
                proceso: "LISTAR"
            }, function(response) {
                try {
                    let especialidades = JSON.parse(response);
                    $('#selectEspecialidad').html('<option value="">Seleccione especialidad...</option>');

                    if (especialidades.length > 0) {
                        especialidades.forEach(esp => {
                            $('#selectEspecialidad').append(`<option value="${esp.id}">${esp.nombre}</option>`);
                        });
                    } else {
                        $('#selectEspecialidad').html('<option value="">No hay especialidades activas</option>');
                    }
                } catch (e) {
                    console.error("Error al parsear especialidades:", e);
                    $('#selectEspecialidad').html('<option value="">Error al cargar datos</option>');
                }
            });
        });

        // Lógica de Selects Anidados (Original intacto)
        $('#selectEspecialidad').change(function() {
            let espId = $(this).val();
            let selectMed = $('#selectMedico');

            if (espId === "") {
                selectMed.html('<option value="">-- Primero elija una especialidad --</option>').prop('disabled', true);
                return;
            }

            selectMed.html('<option value="">Cargando médicos activos...</option>').prop('disabled', true);

            $.ajax({
                url: "controllers/contMedico.php",
                type: "POST",
                dataType: "json",
                data: {
                    proceso: "LISTAR_POR_ESPECIALIDAD",
                    especialidad_id: espId
                },
                success: function(response) {
                    selectMed.html('<option value="">Seleccione un médico...</option>');

                    if (response.length > 0) {
                        $.each(response, function(index, medico) {
                            selectMed.append(`<option value="${medico.id}">${medico.nombre_completo}</option>`);
                        });
                        selectMed.prop('disabled', false);
                    } else {
                        selectMed.html('<option value="">No hay médicos registrados en esta rama</option>');
                    }
                },
                error: function() {
                    alert("Error en la comunicación con el servidor de médicos.");
                    selectMed.html('<option value="">Error al cargar la lista</option>');
                }
            });
        });

        function setCamposPacienteReadOnly(status) {
            $('#pacienteNombres').attr('readonly', status);
            $('#pacienteApellidos').attr('readonly', status);
            $('#pacienteFechaNac').attr('readonly', status);
            $('#pacienteTelefono').attr('readonly', status);
        }

        function ajustarReglasDocumento() {
            let tipo = $('#selectTipoDoc').val();
            $('#pacienteDni').val('');

            if (tipo === "DNI") {
                $('#lblDocumento').text('DNI del Paciente');
                $('#pacienteDni').attr('placeholder', '8 dígitos').attr('maxlength', '8').removeAttr('readonly');
                $('#btnBuscarPaciente').removeAttr('disabled');
                $('#statusPaciente').html('Ingrese DNI para verificar.').attr('class', 'form-text text-muted');
                $('#btnGuardarCita').attr('disabled', true);
                setCamposPacienteReadOnly(true);
            } else if (tipo === "CE_PAS" || tipo === "SIN_DOC") {
                $('#lblDocumento').text(tipo === "SIN_DOC" ? 'Buscar por Nombre o Apellido' : 'N° Carnet / Pasaporte');
                $('#pacienteDni').attr('placeholder', tipo === "SIN_DOC" ? 'Escriba ej: Ymán' : 'Alfanumérico').attr('maxlength', '40').removeAttr('readonly');
                $('#btnBuscarPaciente').removeAttr('disabled');
                $('#statusPaciente').html(tipo === "SIN_DOC" ? '<i class="fa fa-search"></i> Busque primero si ya tiene historia clínica provisional.' : 'Ingrese documento para verificar.').attr('class', 'form-text text-info');
                $('#btnGuardarCita').attr('disabled', true);
                setCamposPacienteReadOnly(true);
            }
        }

        $('#selectTipoDoc').change(ajustarReglasDocumento);

        // 3. Motor de Búsqueda y Manejo de Homónimos (Original intacto)
        function buscarPaciente() {
            let tipo = $('#selectTipoDoc').val();
            let dni = $('#pacienteDni').val().trim();

            if (tipo === "DNI" && !/^[0-9]{8}$/.test(dni)) {
                return;
            }
            if (dni === "") {
                return;
            }

            $.post("controllers/contPaciente.php", {
                proceso: "BUSCAR_DNI",
                dni: dni
            }, function(resultado) {
                if (resultado == "0") {
                    inyectarPacienteNuevoDirecto();
                } else {
                    let datos = JSON.parse(resultado);

                    if (!Array.isArray(datos)) {
                        cargarPacienteFormularioPrincipal(datos);
                    } else {
                        if (datos.length === 1) {
                            cargarPacienteFormularioPrincipal(datos[0]);
                        } else {
                            $('#listaHomonimosBody').empty();
                            datos.forEach(p => {
                                let fecha_txt = p.fecha_nac ? p.fecha_nac : 'No registrada';
                                $('#listaHomonimosBody').append(`
                                    <tr>
                                        <td class="align-middle text-center font-weight-bold text-primary small">${p.dni}</td>
                                        <td>
                                            <strong>${p.apellidos}, ${p.nombres}</strong><br>
                                            <small class="text-muted"><i class="fa fa-birthday-cake"></i> ${fecha_txt} | <i class="fa fa-phone"></i> ${p.telefono || 'S/T'}</small>
                                        </td>
                                        <td class="text-center align-middle">
                                            <button type="button" class="btn btn-xs btn-primary btnSeleccionarHomonimo" 
                                                data-id="${p.id}" data-dni="${p.dni}" data-nombres="${p.nombres}" 
                                                data-apellidos="${p.apellidos}" data-fecha="${p.fecha_nac}" data-telefono="${p.telefono}">
                                                <i class="fa fa-user-check"></i> Es él
                                            </button>
                                        </td>
                                    </tr>
                                `);
                            });
                            $('#modalHomonimos').modal('show');
                        }
                    }
                }
            });
        }

        $('#listaHomonimosBody').on('click', '.btnSeleccionarHomonimo', function() {
            let p = {
                id: $(this).data('id'),
                dni: $(this).data('dni'),
                nombres: $(this).data('nombres'),
                apellidos: $(this).data('apellidos'),
                fecha_nac: $(this).data('fecha'),
                telefono: $(this).data('telefono')
            };
            cargarPacienteFormularioPrincipal(p);
            $('#modalHomonimos').modal('hide');
        });

        $('#btnMarcarComoNuevoAbsoluto').click(function() {
            inyectarPacienteNuevoDirecto();
            $('#modalHomonimos').modal('hide');
        });

        function cargarPacienteFormularioPrincipal(p) {
            $('#pacienteId').val(p.id);
            $('#pacienteNombres').val(p.nombres);
            $('#pacienteApellidos').val(p.apellidos);
            $('#pacienteFechaNac').val(p.fecha_nac);
            $('#pacienteTelefono').val(p.telefono);
            setCamposPacienteReadOnly(true);
            $('#btnGuardarCita').removeAttr('disabled');
            $('#statusPaciente').html('<i class="fa fa-check-circle"></i> Paciente seleccionado con éxito.').attr('class', 'form-text text-success font-weight-bold');
        }

        function inyectarPacienteNuevoDirecto() {
            $('#pacienteId').val('NUEVO');
            $('#pacienteNombres').val('');
            $('#pacienteApellidos').val('');
            $('#pacienteFechaNac').val('');
            $('#pacienteTelefono').val('');
            setCamposPacienteReadOnly(false);
            $('#btnGuardarCita').removeAttr('disabled');
            $('#statusPaciente').html('<i class="fa fa-info-circle"></i> Nuevo historial provisional activo. Ingrese los datos.').attr('class', 'form-text text-warning font-weight-bold');
        }

        $('#btnBuscarPaciente').click(buscarPaciente);
        $('#pacienteDni').keyup(function() {
            let tipo = $('#selectTipoDoc').val();
            if (tipo === "DNI" && this.value.length === 8) {
                buscarPaciente();
            }
        });

        // 4. Guardar Cita Completa (Original intacto)
        $('#btnGuardarCita').click(function() {
            let paciente_id = $('#pacienteId').val();
            let medico_id = $('#selectMedico').val();
            let fecha = $('#fechaCita').val();
            let hora = $('#horaCita').val();
            let motivo = $('#motivoCita').val().trim();

            if (!paciente_id || !medico_id || !fecha || !hora) {
                alert("Por favor rellene los campos obligatorios.");
                return;
            }

            let payload = {
                proceso: "REGISTRAR",
                paciente_id: paciente_id,
                medico_id: medico_id,
                fecha: fecha,
                hora: hora,
                motivo: motivo,
                tipo_doc: $('#selectTipoDoc').val(),
                dni: $('#pacienteDni').val().trim(),
                nombres: $('#pacienteNombres').val().trim(),
                apellidos: $('#pacienteApellidos').val().trim(),
                fecha_nac: $('#pacienteFechaNac').val(),
                telefono: $('#pacienteTelefono').val().trim()
            };

            $.post("controllers/contCita.php", payload, function(resultado) {
                if (resultado == "1") {
                    alert("Cita médica agendada correctamente.");
                    $('#addCitaModal').modal('hide');
                    tablaCitas.ajax.reload();
                } else {
                    alert("Error al procesar la cita en el servidor.");
                }
            });
        });

        // 5. Cambios de Estado (Original intacto)
        $('#tablaCitas').on('click', '.btnConfirmarCita', function() {
            let id = $(this).data('id');
            if (confirm("¿Está seguro de marcar esta cita como CONFIRMADA?")) {
                $.post("controllers/contCita.php", {
                    proceso: "CAMBIAR_ESTADO",
                    cita_id: id,
                    estado: "confirmada"
                }, function(res) {
                    if (res == "1") {
                        tablaCitas.ajax.reload(null, false);
                    }
                });
            }
        });

        $('#tablaCitas').on('click', '.btnAtenderCita', function() {
            let id = $(this).data('id');
            if (confirm("¿Está seguro de marcar esta cita como ATENDIDA?")) {
                $.post("controllers/contCita.php", {
                    proceso: "CAMBIAR_ESTADO",
                    cita_id: id,
                    estado: "atendida"
                }, function(res) {
                    if (res == "1") {
                        tablaCitas.ajax.reload(null, false);
                    }
                });
            }
        });

        $('#tablaCitas').on('click', '.btnCancelarCita', function() {
            let id = $(this).data('id');
            if (confirm("¿Está seguro de CANCELAR esta cita médica?")) {
                $.post("controllers/contCita.php", {
                    proceso: "CAMBIAR_ESTADO",
                    cita_id: id,
                    estado: "cancelada"
                }, function(res) {
                    if (res == "1") {
                        tablaCitas.ajax.reload(null, false);
                    }
                });
            }
        });

        // NUEVO 6: FUNCIONES GLOBALES PARA EL CONTROL DE MOTIVO DE CONSULTA
        window.abrirModalEditarMotivo = function(base64Data) {
            // Decodificamos el JSON de la fila de forma segura
            let row = JSON.parse(decodeURIComponent(escape(atob(base64Data))));

            $('#editCitaId').val(row.id);
            $('#editPacienteTxt').html(`<i class="fa fa-user"></i> ${row.title} <span class="text-muted font-weight-normal">(DNI: ${row.paciente_dni || 'N/E'})</span>`);
            $('#editMedicoTxt').html(`<i class="fa fa-user-md"></i> Dr(a). ${row.medico} <br><i class="fa fa-stethoscope"></i> ${row.especialidad}`);
            $('#editMotivoInput').val(row.motivo || ''); // Si no tiene motivo, limpia el campo

            $('#editarCitaModal').modal('show');
        }

        window.guardarMotivoCita = function() {
            let id = $('#editCitaId').val();
            let motivoTxt = $('#editMotivoInput').val().trim();

            if (!id) {
                alert("Error: No se pudo capturar el ID de la cita.");
                return;
            }

            $.ajax({
                url: "controllers/contCita.php",
                type: "POST",
                data: {
                    proceso: "ACTUALIZAR_MOTIVO",
                    cita_id: id,
                    motivo: motivoTxt
                },
                success: function(response) {
                    if (response.trim() == "1") {
                        alert("El motivo de consulta se actualizó correctamente.");
                        $('#editarCitaModal').modal('hide');
                        tablaCitas.ajax.reload(null, false); // Recarga DataTable sin perder paginación
                    } else {
                        alert("Atención: No se realizaron cambios o el registro no varió.");
                    }
                },
                error: function() {
                    alert("Error crítico: No se pudo establecer conexión con contCita.php");
                }
            });
        }

    });
</script>