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
                initialView: 'timeGridWeek', // Vista semanal por horas, excelente para clínica
                locale: 'es',
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
                    alert(
                        "Resumen de Cita Médica:\n\n" +
                        "Asunto: " + info.event.title + "\n" +
                        "Inicio: " + info.event.start.toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        }) + "\n" +
                        "Estado del registro: Activo"
                    );
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