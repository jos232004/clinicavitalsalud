<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Reportes Mensuales</h4>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Filtro de Citas por Mes</h4>
        </div>
        <div class="card-body">

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="filtroMesAnio">Seleccione el Mes y Año</label>
                        <input type="month" id="filtroMesAnio" class="form-control" value="<?= date('Y-m'); ?>">
                    </div>
                </div>
                <div class="col-md-3 align-self-end">
                    <div class="form-group">
                        <button type="button" id="btnBuscarReporte" class="btn btn-success btn-block">
                            <i class="fa fa-chart-line"></i> Generar Reporte
                        </button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="tablaReporteCitas" class="display table table-striped table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Fecha/Hora</th>
                            <th>Paciente</th>
                            <th>N° Documento</th>
                            <th>Médico / Especialidad</th>
                            <th>Motivo</th>
                            <th>Estado</th>
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
        let tablaReporte;

        function cargarReporte() {
            let periodoSeleccionado = $('#filtroMesAnio').val();

            if ($.fn.DataTable.isDataTable('#tablaReporteCitas')) {
                tablaReporte.destroy();
            }

            tablaReporte = $('#tablaReporteCitas').DataTable({
                "ajax": {
                    "url": "controllers/contCita.php",
                    "type": "POST",
                    "data": {
                        "proceso": "REPORTAR_MES",
                        "periodo": periodoSeleccionado
                    },
                    "dataSrc": "aaData" // Adaptado a la estructura de tu controlador
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
                        "data": "title"
                    },
                    {
                        "data": "paciente_dni"
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return `<strong>${row.medico}</strong><br><small class="text-muted">${row.especialidad}</small>`;
                        }
                    },
                    {
                        "data": "motivo"
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
                    }
                ],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                }
            });
        }

        // Carga inicial automática
        cargarReporte();

        // Botón buscar filtra el mes seleccionado
        $('#btnBuscarReporte').click(function() {
            cargarReporte();
        });
    });
</script>