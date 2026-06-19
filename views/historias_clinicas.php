<?php
// views/historias_clinicas.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificación rápida de seguridad por si acceden al archivo directamente
if (!isset($_SESSION['rol'])) {
    echo "<div class='alert alert-danger'>Sesión no válida.</div>";
    exit();
}

$rol_actual = $_SESSION['rol'];
?>

<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Área Médica</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="#"><i class="flaticon-home"></i></a>
            </li>
            <li class="separator"><i class="flaticon-right-arrow"></i></li>
            <li class="nav-item"><a href="#">Historias Clínicas</a></li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fas fa-search mr-2"></i> Localizar Paciente</div>
                </div>
                <div class="card-body">
                    <form id="formBuscarPaciente" class="row align-items-end">
                        <div class="col-md-8 col-sm-12 form-group">
                            <label for="buscarDni" class="form-label font-weight-bold">Ingrese el DNI o Apellidos del Paciente</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                </div>
                                <input type="text" id="buscarDni" class="form-control" placeholder="Ej. 74839201 o Ymán" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 form-group">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search mr-2"></i> Buscar Historial
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="panelHistorial" style="display: none;">
        <div class="col-md-12">
            <div class="card card-with-nav">
                <div class="card-header bg-light">
                    <div class="row align-items-center p-2">
                        <div class="col">
                            <h3 class="text-primary font-weight-bold mb-1" id="lblNombrePaciente">Paciente: ---</h3>
                            <p class="text-muted mb-0">DNI: <span id="lblDniPaciente">---</span> | Edad: <span id="lblEdadPaciente">---</span> años</p>
                        </div>
                        <div class="col-auto">
                            <?php if ($rol_actual === 'admin' || $rol_actual === 'admision'): ?>
                                <button class="btn btn-info btn-round mr-2" data-toggle="modal" data-target="#modalSubirArchivo">
                                    <i class="fas fa-cloud-upload-alt mr-1"></i> Digitalizar Historial Físico
                                </button>
                            <?php endif; ?>

                            <?php if ($rol_actual === 'admin' || $rol_actual === 'medico'): ?>
                                <button class="btn btn-success btn-round" data-toggle="modal" data-target="#modalNuevoDiagnostico">
                                    <i class="fas fa-user-md mr-1"></i> Nueva Consulta Médica
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <h4 class="card-title mt-2 mb-3">Línea de Tiempo / Antecedentes Clínicos</h4>

                    <div class="table-responsive">
                        <table id="tablaHistorial" class="display table table-striped table-hover w-100">
                            <thead>
                                <tr>
                                    <th>Fecha / Hora</th>
                                    <th>Especialidad</th>
                                    <th>Personal que Registró</th>
                                    <th>Tipo de Entrada</th>
                                    <th>Descripción / Diagnóstico / Archivo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($rol_actual === 'admin' || $rol_actual === 'admision'): ?>
    <div class="modal fade" id="modalSubirArchivo" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-file-upload text-info mr-2"></i> Subir Documento Escaneado</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form id="formSubirHistorial" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" id="subirIdPaciente">
                        <input type="hidden" id="subirDniPaciente">
                        <div class="form-group">
                            <label>Especialidad de la Consulta Original</label>
                            <select id="subirEspecialidad" class="form-control" required>
                                <option value="">Cargando especialidades...</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Breve Descripción u Observación de Admisión</label>
                            <textarea id="subirDescripcion" class="form-control" rows="3" placeholder="Ej: Se escanea documento de antecedentes adjunto por el paciente en formato físico..." required></textarea>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold text-danger">Seleccione Archivo (PDF, JPG, PNG)</label>
                            <input type="file" id="archivoHistorial" class="form-control-file" accept=".pdf, .jpg, .jpeg, .png" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnGuardarArchivo" class="btn btn-info">Cargar Archivo</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($rol_actual === 'admin' || $rol_actual === 'medico'): ?>
    <div class="modal fade" id="modalNuevoDiagnostico" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-notes-medical text-success mr-2"></i> Registro Clínico de Evolución</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form id="formNuevoDiagnostico">
                    <div class="modal-body">
                        <input type="hidden" id="medicoIdPaciente">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Sintomatología / Motivo de Consulta</label>
                                <textarea id="medicoSintomas" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Examen Físico / Evaluación</label>
                                <textarea id="medicoExamen" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Diagnóstico Médico (CIE-10 u observaciones)</label>
                            <textarea id="medicoDiagnostico" class="form-control" rows="2" placeholder="Ej: Diagnóstico definitivo..." required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Plan de Tratamiento / Receta Médica</label>
                            <textarea id="medicoTratamiento" class="form-control" rows="3" placeholder="Medicamentos, dosis y cuidados específicos..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnGuardarConsulta" class="btn btn-success">Guardar Registro Médico</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="modal fade" id="modalVerDetalleClinico" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="txtDetalleTitulo"><i class="fas fa-search-plus mr-2"></i> Detalles de Registro Clínico</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Especialidad:</strong> <span id="txtDetalleEspecialidad" class="text-primary font-weight-bold"></span></p>
                        <p class="mb-1"><strong>Registrado Por:</strong> <span id="txtDetalleUsuario"></span></p>
                    </div>
                    <div class="col-md-6 text-md-right">
                        <p class="mb-1"><strong>Fecha de Atención:</strong> <span id="txtDetalleFecha"></span></p>
                        <p class="mb-1"><strong>Tipo Entrada:</strong> <span id="txtDetalleTipo"></span></p>
                    </div>
                </div>
                <hr>
                <div id="wrapperCamposMedicos">
                    <div class="form-group bg-light p-2 rounded">
                        <label class="font-weight-bold text-dark">Sintomatología / Motivo:</label>
                        <p id="viewSintomas" class="mb-0 text-muted" style="white-space: pre-wrap;"></p>
                    </div>
                    <div class="form-group bg-light p-2 rounded">
                        <label class="font-weight-bold text-dark">Examen Físico / Evaluación:</label>
                        <p id="viewExamen" class="mb-0 text-muted" style="white-space: pre-wrap;"></p>
                    </div>
                    <div class="form-group border-success p-2 rounded" style="border: 1px solid #28a745;">
                        <label class="font-weight-bold text-success">Diagnóstico Emitido:</label>
                        <p id="viewDiagnostico" class="mb-0 font-weight-bold" style="white-space: pre-wrap;"></p>
                    </div>
                    <div class="form-group bg-light p-2 rounded">
                        <label class="font-weight-bold text-dark">Plan de Tratamiento / Prescripción:</label>
                        <p id="viewTratamiento" class="mb-0 text-dark" style="white-space: pre-wrap;"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar Visualizador</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalVisorAdjunto" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-file-alt mr-2"></i> Documento Adjunto Digitalizado</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-0" style="height: 75vh; background-color: #525659;">
                <embed id="visorContenido" src="" type="application/pdf" width="100%" height="100%">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar Documento</button>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        let tablaHistorial;

        // --- CARGA DINÁMICA DE ESPECIALIDADES DESDE TU CONTROLADOR ---
        function cargarEspecialidadesHistorial() {
            $.post("controllers/contEspecialidad.php", {
                proceso: "LISTAR"
            }, function(data) {
                let blackjack = JSON.parse(data);
                let select = $('#subirEspecialidad');

                select.empty();

                if (blackjack.length === 0) {
                    select.append('<option value="">No hay especialidades activas</option>');
                    return;
                }

                select.append('<option value="">-- Seleccione Especialidad --</option>');
                blackjack.forEach(function(e) {
                    select.append(`<option value="${e.nombre}">${e.nombre}</option>`);
                });
            });
        }

        cargarEspecialidadesHistorial();

        // Acción al buscar un paciente por DNI o Nombre
        $('#formBuscarPaciente').submit(function(e) {
            e.preventDefault();
            let criterio = $('#buscarDni').val().trim();

            if (!criterio) return;

            $.post("controllers/contHistoriaClinica.php", {
                proceso: "BUSCAR_PACIENTE",
                criterio: criterio
            }, function(response) {
                let res = JSON.parse(response);

                if (res.status === "success") {
                    $('#lblNombrePaciente').text("Paciente: " + res.paciente.nombre + " " + res.paciente.apellido);
                    $('#lblDniPaciente').text(res.paciente.dni);
                    $('#lblEdadPaciente').text(res.paciente.edad);

                    $('#subirIdPaciente').val(res.paciente.id);
                    $('#subirDniPaciente').val(res.paciente.dni);
                    $('#medicoIdPaciente').val(res.paciente.id);

                    $('#panelHistorial').fadeIn();
                    cargarTablaHistorial(res.paciente.id);

                } else {
                    $('#panelHistorial').hide();
                    alert("Paciente no encontrado. Verifique los datos o regístrelo en el módulo de Pacientes.");
                }
            });
        });

        // Función para renderizar el Historial Clínico en el DataTable
        function cargarTablaHistorial(idPaciente) {
            if ($.fn.DataTable.isDataTable('#tablaHistorial')) {
                $('#tablaHistorial').DataTable().destroy();
            }

            tablaHistorial = $('#tablaHistorial').DataTable({
                "ajax": {
                    "url": "controllers/contHistoriaClinica.php",
                    "type": "POST",
                    "data": {
                        "proceso": "LISTAR_HISTORIAL",
                        "id_paciente": idPaciente
                    },
                    "dataSrc": ""
                },
                "columns": [{
                        "data": "fecha_registro"
                    },
                    {
                        "data": "especialidad"
                    },
                    {
                        "data": "usuario_registro"
                    },
                    {
                        "data": "tipo",
                        "render": function(data) {
                            if (data === 'DIGITALIZADO') {
                                return '<span class="badge badge-info"><i class="fas fa-file-pdf mr-1"></i> Digital</span>';
                            }
                            return '<span class="badge badge-success"><i class="fas fa-stethoscope mr-1"></i> Consulta</span>';
                        }
                    },
                    {
                        "data": "resumen_clinico"
                    },
                    {
                        "data": "id",
                        "render": function(data, type, row) {
                            // MODIFICADO: Ahora el botón usa una clase 'btn-abrir-visor' y un data-url en vez de un <a> directo
                            let btnArchivo = row.ruta_archivo ? `<button type="button" data-url="uploads/historias/${row.ruta_archivo}" class="btn btn-secondary btn-sm mr-1 btn-abrir-visor"><i class="fas fa-eye"></i> Ver Adjunto</button>` : '';

                            let rowDataAttr = btoa(unescape(encodeURIComponent(JSON.stringify(row))));

                            return `${btnArchivo} <button class="btn btn-primary btn-sm btn-ver-detalle" data-json="${rowDataAttr}"><i class="fa fa-search-plus"></i> Detalle</button>`;
                        }
                    }
                ],
                "order": [
                    [0, "desc"]
                ],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                }
            });
        }

        // --- NUEVO MANEJO: CLIC EN EL BOTÓN "VER ADJUNTO" ---
        $('#tablaHistorial').on('click', '.btn-abrir-visor', function() {
            let rutaCompleta = $(this).data('url');

            // Asignamos la url del archivo al source de la etiqueta embed
            $('#visorContenido').attr('src', rutaCompleta);

            // Abrimos el modal
            $('#modalVisorAdjunto').modal('show');
        });

        // Limpiar el visor cuando el modal se cierre (para detener la carga del archivo en segundo plano)
        $('#modalVisorAdjunto').on('hidden.bs.modal', function() {
            $('#visorContenido').attr('src', '');
        });

        // --- MANEJO DEL CLIC EN EL BOTÓN "DETALLE" ---
        $('#tablaHistorial').on('click', '.btn-ver-detalle', function() {
            let base64Data = $(this).data('json');
            let rowData = JSON.parse(decodeURIComponent(escape(atob(base64Data))));

            $('#txtDetalleEspecialidad').text(rowData.especialidad);
            $('#txtDetalleUsuario').text(rowData.usuario_registro);
            $('#txtDetalleFecha').text(rowData.fecha_registro);
            $('#txtDetalleTipo').text(rowData.tipo);

            if (rowData.tipo === 'CONSULTA INTERNA') {
                $('#viewSintomas').text(rowData.sintomas ? rowData.sintomas : 'No especificado.');
                $('#viewExamen').text(rowData.examen_physical ? rowData.examen_physical : (rowData.examen_fisico ? rowData.examen_fisico : 'No especificado.'));
                $('#viewDiagnostico').text(rowData.diagnostico);
                $('#viewTratamiento').text(rowData.tratamiento);
            } else {
                $('#viewSintomas').text('Documento escaneado en formato físico.');
                $('#viewExamen').text('N/A (Revisar el archivo adjunto)');
                $('#viewDiagnostico').text(rowData.resumen_clinico);
                $('#viewTratamiento').text('Revisar indicaciones en el documento original adjunto.');
            }

            $('#modalVerDetalleClinico').modal('show');
        });

        // --- ACCIÓN ADMISIÓN: GUARDAR ARCHIVO ADJUNTO ---
        $('#btnGuardarArchivo').click(function() {
            let idPaciente = $('#subirIdPaciente').val();
            let dniPaciente = $('#subirDniPaciente').val();
            let specialty = $('#subirEspecialidad').val();
            let descripcion = $('#subirDescripcion').val().trim();
            let archivo = $('#archivoHistorial')[0].files[0];

            if (!descripcion || !archivo) {
                alert("Por favor complete la descripción y seleccione un documento físico escaneado.");
                return;
            }

            let formData = new FormData();
            formData.append("proceso", "GUARDAR_ADJUNTO");
            formData.append("id_paciente", idPaciente);
            formData.append("dni_paciente", dniPaciente);
            formData.append("especialidad", specialty);
            formData.append("descripcion", descripcion);
            formData.append("archivo", archivo);

            $.ajax({
                url: "controllers/contHistoriaClinica.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(resultado) {
                    if (resultado == "1") {
                        alert("Documento digitalizado cargado con éxito en la historia clínica.");
                        $('#modalSubirArchivo').modal('hide');
                        $('#formSubirHistorial')[0].reset();
                        tablaHistorial.ajax.reload();
                    } else {
                        alert("Error en el servidor al subir el archivo.");
                    }
                }
            });
        });

        // --- ACCIÓN MÉDICO: GUARDAR EVOLUCIÓN CLÍNICA ---
        $('#btnGuardarConsulta').click(function() {
            let idPaciente = $('#medicoIdPaciente').val();
            let sintomas = $('#medicoSintomas').val().trim();
            let examen = $('#medicoExamen').val().trim();
            let diagnostico = $('#medicoDiagnostico').val().trim();
            let tratamiento = $('#medicoTratamiento').val().trim();

            if (!sintomas || !diagnostico || !tratamiento) {
                alert("Los campos de síntomas, diagnóstico y tratamiento son estrictamente obligatorios.");
                return;
            }

            $.post("controllers/contHistoriaClinica.php", {
                proceso: "GUARDAR_CONSULTA",
                id_paciente: idPaciente,
                sintomas: sintomas,
                examen: examen,
                diagnostico: diagnostico,
                tratamiento: tratamiento
            }, function(resultado) {
                if (resultado == "1") {
                    alert("Evolución médica registrada de forma segura en la historia clínica.");
                    $('#modalNuevoDiagnostico').modal('hide');
                    $('#formNuevoDiagnostico')[0].reset();
                    tablaHistorial.ajax.reload();
                } else {
                    alert("Error al procesar el registro de consulta.");
                }
            });
        });
    });
</script>