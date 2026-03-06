@extends('layouts.admin')

@section('content')
<div class="container-fluid pt-3">
    
    <div class="card border-0 shadow-sm mb-5 rounded-3">
        <div class="card-header bg-light border-bottom-0 pt-4 pb-3 px-4 d-flex justify-content-between align-items-center rounded-top">
            <div>
                <h4 class="fw-bold text-guinda mb-0">Informes de actividades</h4>
            </div>
            <a href="{{ route('informes.actividades') }}" class="btn btn-guinda rounded-pill px-4 shadow-sm fw-bold">
                Limpiar
            </a>
        </div>

        <div class="card-body p-4 bg-white rounded-bottom">
            <form action="{{ route('informes.actividades') }}" method="GET" class="row align-items-end g-3">
                <div class="col-md-3">
                    <label class="form-label text-guinda2 fw-bold small">Fecha de actividad del:</label>
                    <input type="date" name="fecha_inicial" class="form-control border-guinda " value="{{ $request->fecha_inicial }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-guinda2 fw-bold small">al:</label>
                    <input type="date" name="fecha_final" class="form-control border-guinda " value="{{ $request->fecha_final }}">
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-2 text-end">
                    <button type="submit" class="btn btn-outline-guinda w-100 rounded-pill shadow-sm fw-bold">
                        Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4 pb-4">
        
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100 bg-white rounded-3">
                <div class="card-header bg-white border-bottom-0 pt-4 text-center">
                    <h5 class="fw-bold text-guinda mb-0">Estado General de Tareas</h5>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center p-4">
                    @if(count($seriesPieEstatus) > 0)
                        <div id="chartEstatus" class="w-100 d-flex justify-content-center"></div>
                    @else
                        <div class="text-muted fst-italic py-5">No hay tareas en proceso o atendidas en este rango.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100 bg-white rounded-3">
                <div class="card-header bg-white border-bottom-0 pt-4 text-center">
                    <h5 class="fw-bold text-guinda mb-1">Carga de Trabajo por Sistema</h5>
                    <small class="text-muted">Eje Y: Sistemas | Eje X: Total de Tareas</small>
                </div>
                <div class="card-body p-4">
                    @if(count($categoriasSis) > 0)
                        <div id="chartSis" style="min-height: 350px;"></div>
                    @else
                        <div class="text-muted text-center py-5">No hay datos para mostrar.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card border-0 shadow-sm h-100 bg-white rounded-3">
                <div class="card-header bg-white border-bottom-0 pt-4 text-center">
                    <h5 class="fw-bold text-guinda mb-1">Tendencia de Productividad Diaria</h5>
                    <small class="text-muted">Eje X: Fechas | Eje Y: Actividades Registradas</small>
                </div>
                <div class="card-body p-4">
                    @if(count($categoriasTiempo) > 0)
                        <div id="chartTiempo" style="min-height: 350px;"></div>
                    @else
                        <div class="text-muted text-center py-5">No hay actividad en estas fechas.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card border-0 shadow-sm h-100 bg-white rounded-3">
                <div class="card-header bg-white border-bottom-0 pt-4 text-center">
                    <h5 class="fw-bold text-guinda mb-1">Ranking de Responsables</h5>
                    <small class="text-muted">Eje Y: Responsables | Eje X: Tareas asignadas</small>
                </div>
                <div class="card-body p-4">
                    @if(count($categoriasResp) > 0)
                        <div id="chartResp" style="min-height: 350px;"></div>
                    @else
                        <div class="text-muted text-center py-5">No hay responsables asignados en este rango.</div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    /* PALETA INSTITUCIONAL */
    .border-guinda { border-color: #9D2449 !important; }
    
    /* BOTÓN LIMPIAR (GUINDA) */
    .btn-guinda { background-color: #9D2449; color: white; border: none; transition: 0.3s; }
    .btn-guinda:hover { background-color: #7a1c38; color: white; transform: translateY(-1px); }

    /* BOTÓN BUSCAR (ORO/BEIGE INSTITUCIONAL) */
    .btn-buscar { background-color: #c9b088; color: white; border: none; transition: 0.3s; }
    .btn-buscar:hover { background-color: #b09975; color: white; transform: translateY(-1px); }
</style>



<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {

    // Paleta Institucional Completa
    const paletaInstitucional = ['#9D2449', '#977e5b', '#c3b08f', '#8a8a8a', '#c9b088'];

    // 1. PASTEL: Estatus (Solo En Proceso y Atendidas)
    @if(count($seriesPieEstatus) > 0)
    var optionsEstatus = {
        series: @json($seriesPieEstatus),
        labels: @json($labelsPieEstatus),
        chart: { 
            type: 'pie', 
            width: '100%', 
            height: 350, 
            fontFamily: 'inherit',
            toolbar: { show: true, tools: { download: true } }
        },
        // Marrón para En Proceso, Oro para Atendidas (puedes ajustarlos si gustas)
        colors: ['#977e5b', '#c3b08f'], 
        legend: { position: 'bottom' },
        dataLabels: { enabled: true, dropShadow: { enabled: false } },
        stroke: { show: true, colors: '#ffffff', width: 2 }
    };
    new ApexCharts(document.querySelector("#chartEstatus"), optionsEstatus).render();
    @endif

    // 2. BARRAS HORIZONTALES: Sistemas (DISTRIBUIDO)
    @if(count($categoriasSis) > 0)
    var optionsSis = {
        series: [{ name: 'Total de Tareas', data: @json($seriesSis) }],
        chart: { 
            type: 'bar', 
            height: 350, 
            fontFamily: 'inherit',
            toolbar: { show: true, tools: { download: true, selection: false, zoom: false, pan: false } }
        },
        colors: paletaInstitucional, // <-- Usa toda la paleta
        plotOptions: { 
            bar: { 
                horizontal: true, 
                borderRadius: 3, 
                barHeight: '50%',
                distributed: true, // <-- ESTO HACE QUE CADA BARRA TENGA UN COLOR DISTINTO
                dataLabels: { position: 'bottom' }
            } 
        },
        dataLabels: { enabled: true, style: { colors: ['#ffffff'] } },
        xaxis: { categories: @json($categoriasSis), labels: { style: { colors: '#8a8a8a' }, formatter: function(val){ return Math.floor(val) } } },
        yaxis: { labels: { style: { colors: '#8a8a8a', fontWeight: 600 } } },
        legend: { show: false }, // Ocultamos la leyenda para que no se repitan los nombres de abajo
        grid: { borderColor: '#f1f1f1' }
    };
    new ApexCharts(document.querySelector("#chartSis"), optionsSis).render();
    @endif

    // 3. ÁREA/LÍNEAS: Evolución en el tiempo (SOLO 1 COLOR COMO PEDISTE)
    @if(count($categoriasTiempo) > 0)
    var optionsTiempo = {
        series: [{ name: 'Actividades Registradas', data: @json($seriesTiempo) }],
        chart: { 
            type: 'area', 
            height: 350, 
            fontFamily: 'inherit',
            toolbar: { show: true, tools: { download: true, selection: false, zoom: false, pan: false } }
        },
        colors: ['#c3b08f'], // Oro
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.7, opacityTo: 0.2, stops: [0, 90, 100] } },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        xaxis: { categories: @json($categoriasTiempo), labels: { style: { colors: '#8a8a8a' } } },
        yaxis: { labels: { style: { colors: '#8a8a8a' }, formatter: function(val){ return Math.floor(val) } } },
        grid: { borderColor: '#f1f1f1', strokeDashArray: 4 }
    };
    new ApexCharts(document.querySelector("#chartTiempo"), optionsTiempo).render();
    @endif

    // 4. BARRAS HORIZONTALES: Responsables (DISTRIBUIDO)
    @if(count($categoriasResp) > 0)
    var optionsResp = {
        series: [{ name: 'Tareas asignadas', data: @json($seriesResp) }],
        chart: { 
            type: 'bar', 
            height: 400, 
            fontFamily: 'inherit',
            toolbar: { show: true, tools: { download: true, selection: false, zoom: false, pan: false } }
        },
        // Alteramos un poco el orden para que la primera barra de esta gráfica no sea igual a la gráfica 2
        colors: ['#c9b088', '#9D2449', '#8a8a8a', '#c3b08f', '#977e5b'], 
        plotOptions: { 
            bar: { 
                horizontal: true, 
                borderRadius: 3, 
                barHeight: '50%', 
                distributed: true, // <-- ESTO HACE QUE CADA BARRA TENGA UN COLOR DISTINTO
                dataLabels: { position: 'bottom' } 
            } 
        },
        dataLabels: { enabled: true, style: { colors: ['#ffffff'] } },
        xaxis: { categories: @json($categoriasResp), labels: { style: { colors: '#8a8a8a' }, formatter: function(val){ return Math.floor(val) } } },
        yaxis: { labels: { style: { colors: '#8a8a8a', fontWeight: 600 } } },
        legend: { show: false }, // Ocultamos la leyenda
        grid: { borderColor: '#f1f1f1' }
    };
    new ApexCharts(document.querySelector("#chartResp"), optionsResp).render();
    @endif

});
</script>
@endsection