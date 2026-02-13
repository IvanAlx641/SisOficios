<style>
    /* FORZAR PAGINACIÓN GUINDA (#9D2449) */
    .pagination {
        --bs-pagination-active-bg: #9D2449;
        --bs-pagination-active-border-color: #9D2449;
        --bs-pagination-color: #9D2449;
        --bs-pagination-hover-color: #7d1d3a;
        --bs-pagination-focus-color: #7d1d3a;
        --bs-pagination-focus-box-shadow: 0 0 0 0.25rem rgba(157, 36, 73, 0.25);
    }

    /* Estilos de respaldo por si Bootstrap falla */
    .page-link {
        color: #9D2449 !important;
        background-color: #fff;
        border: 1px solid #dee2e6;
    }
    
    .page-item.active .page-link {
        background-color: #9D2449 !important;
        border-color: #9D2449 !important;
        color: #fff !important;
    }
    
    .page-link:hover {
        background-color: #f8d7da !important;
        color: #7d1d3a !important;
    }
</style>

@if ($paginator->hasPages())
    <nav class="d-flex justify-items-center justify-content-between">
        {{-- Versión Móvil --}}
        <div class="d-flex justify-content-between flex-fill d-sm-none">
            <ul class="pagination">
                {{-- Anterior --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">Anterior</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Anterior</a></li>
                @endif

                {{-- Siguiente --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Siguiente</a></li>
                @else
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">Siguiente</span></li>
                @endif
            </ul>
        </div>

        {{-- Versión Escritorio --}}
        <div class="d-none flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between">
            <div class="me-3">
                <p class="small text-muted mb-0">
                    Mostrando <span class="fw-bold">{{ $paginator->firstItem() }}</span> a <span class="fw-bold">{{ $paginator->lastItem() }}</span> de <span class="fw-bold">{{ $paginator->total() }}</span> resultados
                </p>
            </div>

            <div>
                <ul class="pagination mb-0">
                    {{-- Anterior --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true"><span class="page-link">Anterior</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Anterior</a></li>
                    @endif

                    {{-- Números --}}
                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Siguiente --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Siguiente</a></li>
                    @else
                        <li class="page-item disabled" aria-disabled="true"><span class="page-link">Siguiente</span></li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
@endif