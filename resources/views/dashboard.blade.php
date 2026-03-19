@extends('layouts.admin')

@section('content')
    <div class="card border-0 shadow-sm mb-4 rounded-3">
        <div class="card-body p-3 bg-white rounded">
            <form method="GET" class="row align-items-center m-0 justify-content-left">
                <div class="col-md-1">
                    <label class="form-label text-guinda2 fw-bold small mb-0">Filtrar por año:</label>
                </div>
                <div class="col-md-2">
                    <select name="anio" class="form-select form-select-sm border-guinda">
                        <option value="">Todos los años</option>
                        @php
                            $anioMinimo = 2026;
                            // Nos aseguramos de tomar el año actual (o 2026 como mínimo de seguridad)
                            $anioActual = max(date('Y'), $anioMinimo);
                        @endphp

                        {{-- Bucle inverso: del actual hacia atrás hasta 2026 --}}
                        @for ($i = $anioActual; $i >= $anioMinimo; $i--)
                            <option value="{{ $i }}" {{ request('anio') == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-auto">
                    {{-- Botón Filtrar con el estilo de Limpiar (outline) --}}
                    <button type="submit" class="btn btn-sm btn-outline-guinda rounded-pill shadow-sm fw-bold px-3">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-9">
                    <div class="hstack gap-9">
                        <div class="round-56 rounded-circle text-white d-flex align-items-center justify-content-center"
                            style="background-color: #9D2449;">
                            <i class="ti ti-clipboard-list fs-6"></i>
                        </div>
                        <div class="align-self-center">
                            <h3 class="mb-1 fs-6">{{ $totales['Pendientes'] }}</h3>
                            <span class="text-muted fw-bold" style="font-size: 0.8rem;">PENDIENTES</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-9">
                    <div class="hstack gap-9">
                        <div class="round-56 rounded-circle text-white d-flex align-items-center justify-content-center"
                            style="background-color: #977e5b;">
                            <i class="ti ti-tournament fs-6"></i>
                        </div>
                        <div class="align-self-center">
                            <h3 class="mb-1 fs-6">{{ $totales['Turnados'] }}</h3>
                            <span class="text-muted fw-bold" style="font-size: 0.8rem;">TURNADOS</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-9">
                    <div class="hstack gap-9">
                        <div class="round-56 rounded-circle text-white d-flex align-items-center justify-content-center"
                            style="background-color: #c3b08f;">
                            <i class="ti ti-check fs-6"></i>
                        </div>
                        <div class="align-self-center">
                            <h3 class="mb-1 fs-6">{{ $totales['Atendidos'] }}</h3>
                            <span class="text-muted fw-bold" style="font-size: 0.8rem;">ATENDIDOS</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-9">
                    <div class="hstack gap-9">
                        <div class="round-56 rounded-circle text-white d-flex align-items-center justify-content-center"
                            style="background-color: #8a8a8a;">
                            <i class="ti ti-circle-check fs-6"></i>
                        </div>
                        <div class="align-self-center">
                            <h3 class="mb-1 fs-6">{{ $totales['Concluidos'] }}</h3>
                            <span class="text-muted fw-bold" style="font-size: 0.8rem;">CONCLUIDOS</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div>
                            <h4 class="card-title fw-bold mb-0 text-guinda">Oficios por Sistema</h4>
                        </div>
                    </div>
                    @if (count($categoriasChart1) > 0)
                        <div id="chartOficios" style="min-height: 400px;"></div>
                    @else
                        <div class="text-center py-5 text-muted">Sin datos para graficar</div>
                    @endif
                </div>
            </div>
        </div>

        @if ($rol !== 'Capturista')
            <div class="col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div>
                                <h4 class="card-title fw-bold mb-0 text-guinda">Actividades por Responsable</h4>
                            </div>
                        </div>
                        @if (count($categoriasChart2) > 0)
                            <div id="chartActividades" style="min-height: 400px;"></div>
                        @else
                            <div class="text-center py-5 text-muted">Sin datos para graficar</div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        .text-guinda {
            color: #9D2449 !important;
        }

        .border-guinda {
            border-color: #9D2449 !important;
        }

        .btn-guinda {
            background-color: #9D2449;
            color: white;
            border: none;
            transition: 0.3s;
        }

        .btn-guinda:hover {
            background-color: #7a1c38;
            color: white;
            transform: translateY(-1px);
        }

        .btn-outline-guinda {
            color: #9D2449;
            border: 1px solid #9D2449;
            background-color: transparent;
            transition: 0.3s;
        }

        .btn-outline-guinda:hover {
            background-color: #9D2449;
            color: white;
        }
    </style>
@endsection

@push('scripts')
    <script src="{{ asset('materialpro/assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Tu nueva paleta institucional
            const paletaInstitucional = ['#9D2449', '#977e5b', '#c3b08f', '#8a8a8a', '#c9b088'];

            // --- Gráfica 1: Oficios (Sistemas vs Requerimientos) ---
            @if (count($categoriasChart1) > 0)
                var optionsOficios = {
                    series: @json($seriesChart1),
                    chart: {
                        type: 'bar',
                        height: 400,
                        stacked: true,
                        toolbar: {
                            show: false
                        },
                        fontFamily: 'inherit'
                    },
                    colors: paletaInstitucional,
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            borderRadius: 3,
                            barHeight: '55%',
                            dataLabels: {
                                total: {
                                    enabled: true,
                                    style: {
                                        fontWeight: 700
                                    }
                                }
                            }
                        }
                    },
                    xaxis: {
                        categories: @json($categoriasChart1),
                        labels: {
                            style: {
                                colors: '#8a8a8a'
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: '#8a8a8a'
                            }
                        }
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'center',
                        labels: {
                            colors: '#8a8a8a'
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    grid: {
                        borderColor: '#f1f1f1'
                    }
                };
                new ApexCharts(document.querySelector("#chartOficios"), optionsOficios).render();
            @endif

            // --- Gráfica 2: Actividades (Responsables vs Sistemas) ---
            @if ($rol !== 'Capturista' && count($categoriasChart2) > 0)
                var optionsActividades = {
                    series: @json($seriesChart2),
                    chart: {
                        type: 'bar',
                        height: 400,
                        stacked: true,
                        toolbar: {
                            show: false
                        },
                        fontFamily: 'inherit'
                    },
                    // Usamos la paleta en diferente orden para diferenciar
                    colors: ['#977e5b', '#c3b08f', '#9D2449', '#c9b088', '#8a8a8a'],
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            borderRadius: 3,
                            barHeight: '55%',
                            dataLabels: {
                                total: {
                                    enabled: true,
                                    style: {
                                        fontWeight: 700
                                    }
                                }
                            }
                        }
                    },
                    xaxis: {
                        categories: @json($categoriasChart2),
                        labels: {
                            style: {
                                colors: '#8a8a8a'
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: '#8a8a8a'
                            }
                        }
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'center',
                        labels: {
                            colors: '#8a8a8a'
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    grid: {
                        borderColor: '#f1f1f1'
                    }
                };
                new ApexCharts(document.querySelector("#chartActividades"), optionsActividades).render();
            @endif
        });
    </script>
@endpush