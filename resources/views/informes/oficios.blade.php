@extends('layouts.admin')

@section('content')
<div class="container-fluid pt-3">
    
    <div class="card border-0 shadow-sm mb-5 rounded-3">
        
        <div class="card-header bg-light border-bottom-0 pt-4 pb-3 px-4 d-flex justify-content-between align-items-center rounded-top">
            <div>
                <h4 class="fw-bold text-guinda mb-0">Informes de oficios</h4>
            </div>
            <a href="{{ route('informes.oficios') }}" class="btn btn-guinda rounded-pill px-4 shadow-sm fw-bold">
                Limpiar
            </a>
        </div>

        <div class="card-body p-4 bg-white rounded-bottom">
            <form action="{{ route('informes.oficios') }}" method="GET" class="row align-items-end g-3">
                
                <div class="col-md-3">
                    <label class="form-label text-guinda2 fw-bold small">Fecha de recepción del:</label>
                    <input type="date" name="fecha_inicial" class="form-control border-guinda " value="{{ $request->fecha_inicial }}">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label text-guinda2 fw-bold small">al:</label>
                    <input type="date" name="fecha_final" class="form-control border-guinda" value="{{ $request->fecha_final }}">
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
                    <h5 class="fw-bold text-guinda mb-0">Por tipo de requerimiento</h5>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center p-4">
                    @if(count($seriesPieReq) > 0)
                        <div id="chartPieReq" class="w-100 d-flex justify-content-center"></div>
                    @else
                        <div class="text-muted fst-italic py-5">No hay oficios en este rango de fechas.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100 bg-white rounded-3">
                <div class="card-header bg-white border-bottom-0 pt-4 text-center">
                    <h5 class="fw-bold text-guinda mb-1">Requerimientos por Sistema</h5>
                    <small class="text-muted">Eje Y: Sistemas | Eje X: Requerimientos</small>
                </div>
                <div class="card-body p-4">
                    @if(count($categoriasSis) > 0)
                        <div id="chartSisReq" style="min-height: 380px;"></div>
                    @else
                        <div class="text-muted text-center py-5">No hay datos para mostrar.</div>
                    @endif
                </div>
            </div>
            
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100 bg-white rounded-3">
                <div class="card-header bg-white border-bottom-0 pt-4 text-center">
                    <h5 class="fw-bold text-guinda mb-0">Por estatus de oficio</h5>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center p-4">
                    @if(count($seriesPieEstatus) > 0)
                        <div id="chartPieEstatus" class="w-100 d-flex justify-content-center"></div>
                    @else
                        <div class="text-muted fst-italic py-5">No hay oficios en este rango de fechas.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100 bg-white rounded-3">
                <div class="card-header bg-white border-bottom-0 pt-4 text-center">
                    <h5 class="fw-bold text-guinda mb-1">Requerimientos por Responsable</h5>
                    <small class="text-muted">Eje Y: Responsables | Eje X: Requerimientos</small>
                </div>


                <div class="card-body p-4">
                    @if(count($categoriasResp) > 0)
                        <div id="chartRespReq" style="min-height: 380px;"></div>
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
    
    .bg-guinda { background-color: #9D2449 !important; }
    .border-guinda { border-color: #9D2449 !important; }
    
    /* BOTÓN SÓLIDO (LIMPIAR) */
    .btn-guinda { background-color: #9D2449; color: white; border: none; transition: 0.3s; }
    .btn-guinda:hover { background-color: #7a1c38; color: white; transform: translateY(-1px); }

    /* BOTÓN DELINEADO (BUSCAR) */
    .btn-outline-guinda { color: #9D2449; border: 1px solid #9D2449; background-color: transparent; transition: 0.3s; }
    .btn-outline-guinda:hover { background-color: #9D2449; color: white; }
</style>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {

    // Paleta Institucional Exacta
    const paletaInstitucional = ['#9D2449', '#977e5b', '#c3b08f', '#8a8a8a', '#c9b088'];

    // 1. PASTEL: Tipo Req
    @if(count($seriesPieReq) > 0)
    var optionsPieReq = {
        series: @json($seriesPieReq),
        labels: @json($labelsPieReq),
        chart: { 
            type: 'pie', 
            width: '100%', 
            height: 350, 
            fontFamily: 'inherit'
        },
        colors: paletaInstitucional,
        legend: { position: 'bottom' },
        dataLabels: { enabled: true, dropShadow: { enabled: false } },
        stroke: { show: true, colors: '#ffffff', width: 2 }
    };
    new ApexCharts(document.querySelector("#chartPieReq"), optionsPieReq).render();
    @endif

    // 2. PASTEL: Estatus
    @if(count($seriesPieEstatus) > 0)
    const estatusColors = @json($labelsPieEstatus).map(estatus => {
        if(estatus === 'CONCLUIDO' || estatus === 'ATENDIDO') return '#8a8a8a';
        if(estatus === 'TURNADO') return '#977e5b';
        if(estatus === 'PENDIENTE') return '#9D2449';
        return '#c3b08f';
    });

    var optionsPieEstatus = {
        series: @json($seriesPieEstatus),
        labels: @json($labelsPieEstatus),
        chart: { 
            type: 'pie', 
            width: '100%', 
            height: 350, 
            fontFamily: 'inherit'
        },
        colors: estatusColors.length > 0 ? estatusColors : paletaInstitucional,
        legend: { position: 'bottom' },
        dataLabels: { enabled: true, dropShadow: { enabled: false } },
        stroke: { show: true, colors: '#ffffff', width: 2 }
    };
    new ApexCharts(document.querySelector("#chartPieEstatus"), optionsPieEstatus).render();
    @endif

    // 3. BARRAS: Sistemas vs Req
    @if(count($categoriasSis) > 0)
    var optionsSisReq = {
        series: @json($seriesSisReq),
        chart: { 
            type: 'bar', 
            height: 400, 
            stacked: true, 
            fontFamily: 'inherit',
            toolbar: { 
                show: true,
                tools: { download: true, selection: false, zoom: false, pan: false }
            } 
        },
        colors: paletaInstitucional,
        plotOptions: {
            bar: { horizontal: true, borderRadius: 3, barHeight: '55%', dataLabels: { total: { enabled: true, style: { fontWeight: 700 } } } }
        },
        xaxis: { categories: @json($categoriasSis), labels: { style: { colors: '#8a8a8a' }, formatter: function(val){ return Math.floor(val) } } },
        yaxis: { labels: { style: { colors: '#8a8a8a', fontSize: '11px', fontWeight: 600 } } },
        legend: { position: 'top', horizontalAlign: 'center', labels: { colors: '#8a8a8a' } },
        fill: { opacity: 1 },
        grid: { borderColor: '#f1f1f1' }
    };
    new ApexCharts(document.querySelector("#chartSisReq"), optionsSisReq).render();
    @endif

    // 4. BARRAS: Responsables vs Req
    @if(count($categoriasResp) > 0)
    var optionsRespReq = {
        series: @json($seriesRespReq),
        chart: { 
            type: 'bar', 
            height: 400, 
            stacked: true, 
            fontFamily: 'inherit',
            toolbar: { 
                show: true,
                tools: { download: true, selection: false, zoom: false, pan: false }
            } 
        },
        colors: ['#977e5b', '#c3b08f', '#9D2449', '#c9b088', '#8a8a8a'],
        plotOptions: {
            bar: { horizontal: true, borderRadius: 3, barHeight: '55%', dataLabels: { total: { enabled: true, style: { fontWeight: 700 } } } }
        },
        xaxis: { categories: @json($categoriasResp), labels: { style: { colors: '#8a8a8a' }, formatter: function(val){ return Math.floor(val) } } },
        yaxis: { labels: { style: { colors: '#8a8a8a', fontSize: '11px', fontWeight: 600 } } },
        legend: { position: 'top', horizontalAlign: 'center', labels: { colors: '#8a8a8a' } },
        fill: { opacity: 1 },
        grid: { borderColor: '#f1f1f1' }
    };
    new ApexCharts(document.querySelector("#chartRespReq"), optionsRespReq).render();
    @endif

});
</script>
@endsection
