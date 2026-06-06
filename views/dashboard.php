<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />

<style>
    .fc-v-event {
        border-radius: 6px !important;
        padding: 5px !important;
        box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.1) !important;
        border: none !important;
    }

    .fc-event-title {
        font-weight: 600 !important;
        font-size: 0.85rem !important;
        color: white !important;
    }

    .fc {
        background: #ffffff !important;
    }

    .fc-event {
        border: none !important;
        border-radius: 10px !important;
        padding: 4px !important;
        cursor: pointer;
        transition: all .2s ease;
    }

    .fc-event:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, .25) !important;
    }

    .cita-card {
        font-size: 11px;
        line-height: 1.3;
    }

    .cita-paciente {
        font-weight: 700;
        font-size: 12px;
    }

    .cita-medico {
        opacity: .9;
    }

    .cita-especialidad {
        font-size: 10px;
        opacity: .85;
    }

    .fc-timegrid-event-harness {
        margin-right: 2px;
    }
</style>

<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Panel de Control Principal - Vital Salud</h4>
        <div class="btn-group ml-auto mb-2">
            <span class="badge badge-primary p-2 font-weight-bold">
                <i class="fa fa-calendar-alt"></i> Monitoreo Activo en Tiempo Real
            </span>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round border-left border-primary">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Citas de Hoy</p>
                                <h4 class="card-title" id="kpiTotalCitas">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round border-left border-success">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="fas fa-user-md"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Atendidos</p>
                                <h4 class="card-title text-success" id="kpiAtendidos">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round border-left border-warning">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-warning bubble-shadow-small">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">En Espera</p>
                                <h4 class="card-title text-warning" id="kpiPendientes">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round border-left border-info">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-info bubble-shadow-small">
                                <i class="fas fa-user-plus"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Pacientes Nuevos</p>
                                <h4 class="card-title text-info" id="kpiNuevosPacientes">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex align-items-center bg-white">
                    <h4 class="card-title font-weight-bold text-secondary">
                        <i class="fa fa-calendar-alt text-primary"></i> Agenda Diaria de Atenciones
                    </h4>
                    <div class="ml-auto">
                        <span class="badge badge-warning mr-1">Pendiente</span>
                        <span class="badge badge-info mr-1">Confirmada</span>
                        <span class="badge badge-success mr-1">Atendida</span>
                        <span class="badge badge-danger">Cancelada</span>
                    </div>
                </div>
                <div class="card-body">
                    <div id="calendar" style="min-height: 600px; background: #ffffff;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa fa-chart-line text-primary"></i> Flujo de Atenciones Mensuales</div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="min-height: 300px;">
                        <canvas id="chartMensual"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa fa-chart-pie text-info"></i> Especialidades Más Solicitadas</div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="min-height: 300px; position: relative;">
                        <canvas id="chartEspecialidades"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDetalleCita">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-calendar-check mr-2"></i>
                        Resumen de Cita Médica
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <div class="card border-left border-primary">
                                <div class="card-body">
                                    <h6 class="font-weight-bold text-primary">
                                        <i class="fa fa-user"></i> Paciente
                                    </h6>

                                    <h4 id="mPaciente"></h4>

                                    <div id="mDni"></div>
                                    <div id="mEdad"></div>
                                    <div id="mTelefono"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card border-left border-success">
                                <div class="card-body">
                                    <h6 class="font-weight-bold text-success">
                                        <i class="fa fa-user-md"></i> Atención
                                    </h6>

                                    <div id="mMedico"></div>
                                    <div id="mEspecialidad"></div>
                                    <div id="mHora"></div>
                                    <div id="mEstado"></div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="card">
                        <div class="card-header">
                            <strong>
                                <i class="fa fa-notes-medical"></i>
                                Motivo de Consulta
                            </strong>
                        </div>

                        <div class="card-body">
                            <p id="mMotivo" class="mb-0"></p>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

</div>

<script>
    (function() {
        // URLs de las librerías de FullCalendar v5
        const urlScriptCore = "https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js";
        const urlScriptLocale = "https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.js";

        // Función cargadora asíncrona de scripts
        function cargarScript(url, callback) {
            if (document.querySelector(`script[src="${url}"]`)) {
                if (callback) callback();
                return;
            }
            let script = document.createElement("script");
            script.type = "text/javascript";
            script.src = url;
            script.onload = function() {
                if (callback) callback();
            };
            document.head.appendChild(script);
        }

        // Inicializador de los KPIs y Gráficos con Chart.js
        function cargarKpisYGraficos() {
            $.post("controllers/contDashboard.php", {
                proceso: "CARGAR_DATOS"
            }, function(response) {
                let res = JSON.parse(response);

                // 1. Renderizar KPIs numéricos
                $('#kpiTotalCitas').text(res.tarjetas.total_citas);
                $('#kpiAtendidos').text(res.tarjetas.atendidas);
                $('#kpiPendientes').text(res.tarjetas.pendientes);
                $('#kpiNuevosPacientes').text(res.tarjetas.nuevos_pac);

                // 2. Procesar e Inicializar Gráfico Mensual
                let mesesNombres = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Set", "Oct", "Nov", "Dic"];
                let dataMensual = Array(12).fill(0);
                res.mensual.forEach(item => {
                    dataMensual[item.mes - 1] = parseInt(item.cantidad);
                });

                let ctxMensual = document.getElementById('chartMensual').getContext('2d');
                new Chart(ctxMensual, {
                    type: 'line',
                    data: {
                        labels: mesesNombres,
                        datasets: [{
                            label: "Citas Médicas",
                            borderColor: "#1d7af3",
                            pointBackgroundColor: "#1d7af3",
                            pointRadius: 4,
                            backgroundColor: "rgba(29, 122, 243, 0.1)",
                            fill: true,
                            borderWidth: 3,
                            data: dataMensual
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    stepSize: 1
                                }
                            }]
                        }
                    }
                });

                // 3. Procesar e Inicializar Gráfico Especialidades
                let etiquetasEsp = [];
                let valoresEsp = [];
                res.especialidades.forEach(item => {
                    etiquetasEsp.push(item.especialidad);
                    valoresEsp.push(parseInt(item.total_citas));
                });

                if (etiquetasEsp.length === 0) {
                    etiquetasEsp = ["Sin registros"];
                    valoresEsp = [0];
                }

                let ctxEsp = document.getElementById('chartEspecialidades').getContext('2d');
                new Chart(ctxEsp, {
                    type: 'doughnut',
                    data: {
                        labels: etiquetasEsp,
                        datasets: [{
                            data: valoresEsp,
                            backgroundColor: ["#1d7af3", "#fdaf4b", "#f3545d", "#155724", "#6861ce", "#31ce7f"]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                fontStyle: 'bold'
                            }
                        },
                        animation: {
                            animateScale: true,
                            animateRotate: true
                        }
                    }
                });
            });
        }

        // Inicializador del Calendario Dinámico conectado al Servidor
        function inicializarCalendarioReal() {
            const calendarEl = document.getElementById('calendar');
            if (!calendarEl || typeof FullCalendar === 'undefined') return;

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                locale: 'es',

                // FORMATO 12 HORAS (AM/PM)
                slotLabelFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                },

                eventTimeFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                },

                // Encabezados más bonitos
                dayHeaderFormat: {
                    weekday: 'short',
                    day: '2-digit',
                    month: 'short'
                },

                slotMinTime: '07:00:00',
                slotMaxTime: '20:00:00',
                allDaySlot: false,
                slotDuration: '00:30:00',

                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },

                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día'
                },



                // JALA LA DATA REAL DE LAS CITAS DESDE EL CONTROLADOR EN POST
                events: function(fetchInfo, successCallback, failureCallback) {
                    $.ajax({
                        url: 'controllers/contCita.php',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            proceso: 'LISTAR_CALENDARIO'
                        },
                        success: function(doc) {
                            // Enviar los eventos directamente a FullCalendar
                            successCallback(doc);
                        },
                        error: function() {
                            failureCallback();
                        }
                    });
                },

                eventClick: function(info) {

                    let p = info.event.extendedProps;

                    $('#mPaciente').text(info.event.title);

                    $('#mDni').html(
                        '<b>Documento:</b> ' +
                        (p.paciente_dni || 'No registrado')
                    );

                    $('#mEdad').html(
                        '<b>Edad:</b> ' +
                        (p.paciente_edad || 'No registrada')
                    );

                    $('#mTelefono').html(
                        '<b>Teléfono:</b> ' +
                        (p.paciente_telefono || 'No registrado')
                    );

                    $('#mMedico').html(
                        '<b>Médico:</b> ' +
                        (p.medico || 'No asignado')
                    );

                    $('#mEspecialidad').html(
                        '<b>Especialidad:</b> ' +
                        (p.especialidad || 'General')
                    );

                    $('#mHora').html(
                        '<b>Hora:</b> ' +
                        info.event.start.toLocaleTimeString('en-US', {
                            hour: 'numeric',
                            minute: '2-digit',
                            hour12: true
                        })
                    );

                    let badge = '';

                    switch (p.estado) {

                        case 'pendiente':
                            badge = '<span class="badge badge-warning p-2">Pendiente</span>';
                            break;

                        case 'confirmada':
                            badge = '<span class="badge badge-primary p-2">Confirmada</span>';
                            break;

                        case 'atendida':
                            badge = '<span class="badge badge-success p-2">Atendida</span>';
                            break;

                        case 'cancelada':
                            badge = '<span class="badge badge-danger p-2">Cancelada</span>';
                            break;

                        default:
                            badge = '<span class="badge badge-secondary p-2">Sin estado</span>';
                    }

                    $('#mEstado').html('<b>Estado:</b> ' + badge);

                    $('#mMotivo').text(
                        p.motivo || 'No se registró motivo de consulta.'
                    );

                    $('#modalDetalleCita').modal('show');
                },
                windowResize: function() {
                    calendar.updateSize();
                }
            });

            setTimeout(function() {
                calendar.render();
                calendar.updateSize();
            }, 150);
        }

        // Disparamos todo en orden asíncrono
        $(document).ready(function() {
            // Cargar primero los KPIs y gráficos
            cargarKpisYGraficos();

            // Cargar scripts en cascada e inicializar el calendario real
            cargarScript(urlScriptCore, function() {
                cargarScript(urlScriptLocale, function() {
                    inicializarCalendarioReal();
                });
            });
        });

    })();
</script>