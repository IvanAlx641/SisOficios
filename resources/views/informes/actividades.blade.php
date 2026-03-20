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
                
                {{-- DDL de Unidad Administrativa --}}
                @if(in_array($rol, ['Administrador', 'Administrador TI', 'Admin TI', 'Analista']))
                <div class="col-md-4">
                    <label class="form-label text-guinda2 fw-bold small">Unidad Administrativa:</label>
                    <select name="unidad_administrativa_id" id="filtro_dirigido" class="form-select border-guinda">
                        <option value="">Todas las unidades</option>
                        @foreach($unidades as $id => $nombre)
                            <option value="{{ $id }}" {{ $request->unidad_administrativa_id == $id ? 'selected' : '' }}>
                                {{ $nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @else
                <div class="col-md-4"></div>
                @endif

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
                    <h5 class="fw-bold text-guinda mb-0">Estado general de actividades</h5>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center p-4">
                    @if(count($seriesPieEstatus) > 0)
                        <div id="chartEstatus" class="w-100 d-flex justify-content-center"></div>
                    @else
                        <div class="text-muted text-center py-5">No se encontró información con los criterios de búsqueda seleccionados.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100 bg-white rounded-3">
                <div class="card-header bg-white border-bottom-0 pt-4 text-center">
                    <h5 class="fw-bold text-guinda mb-0">Carga de trabajo por sistema</h5>
                </div>
                <div class="card-body p-4">
                    @if(count($categoriasSis) > 0)
                        <div id="chartSis" style="min-height: 350px;"></div>
                    @else
                        <div class="text-muted text-center py-5">No se encontró información con los criterios de búsqueda seleccionados.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card border-0 shadow-sm h-100 bg-white rounded-3">
                <div class="card-header bg-white border-bottom-0 pt-4 text-center">
                    <h5 class="fw-bold text-guinda mb-0">Tendencia de productividad diaria</h5>
                </div>
                <div class="card-body p-4">
                    @if(count($categoriasTiempo) > 0)
                        <div id="chartTiempo" style="min-height: 350px;"></div>
                    @else
                        <div class="text-muted text-center py-5">No se encontró información con los criterios de búsqueda seleccionados.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card border-0 shadow-sm h-100 bg-white rounded-3">
                <div class="card-header bg-white border-bottom-0 pt-4 text-center">
                    <h5 class="fw-bold text-guinda mb-0"> Responsables</h5>
                </div>
                <div class="card-body p-4">
                    @if(count($categoriasResp) > 0)
                        <div id="chartResp" style="min-height: 350px;"></div>
                    @else
                        <div class="text-muted text-center py-5">No se encontró información con los criterios de búsqueda seleccionados.</div>
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
    .btn-outline-guinda { color: #9D2449; border: 1px solid #9D2449; background-color: transparent; transition: 0.3s; }
    .btn-outline-guinda:hover { background-color: #9D2449; color: white; }

    /* ESTILOS PARA EL BUSCADOR DDL */
    .searchable-dropdown-wrapper { position: relative; width: 100%; }
    .searchable-trigger { background-color: #fff; text-align: left; padding-right: 2rem; position: relative; }
    .searchable-trigger::after {
        content: ""; position: absolute; top: 50%; right: 1rem; transform: translateY(-50%);
        border-top: 0.3em solid; border-right: 0.3em solid transparent; border-bottom: 0; border-left: 0.3em solid transparent;
    }
    .searchable-menu {
        display: none; position: absolute; top: 100%; left: 0; width: 100%; background-color: #ffffff;
        border: 1px solid #9D2449; border-radius: 0.25rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        margin-top: 4px; padding: 8px; z-index: 1050;
    }
    .searchable-menu.show { display: block; }
    .searchable-option { border-radius: 4px; transition: background-color 0.2s; }

    /* OCULTAR DESCARGA CSV EN APEXCHARTS */
    .exportCSV {
        display: none !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {

    const paletaInstitucional = ['#9D2449', '#977e5b', '#c3b08f', '#8a8a8a', '#c9b088'];

    const apexLocales = [{
        "name": "es",
        "options": {
            "toolbar": {
                "exportToSVG": "Descargar SVG",
                "exportToPNG": "Descargar Imagen",
                "menu": "Descargar",
                "selection": "Selección",
                "selectionZoom": "Acercar Selección",
                "zoomIn": "Acercar", "zoomOut": "Alejar", "pan": "Navegación", "reset": "Reiniciar Zoom"
            }
        }
    }];
    const toolbarConfig = { show: true, export: { csv: { show: false }, svg: { filename: 'grafica_actividades' }, png: { filename: 'grafica_actividades' } } };

    // 1. PASTEL: Estatus
    @if(count($seriesPieEstatus) > 0)
    var optionsEstatus = {
        series: @json($seriesPieEstatus),
        labels: @json($labelsPieEstatus),
        chart: { type: 'pie', width: '100%', height: 350, fontFamily: 'inherit', locales: apexLocales, defaultLocale: 'es', toolbar: toolbarConfig },
        colors: ['#977e5b', '#c3b08f'], 
        legend: { position: 'bottom' },
        dataLabels: { enabled: true, dropShadow: { enabled: false } },
        stroke: { show: true, colors: '#ffffff', width: 2 }
    };
    new ApexCharts(document.querySelector("#chartEstatus"), optionsEstatus).render();
    @endif

    // 2. BARRAS HORIZONTALES APILADAS: Sistemas vs Tipo Requerimiento
    @if(count($categoriasSis) > 0)
    var seriesSisData = @json($seriesSis);
    // Calculamos el valor máximo para evitar decimales
    var maxSis = Math.max(...seriesSisData.map(s => Math.max(...s.data)));
    var tickSis = (maxSis > 0 && maxSis < 5) ? maxSis : 5;

    var optionsSis = {
        series: seriesSisData, 
        chart: { type: 'bar', height: 350, stacked: true, fontFamily: 'inherit', locales: apexLocales, defaultLocale: 'es', toolbar: toolbarConfig },
        colors: paletaInstitucional,
        plotOptions: { bar: { horizontal: true, borderRadius: 3, barHeight: '50%', dataLabels: { total: { enabled: true, style: { fontWeight: 700 } } } } },
        xaxis: { 
            categories: @json($categoriasSis), 
            title: { text: 'Tipo de requerimiento', style: { fontWeight: 600, color: '#9D2449' } },
            tickAmount: tickSis, // 🚨 ESTO EVITA LOS DECIMALES 🚨
            labels: { style: { colors: '#8a8a8a' }, formatter: function(val){ return parseInt(val); } } 
        },
        yaxis: { title: { text: 'Sistema', style: { fontWeight: 600, color: '#9D2449' } }, labels: { style: { colors: '#8a8a8a', fontWeight: 600 } } },
        legend: { position: 'top', horizontalAlign: 'center', labels: { colors: '#8a8a8a' } },
        grid: { borderColor: '#f1f1f1' }
    };
    new ApexCharts(document.querySelector("#chartSis"), optionsSis).render();
    @endif

    // 3. ÁREA/LÍNEAS: Evolución en el tiempo
    @if(count($categoriasTiempo) > 0)
    var serieTiempoData = @json($seriesTiempo);
    var maxTiempo = Math.max(...serieTiempoData);
    var tickTiempo = (maxTiempo > 0 && maxTiempo < 5) ? maxTiempo : 5;

    var optionsTiempo = {
        series: [{ name: 'Actividades Registradas', data: serieTiempoData }],
        chart: { type: 'area', height: 350, fontFamily: 'inherit', locales: apexLocales, defaultLocale: 'es', toolbar: toolbarConfig },
        colors: ['#c3b08f'], fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.7, opacityTo: 0.2, stops: [0, 90, 100] } },
        dataLabels: { enabled: false }, stroke: { curve: 'smooth', width: 3 },
        xaxis: { categories: @json($categoriasTiempo), title: { text: 'Fecha', style: { fontWeight: 600, color: '#9D2449' } }, labels: { style: { colors: '#8a8a8a' } } },
        yaxis: { 
            title: { text: 'No. de actividades registradas', style: { fontWeight: 600, color: '#9D2449' } },
            tickAmount: tickTiempo, // 🚨 ESTO EVITA LOS DECIMALES 🚨
            labels: { style: { colors: '#8a8a8a' }, formatter: function(val){ return parseInt(val); } } 
        },
        grid: { borderColor: '#f1f1f1', strokeDashArray: 4 }
    };
    new ApexCharts(document.querySelector("#chartTiempo"), optionsTiempo).render();
    @endif

    // 4. BARRAS HORIZONTALES: Responsables
    @if(count($categoriasResp) > 0)
    var serieRespData = @json($seriesResp);
    var maxResp = Math.max(...serieRespData);
    var tickResp = (maxResp > 0 && maxResp < 5) ? maxResp : 5;

    var optionsResp = {
        series: [{ name: 'Actividades asignadas', data: serieRespData }],
        chart: { type: 'bar', height: 400, fontFamily: 'inherit', locales: apexLocales, defaultLocale: 'es', toolbar: toolbarConfig },
        colors: ['#c9b088', '#9D2449', '#8a8a8a', '#c3b08f', '#977e5b'], 
        plotOptions: { bar: { horizontal: true, borderRadius: 3, barHeight: '50%', distributed: true, dataLabels: { position: 'bottom' } } },
        dataLabels: { enabled: true, style: { colors: ['#ffffff'] } },
        xaxis: { 
            categories: @json($categoriasResp), 
            title: { text: 'No. de actividades', style: { fontWeight: 600, color: '#9D2449' } },
            tickAmount: tickResp, // 🚨 ESTO EVITA LOS DECIMALES 🚨
            labels: { style: { colors: '#8a8a8a' }, formatter: function(val){ return parseInt(val); } } 
        },
        yaxis: { title: { text: 'Responsables', style: { fontWeight: 600, color: '#9D2449' } }, labels: { style: { colors: '#8a8a8a', fontWeight: 600 } } }, 
        legend: { show: false }, grid: { borderColor: '#f1f1f1' }
    };
    new ApexCharts(document.querySelector("#chartResp"), optionsResp).render();
    @endif

    // SCRIPT DEL BUSCADOR
    function convertirSelectABuscador(idSelect) {
        const originalSelect = document.getElementById(idSelect);
        if (!originalSelect) return;

        const wrapperPrevio = originalSelect.parentNode.querySelector('.searchable-dropdown-wrapper');
        if (wrapperPrevio) wrapperPrevio.remove();

        const wrapper = document.createElement('div');
        wrapper.className = 'searchable-dropdown-wrapper';

        const trigger = document.createElement('button');
        trigger.className = 'form-select searchable-trigger border-guinda text-start text-truncate';
        trigger.type = 'button';

        const selectedOption = originalSelect.options[originalSelect.selectedIndex];
        trigger.textContent = selectedOption ? selectedOption.text : 'Todas las unidades';

        const menu = document.createElement('div');
        menu.className = 'searchable-menu';
        
        const inputSearch = document.createElement('input');
        inputSearch.className = 'form-control mb-2 border-guinda';
        inputSearch.type = 'text';
        inputSearch.placeholder = 'Buscar...';
        inputSearch.onclick = function(e) { e.stopPropagation(); };

        const optionsList = document.createElement('div');
        optionsList.className = 'searchable-options';
        optionsList.style.maxHeight = '200px';
        optionsList.style.overflowY = 'auto';

        function poblarOpciones() {
            optionsList.innerHTML = '';
            Array.from(originalSelect.options).forEach(option => {
                const item = document.createElement('div');
                item.className = 'searchable-option p-2';
                item.style.cursor = 'pointer';
                item.textContent = option.text;

                item.addEventListener('mouseover', () => item.style.backgroundColor = '#f8f9fa');
                item.addEventListener('mouseout', () => item.style.backgroundColor = 'transparent');
                item.addEventListener('click', () => {
                    originalSelect.value = option.value;
                    trigger.textContent = option.text;
                    menu.classList.remove('show');
                    inputSearch.value = '';
                    filtrarOpciones('');
                    originalSelect.dispatchEvent(new Event('change'));
                });
                optionsList.appendChild(item);
            });
        }
        poblarOpciones();

        function filtrarOpciones(texto) {
            const items = optionsList.querySelectorAll('.searchable-option');
            const filtro = texto.toLowerCase();
            items.forEach(item => {
                const coincide = item.textContent.toLowerCase().includes(filtro);
                item.style.display = coincide ? 'block' : 'none';
            });
        }

        inputSearch.addEventListener('keyup', (e) => filtrarOpciones(e.target.value));

        trigger.addEventListener('click', (e) => {
            document.querySelectorAll('.searchable-menu').forEach(m => { if (m !== menu) m.classList.remove('show'); });
            menu.classList.toggle('show');
            if (menu.classList.contains('show')) setTimeout(() => inputSearch.focus(), 100);
        });

        document.addEventListener('click', (e) => { if (!wrapper.contains(e.target)) menu.classList.remove('show'); });

        menu.appendChild(inputSearch);
        menu.appendChild(optionsList);
        wrapper.appendChild(trigger);
        wrapper.appendChild(menu);
        originalSelect.parentNode.insertBefore(wrapper, originalSelect.nextSibling);
        originalSelect.style.display = 'none';
    }

    convertirSelectABuscador('filtro_dirigido');

});
</script>
@endsection