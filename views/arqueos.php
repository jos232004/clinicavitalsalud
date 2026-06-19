<div class="page-inner">
    <div class="page-header d-flex align-items-center">
        <h4 class="page-title">Arqueo de Caja Diario</h4>
        <div class="ml-auto d-flex align-items-center">
            <label class="mr-2 mb-0 font-weight-bold">Fecha Arqueo:</label>
            <input type="date" id="filtroFecha" class="form-control form-control-sm mr-3" value="<?php date_default_timezone_set('America/Lima');
                                                                                                    echo date('Y-m-d'); ?>" style="width: 160px;">
            <button class="btn btn-primary btn-round" data-toggle="modal" data-target="#addArqueoModal">
                <i class="fa fa-plus"></i> Nuevo Registro / Egreso
            </button>
            <button id="btnExportarExcel" class="btn btn-success btn-round ml-2">
                <i class="fa fa-file-excel"></i> Exportar Excel
            </button>
        </div>
    </div>

    <div class="row m-1">
        <div class="col-sm-6 col-md-3 p-1">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-info bubble-shadow-small"><i class="fa fa-arrow-down"></i></div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Monto Recaudado (Bruto)</p>
                                <h4 class="card-title text-info" id="cardTotalIngreso">S/. 0.00</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3 p-1">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-danger bubble-shadow-small"><i class="fa fa-arrow-up"></i></div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Egresos</p>
                                <h4 class="card-title text-danger" id="cardTotalEgreso">S/. 0.00</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3 p-1">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-secondary bubble-shadow-small" style="background:#6f42c1 !important;"><i class="fa fa-qrcode"></i></div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Yape</p>
                                <h4 class="card-title" style="color:#6f42c1;" id="cardTotalYape">S/. 0.00</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3 p-1">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small"><i class="fa fa-wallet"></i></div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Ingreso Total Clínica (Con Egresos)</p>
                                <h4 class="card-title text-success" id="cardNetoClinica">S/. 0.00</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-2">
        <div class="card-header">
            <h4 class="card-title">Movimientos del Día</h4>
        </div>
        <div class="card-body">
            <div class="modal fade" id="addArqueoModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header no-bd">
                            <h5 class="modal-title font-weight-bold">Registrar Movimiento de Caja</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <form id="formArqueo">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold text-primary">Fecha de Operación</label>
                                        <input type="date" id="fecha_arqueo" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold text-primary">Turno</label>
                                        <select id="turno" class="form-control" required>
                                            <option value="MAÑANA">MAÑANA</option>
                                            <option value="TARDE">TARDE</option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <hr class="my-2">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>DNI Paciente <small class="text-muted">(Enter para buscar)</small></label>
                                        <div class="input-group">
                                            <input type="text" id="dni_paciente" class="form-control" placeholder="8 dígitos" maxlength="8">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" id="btnBuscarDni"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-8">
                                        <label>Nombre Completo del Paciente</label>
                                        <input type="text" id="nombre_paciente" class="form-control" placeholder="Nombres y Apellidos">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Celular</label>
                                        <input type="text" id="celular_paciente" class="form-control" placeholder="Ej: 999888777">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Edad</label>
                                        <input type="number" id="edad_paciente" class="form-control" placeholder="Años">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Tipo de Paciente</label>
                                        <select id="tipo_paciente" class="form-control">
                                            <option value="AMBULATORIO">AMBULATORIO</option>
                                            <option value="POSTOPERATORIO">POSTOPERATORIO</option>
                                            <option value="HOSPITALIZADO">HOSPITALIZADO</option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <hr class="my-2">
                                    </div>

                                    <div class="form-group col-md-8">
                                        <label class="font-weight-bold">Descripción del Servicio o Gasto Realizado</label>
                                        <input type="text" id="descripcion_servicio" class="form-control" placeholder="Ej: Consulta general, Ecografía doppler, etc." required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Médico Tratante</label>
                                        <input type="text" id="medico_tratante" class="form-control" placeholder="Dr. / Dra.">
                                    </div>

                                    <div class="form-group col-12 bg-light p-3 rounded my-2">
                                        <label class="d-block font-weight-bold mb-3 text-secondary">Selecciona los conceptos e ingresa sus precios:</label>

                                        <div class="row align-items-center mb-2">
                                            <div class="col-md-4 col-6">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input chk-especialidad" id="es_consulta" value="consulta">
                                                    <label class="custom-control-label font-weight-bold text-dark" for="es_consulta">Consulta Médica (CONSULTA)</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <input type="number" step="0.01" id="precio_consulta" class="form-control form-control-sm txt-precio border-primary" value="0.00" disabled placeholder="S/. Precio Consulta">
                                            </div>
                                        </div>

                                        <div class="row align-items-center mb-2">
                                            <div class="col-md-4 col-6">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input chk-especialidad" id="es_laboratorio" value="lab">
                                                    <label class="custom-control-label" for="es_laboratorio">Laboratorio (LAB)</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <input type="number" step="0.01" id="precio_laboratorio" class="form-control form-control-sm txt-precio" value="0.00" disabled placeholder="S/. Precio">
                                            </div>
                                        </div>

                                        <div class="row align-items-center mb-2">
                                            <div class="col-md-4 col-6">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input chk-especialidad" id="es_rayos_x" value="rx">
                                                    <label class="custom-control-label" for="es_rayos_x">Rayos X (RX)</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <input type="number" step="0.01" id="precio_rayos_x" class="form-control form-control-sm txt-precio" value="0.00" disabled placeholder="S/. Precio">
                                            </div>
                                        </div>

                                        <div class="row align-items-center mb-2">
                                            <div class="col-md-4 col-6">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input chk-especialidad" id="es_ekg" value="ekg">
                                                    <label class="custom-control-label" for="es_ekg">Electrocardiograma (EKG)</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <input type="number" step="0.01" id="precio_ekg" class="form-control form-control-sm txt-precio" value="0.00" disabled placeholder="S/. Precio">
                                            </div>
                                        </div>

                                        <div class="row align-items-center mb-2">
                                            <div class="col-md-4 col-6">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input chk-especialidad" id="es_riesgo_quirurgico" value="rq">
                                                    <label class="custom-control-label" for="es_riesgo_quirurgico">Riesgo Quirúrgico (R.Q.)</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <input type="number" step="0.01" id="precio_riesgo_quirurgico" class="form-control form-control-sm txt-precio" value="0.00" disabled placeholder="S/. Precio">
                                            </div>
                                        </div>

                                        <div class="row align-items-center mb-2">
                                            <div class="col-md-4 col-6">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input chk-especialidad" id="es_ecografia" value="eco">
                                                    <label class="custom-control-label" for="es_ecografia">Ecografía (ECO)</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <input type="number" step="0.01" id="precio_ecografia" class="form-control form-control-sm txt-precio" value="0.00" disabled placeholder="S/. Precio">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label class="font-weight-bold text-success">Monto Total Cobrado (S/.)</label>
                                        <input type="number" step="0.01" id="monto_cancelar" class="form-control bg-white font-weight-bold" value="0.00" readonly>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="font-weight-bold text-warning">Pago Médico / Comisión</label>
                                        <input type="number" step="0.01" id="pago_medico_comision" class="form-control" value="0.00">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="font-weight-bold text-danger">Egreso / Gasto Caja</label>
                                        <input type="number" step="0.01" id="egreso" class="form-control" value="0.00">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="font-weight-bold">Método de Pago</label>
                                        <select id="metodo_pago" class="form-control">
                                            <option value="EFECTIVO">EFECTIVO</option>
                                            <option value="YAPE">YAPE</option>
                                            <option value="TARJETA">TARJETA</option>
                                            <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-12">
                                        <label class="font-weight-bold">Observaciones del Registro</label>
                                        <textarea id="observaciones" class="form-control" rows="2" placeholder="Notas adicionales importantes..."></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer no-bd">
                            <button type="button" id="btnGuardarArqueo" class="btn btn-primary">Guardar Registro</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <style>
                /* Quita todos los bordes de la tabla, filas y celdas */
                #tablaArqueos,
                #tablaArqueos th,
                #tablaArqueos td,
                #tablaArqueos thead th,
                #tablaArqueos tfoot th {
                    border: none !important;
                    border-bottom: none !important;
                    border-top: none !important;
                }
            </style>

            <div class="table-responsive">
                <table id="tablaArqueos" class="display table table-striped table-hover w-100">
                    <thead>
                        <tr class="bg-dark text-light">
                            <th>Turno</th>
                            <th>Paciente / Detalle</th>
                            <th>Descripción</th>
                            <th>Médico</th>
                            <th>Consulta</th>
                            <th>Lab</th>
                            <th>Rx</th>
                            <th>EKG</th>
                            <th>R.Q</th>
                            <th>ECO</th>
                            <th>Monto</th>
                            <th>Pago Doc</th>
                            <th class="bg-success text-light">Ingreso</th>
                            <th class="bg-danger text-light">Egreso</th>
                            <th>Obs</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot class="bg-warning text-light font-weight-bold">
                        <tr>
                            <th colspan="4" class="text-right">TOTALES DEL DÍA:</th>
                            <th id="totCONSULTA">S/. 0.00</th>
                            <th id="totLAB">S/. 0.00</th>
                            <th id="totRX">S/. 0.00</th>
                            <th id="totEKG">S/. 0.00</th>
                            <th id="totRQ">S/. 0.00</th>
                            <th id="totECO">S/. 0.00</th>
                            <th id="totMonto" class="bg-info">S/. 0.00</th>
                            <th id="totComision" class="bg-info">S/. 0.00</th>
                            <th id="totIngresoClinica" class="text-success" style="background:#e8f5e9;">S/. 0.00</th>
                            <th id="totEgreso" class="text-danger" style="background: #ffc4c4;">S/. 0.00</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#btnExportarExcel').click(function() {
            let fechaSeleccionada = $('#filtroFecha').val();
            // Redirecciona al script forzando la descarga del archivo del día seleccionado
            window.location.href = "controllers/exportarExcel.php?fecha=" + fechaSeleccionada;
        });

        // --- MANEJO DINÁMICO DE CHECKBOXES Y PRECIOS ---
        $('.chk-especialidad').change(function() {
            let inputPrecio = $(this).closest('.row').find('.txt-precio');
            if ($(this).is(':checked')) {
                inputPrecio.prop('disabled', false).val('').focus();
            } else {
                inputPrecio.prop('disabled', true).val('0.00');
            }
            calcularMontoTotalFormulario();
        });

        $('.txt-precio').on('input', function() {
            calcularMontoTotalFormulario();
        });

        function calcularMontoTotalFormulario() {
            let total = 0;
            $('.txt-precio').each(function() {
                let valor = parseFloat($(this).val()) || 0;
                total += valor;
            });
            $('#monto_cancelar').val(total.toFixed(2));
        }

        // --- DATATABLES AJAX ---
        let tabla = $('#tablaArqueos').DataTable({
            "ajax": {
                "url": "controllers/ArqueoController.php",
                "type": "POST",
                "data": function(d) {
                    d.proceso = "LISTAR_FECHA";
                    d.fecha = $('#filtroFecha').val();
                },
                "dataSrc": ""
            },
            "drawCallback": function(settings) {
                let api = this.api();
                let json = api.ajax.json();

                let tConsulta = 0,
                    tLab = 0,
                    tRx = 0,
                    tEkg = 0,
                    tRq = 0,
                    tEco = 0;
                let tMonto = 0,
                    tComision = 0,
                    tIngresoClinica = 0,
                    tEgreso = 0,
                    tYape = 0;

                if (json && json.length > 0) {
                    json.forEach(function(row) {
                        let bruto = parseFloat(row.monto_cancelar) || 0;
                        let comision = parseFloat(row.pago_medico_comision) || 0;
                        let egresoFila = parseFloat(row.egreso) || 0;

                        // Operación matemática clave por fila solicitado:
                        let netoFilaClinica = bruto - comision;

                        if (parseInt(row.es_consulta) === 1) tConsulta += bruto;
                        if (parseInt(row.es_laboratorio) === 1) tLab += bruto;
                        if (parseInt(row.es_rayos_x) === 1) tRx += bruto;
                        if (parseInt(row.es_ekg) === 1) tEkg += bruto;
                        if (parseInt(row.es_riesgo_quirurgico) === 1) tRq += bruto;
                        if (parseInt(row.es_ecografia) === 1) tEco += bruto;

                        tMonto += bruto;
                        tComision += comision;
                        tIngresoClinica += netoFilaClinica;
                        tEgreso += egresoFila;

                        if (row.metodo_pago === 'YAPE') tYape += bruto;
                    });
                }

                // Renderizado en el Footer
                $('#totCONSULTA').html(`S/. ${tConsulta.toFixed(2)}`);
                $('#totLAB').html(`S/. ${tLab.toFixed(2)}`);
                $('#totRX').html(`S/. ${tRx.toFixed(2)}`);
                $('#totEKG').html(`S/. ${tEkg.toFixed(2)}`);
                $('#totRQ').html(`S/. ${tRq.toFixed(2)}`);
                $('#totECO').html(`S/. ${tEco.toFixed(2)}`);
                $('#totMonto').html(`S/. ${tMonto.toFixed(2)}`);
                $('#totComision').html(`S/. ${tComision.toFixed(2)}`);
                $('#totIngresoClinica').html(`S/. ${tIngresoClinica.toFixed(2)}`);
                $('#totEgreso').html(`S/. ${tEgreso.toFixed(2)}`);

                // Kpis superiores
                $('#cardTotalIngreso').html(`S/. ${tMonto.toFixed(2)}`);
                $('#cardTotalEgreso').html(`S/. ${tEgreso.toFixed(2)}`);
                $('#cardTotalYape').html(`S/. ${tYape.toFixed(2)}`);
                // Neto final absoluto restando los egresos globales de caja chica
                $('#cardNetoClinica').html(`S/. ${(tIngresoClinica - tEgreso).toFixed(2)}`);
            },
            "columns": [{
                    "data": "turno"
                },
                {
                    "data": "nombre_paciente",
                    "render": function(data, type, row) {
                        if (!data) return `<span class="badge badge-danger">GASTO DIRECTO</span>`;
                        let badgePago = `<span class="badge badge-light text-dark border ml-1" style="font-size:10px;">${row.metodo_pago}</span>`;
                        if (row.metodo_pago === 'YAPE') {
                            badgePago = `<span class="badge ml-1" style="background:#6f42c1 !important; color:white; font-size:10px;">YAPE</span>`;
                        }
                        return `<b>${data}</b> ${badgePago}<br><small class="text-muted">DNI: ${row.dni_paciente || '--'}</small>`;
                    }
                },
                {
                    "data": "descripcion_servicio"
                },
                {
                    "data": "medico_tratante",
                    "render": function(d) {
                        return d ? d : '--';
                    }
                },

                // Distribución de Precios en columnas
                {
                    "data": "es_consulta",
                    "render": function(data, type, row) {
                        return parseInt(data) === 1 ? `S/. ${parseFloat(row.monto_cancelar).toFixed(2)}` : 'S/. 0.00';
                    }
                },
                {
                    "data": "es_laboratorio",
                    "render": function(data, type, row) {
                        return parseInt(data) === 1 ? `S/. ${parseFloat(row.monto_cancelar).toFixed(2)}` : 'S/. 0.00';
                    }
                },
                {
                    "data": "es_rayos_x",
                    "render": function(data, type, row) {
                        return parseInt(data) === 1 ? `S/. ${parseFloat(row.monto_cancelar).toFixed(2)}` : 'S/. 0.00';
                    }
                },
                {
                    "data": "es_ekg",
                    "render": function(data, type, row) {
                        return parseInt(data) === 1 ? `S/. ${parseFloat(row.monto_cancelar).toFixed(2)}` : 'S/. 0.00';
                    }
                },
                {
                    "data": "es_riesgo_quirurgico",
                    "render": function(data, type, row) {
                        return parseInt(data) === 1 ? `S/. ${parseFloat(row.monto_cancelar).toFixed(2)}` : 'S/. 0.00';
                    }
                },
                {
                    "data": "es_ecografia",
                    "render": function(data, type, row) {
                        return parseInt(data) === 1 ? `S/. ${parseFloat(row.monto_cancelar).toFixed(2)}` : 'S/. 0.00';
                    }
                },

                {
                    "data": "monto_cancelar",
                    "render": function(d) {
                        return `S/. ${parseFloat(d).toFixed(2)}`;
                    }
                },
                {
                    "data": "pago_medico_comision",
                    "render": function(d) {
                        return `S/. ${parseFloat(d).toFixed(2)}`;
                    }
                },

                // COLUMNA REDISEÑADA: INGRESO CLÍNICA (Monto Total - Comisión Doc)
                {
                    "data": null,
                    "render": function(data, type, row) {
                        let bruto = parseFloat(row.monto_cancelar) || 0;
                        let comision = parseFloat(row.pago_medico_comision) || 0;
                        let neto = bruto - comision;
                        return `<b class="text-success">S/. ${neto.toFixed(2)}</b>`;
                    }
                },
                {
                    "data": "egreso",
                    "render": function(d) {
                        return parseFloat(d) > 0 ? `<span class="text-danger font-weight-bold">S/. ${parseFloat(d).toFixed(2)}</span>` : `S/. 0.00`;
                    }
                },
                {
                    "data": "observaciones",
                    "render": function(d) {
                        return d ? d : '';
                    }
                }
            ],
            "order": [
                [0, "asc"]
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
            }
        });

        $('#filtroFecha').change(function() {
            tabla.ajax.reload();
        });

        // Buscar Paciente por DNI
        function buscarPacientePorDni() {
            let dni = $('#dni_paciente').val().trim();
            if (dni.length === 8) {
                $.post("controllers/ArqueoController.php", {
                    proceso: "BUSCAR_PACIENTE_LOCAL",
                    dni: dni
                }, function(respuesta) {
                    if (respuesta !== "0") {
                        let pac = JSON.parse(respuesta);
                        $('#nombre_paciente').val(pac.nombres + " " + pac.apellidos);
                        $('#celular_paciente').val(pac.telefono);
                        $('#edad_paciente').val(pac.edad);
                    }
                });
            }
        }
        $('#dni_paciente').keypress(function(e) {
            if (e.which == 13) {
                e.preventDefault();
                buscarPacientePorDni();
            }
        });
        $('#btnBuscarDni').click(buscarPacientePorDni);

        // Guardar
        $('#btnGuardarArqueo').click(function() {
            let datos = {
                proceso: "REGISTRAR_FILA",
                fecha_arqueo: $('#fecha_arqueo').val(),
                turno: $('#turno').val(),
                nombre_paciente: $('#nombre_paciente').val().trim(),
                dni_paciente: $('#dni_paciente').val().trim(),
                celular_paciente: $('#celular_paciente').val().trim(),
                edad_paciente: $('#edad_paciente').val(),
                tipo_paciente: $('#tipo_paciente').val(),
                descripcion_servicio: $('#descripcion_servicio').val().trim(),
                medico_tratante: $('#medico_tratante').val().trim(),

                es_consulta: $('#es_consulta').is(':checked') ? 1 : 0,
                es_laboratorio: $('#es_laboratorio').is(':checked') ? 1 : 0,
                es_rayos_x: $('#es_rayos_x').is(':checked') ? 1 : 0,
                es_ekg: $('#es_ekg').is(':checked') ? 1 : 0,
                es_riesgo_quirurgico: $('#es_riesgo_quirurgico').is(':checked') ? 1 : 0,
                es_ecografia: $('#es_ecografia').is(':checked') ? 1 : 0,

                monto_cancelar: $('#monto_cancelar').val(),
                pago_medico_comision: $('#pago_medico_comision').val(),
                egreso: $('#egreso').val(),
                metodo_pago: $('#metodo_pago').val(),
                observaciones: $('#observaciones').val().trim()
            };

            if (!datos.fecha_arqueo || !datos.turno || !datos.descripcion_servicio) {
                alert("La fecha, turno y la descripción son obligatorios.");
                return;
            }

            $.post("controllers/ArqueoController.php", datos, function(resultado) {
                if (resultado == "1") {
                    alert("Registro guardado con éxito.");
                    $('#addArqueoModal').modal('hide');
                    $('#formArqueo')[0].reset();
                    $('.txt-precio').prop('disabled', true).val('0.00');
                    $('#filtroFecha').val(datos.fecha_arqueo);
                    tabla.ajax.reload();
                } else {
                    alert("Error al procesar el registro.");
                }
            });
        });
    });
</script>