@extends('layouts.app')

@section('content')
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="z-index: 10;">
    <div class="mode-switcher-container">
        <div class="segmented-control">
            <button id="mode-individual" onclick="funcHide()" class="segment active">
                <i class="fas fa-th-list"></i>
                <span>Individual</span>
            </button>
            <button id="mode-lote" onclick="funcShow()" class="segment">
                <i class="fas fa-file-excel"></i>
                <span>Lote (Excel)</span>
            </button>
        </div>
    </div>
</div>

<div class="contFile hidden">
    <div class="row d-flex justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="import-card import-card-delete">
            <div class="avatar">
                <img src="{{ asset('img/Logo_Placapp.png') }}" alt="Avatar">
            </div>
            <form method="POST" action="{{ route('delete.excel') }}" enctype="multipart/form-data" id="imageUploadForm" class="import-form">
                @csrf
                <div class="import-zone">
                    <span class="import-help-text" id="helpText">Arrastre Archivo Aquí</span>
                    <input type='file' name="excel" id="file-delete" class="import-input" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, text/csv" required />
                    <div id="uploadedImg" class="import-preview">
                        <span class="unveil"></span>
                    </div>
                </div>
                <div class="import-actions">
                    <button type="submit" class="import-btn import-btn-delete">Subir Archivo</button>
                </div>
            </form>
            
            @if(session('success'))
                <div class="alert alert-success mt-3">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger mt-3">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Vista de tabla de placas (Liberación Vertical) -->
<div class="row justify-content-center contTable mt-3" id="plateTableContainer" style="margin-top: 0px !important; margin-bottom: 80px !important; display: flex; align-items: stretch;">
    <div class="col-lg-8 col-md-10 col-sm-12 d-flex flex-column">
        <div class="import-card-table">
            {{-- El título se elimina por redundancia según solicitud del usuario --}}

            <div class="dataTable_wrapper">
                <table class="table" id="dataTables-placas" width="100%">
                    <thead>
                        <tr>
                            <th>Placa</th>
                            <th>Fecha</th>
                            <th style="text-align:center !important">Acción</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
                        </tr>
                    </thead>
                </table>
            </div>
            
            <!-- Alerta Nativa de Borrado (iOS Style) -->
            <div class="modal fade" id="null_modal" role="dialog">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 280px; margin: auto;">
                    <div class="modal-content ios-alert-content">
                        <div class="ios-alert-header">
                            <h4 class="ios-alert-title">¿Eliminar Placa?</h4>
                            <p class="ios-alert-message">Esta acción no se puede deshacer de forma sencilla.</p>
                        </div>
                        <div class="modal-body ios-alert-body" style="padding: 10px 15px 20px 15px;">
                            <!-- El contenido simplificado se inyectará aquí -->
                        </div>
                        <div class="ios-alert-actions">
                            <button type="button" id="null-confirm" class="ios-alert-btn ios-alert-btn-danger" data-dismiss="modal">Eliminar</button>
                            <button type="button" class="ios-alert-btn ios-alert-btn-cancel" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .contFile.hidden {
        display: none;
    }

    /* Segmented Control iOS Style */
    .mode-switcher-container {
        display: flex;
        justify-content: center;
        margin-top: 50px; /* Aumentado para no chocar con el botn Salir en Desktop */
        /* margin-bottom: 5px; */
        z-index: 100;
        position: relative;
    }

    @media (max-width: 768px) {
        .mode-switcher-container {
            margin-top: 75px; /* Más espacio en móvil para evitar superposición con botones superiores */
        }
    }

    .segmented-control {
        display: flex;
        background-color: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        padding: 0px;
        border-radius: 12px;
        width: 100%;
        max-width: 320px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .segment {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: none;
        background: transparent;
        color: rgba(255, 255, 255, 0.6);
        padding: 8px 0;
        font-size: 13px;
        font-weight: 500;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
    }

    .segment i {
        font-size: 14px;
        opacity: 0.8;
    }

    .segment.active {
        background-color: rgba(255, 255, 255, 0.15) !important;
        color: #fff !important;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
    }

    .segment:not(.active):hover {
        background-color: rgba(255, 255, 255, 0.05);
        color: rgba(255, 255, 255, 0.9);
    }

    /* Rediseño de Tabla iOS Style */
    .import-card-table {
        background-color: rgba(28, 28, 30, 0.7);
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 10px 10px 5px 10px; /* Reducción de padding inferior */
        margin-top: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        display: flex;
        flex-direction: column;
        flex: 1; /* Ocupar todo el espacio del padre liberado */
    }

    .table-header-ios {
        text-align: center;
        color: rgba(255, 255, 255, 0.4);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1px;
        margin-bottom: 15px;
        text-transform: uppercase;
    }

    /* Estilos para DataTables Internos */
    .dataTables_info {
        flex: 0 0 auto !important;
        margin-top: 5px !important; 
        padding-top: 5px !important;
        padding-bottom: 0 !important;
        font-size: 11px !important;
        opacity: 0.7;
        text-align: center !important;
    }

    .dataTables_paginate {
        flex: 0 0 auto !important;
        margin-top: 8px !important; /* Compactado */
        padding-top: 0px !important;
        padding-bottom: 5px !important; /* Compactado */
    }

    .dataTables_wrapper .dataTable thead th,
    .dataTables_wrapper .dataTable tbody td {
        box-sizing: border-box !important;
    }

    #dataTables-placas,
    .dataTables_scrollHead table,
    .dataTables_scrollBody table {
        border: none !important;
        background: transparent !important;
        margin-top: 10px !important;
        width: 100% !important;
        table-layout: fixed !important; /* Forzar alineación de columnas */
    }

    #dataTables-placas thead th {
        background: transparent !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        color: rgba(255, 255, 255, 0.6) !important;
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 600;
        padding: 12px 5px;
    }

    #dataTables-placas tbody tr {
        background: transparent !important;
        transition: background 0.2s;
    }

    #dataTables-placas tbody tr:hover {
        background: rgba(255, 255, 255, 0.05) !important;
    }

    #dataTables-placas tbody td {
        border: none !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        color: rgba(255, 255, 255, 0.9) !important;
        padding: 14px 5px;
        font-size: 14px;
        vertical-align: middle;
        word-break: break-word !important; /* Evita que el texto largo empuje la tabla */
        overflow-wrap: anywhere !important;
    }

    /* Ajuste de anchos de columna (3 columnas visibles) */
    #dataTables-placas th:nth-child(1), #dataTables-placas td:nth-child(1) { width: 35%; text-align: left; }  /* Placa */
    #dataTables-placas th:nth-child(2), #dataTables-placas td:nth-child(2) { width: 45%; text-align: left; }  /* Fecha */
    #dataTables-placas th:nth-child(3), #dataTables-placas td:nth-child(3) { width: 20%; text-align: center !important; padding-right: 5px !important; } /* Acción Centrada */

    @media (max-width: 480px) {
        #dataTables-placas tbody td {
            font-size: 13px;
            padding: 12px 4px;
        }
    }

    /* Buscador DataTables estilo iOS */
    .dataTables_filter {
        float: none !important;
        text-align: center !important;
        margin-bottom: 15px;
    }

    .dataTables_filter label {
        width: 100%;
        color: transparent; /* Ocultar el texto "Buscar:" */
        position: relative;
    }

    .dataTables_filter input {
        width: 100% !important;
        max-width: 320px;
        background: rgba(255, 255, 255, 0.08) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 10px !important;
        color: #fff !important;
        padding: 8px 12px 8px 35px !important; /* Espacio para el icono */
        font-size: 14px;
        outline: none;
        transition: all 0.3s;
    }

    .dataTables_filter label:after {
        content: '\f002'; /* Lupa FontAwesome */
        font-family: 'FontAwesome';
        position: absolute;
        left: calc(50% - 145px); /* Ajustado para el input de 320px */
        top: 50%;
        transform: translateY(-50%);
        color: rgba(255, 255, 255, 0.4);
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .dataTables_filter label:after {
            left: 25px; /* Ajuste para móvil cuando el input es full width */
        }
        .dataTables_filter input {
            max-width: 100%;
        }
    }

    /* Ocultar barra de scroll tosca de Windows */
    .dataTables_scrollBody::-webkit-scrollbar {
        width: 4px;
    }
    .dataTables_scrollBody::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
    }

    /* RESET NUCLEAR: Eliminar rastro de Bootstrap (Cuadros blancos/cuadrados) */
    .contTable .pagination,
    .contTable .page-item,
    .contTable .page-link,
    .dataTables_wrapper .dataTables_paginate {
        background: transparent !important;
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    /* PAGINACIÓN CÁPSULA DETALLADA: Diseño de 3 Bloques */
    .dataTables_wrapper .dataTables_paginate {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        gap: 15px !important;
        padding-bottom: 5px !important; /* Compactado */
    }

    /* 1. BOTONES AZULES (Independientes) */
    .dataTables_wrapper .dataTables_paginate .paginate_button.previous,
    .dataTables_wrapper .dataTables_paginate .paginate_button.next,
    .contTable .page-item.previous .page-link,
    .contTable .page-item.next .page-link {
        background: #007aff !important; /* Azul iOS */
        border-radius: 50% !important;
        width: 44px !important;
        height: 44px !important;
        min-width: 44px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        color: #fff !important;
        cursor: pointer !important;
        box-shadow: 0 4px 15px rgba(0, 122, 255, 0.4) !important;
        transition: all 0.3s ease !important;
        font-size: 0 !important; /* Ocultar texto nativo */
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.previous::before,
    .contTable .page-item.previous .page-link::before { content: '\f053'; font-family: 'FontAwesome'; font-size: 16px !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.next::before,
    .contTable .page-item.next .page-link::before { content: '\f054'; font-family: 'FontAwesome'; font-size: 16px !important; }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled),
    .contTable .page-link:hover { transform: scale(1.08); color: #fff !important; }

    /* 2. LA CÁPSULA (Píldora Central) */
    .dataTables_wrapper .dataTables_paginate span,
    .contTable .pagination {
        background: rgba(255, 255, 255, 0.08) !important;
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 30px !important;
        padding: 5px 15px !important;
        display: flex !important;
        align-items: center !important;
        gap: 5px !important;
    }

    /* Estilo de los Números dentro de la Cápsula (Elásticos para legibilidad) */
    .dataTables_wrapper .dataTables_paginate span .paginate_button,
    .contTable .pagination .page-item .page-link {
        color: rgba(255, 255, 255, 0.5) !important;
        background: transparent !important;
        border: none !important;
        font-size: 14px !important;
        font-weight: 500 !important;
        width: auto !important; /* Ancho automático para números grandes como 7058 */
        min-width: 36px !important; 
        height: 36px !important;
        padding: 0 10px !important; /* Espacio lateral para que el número respire */
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 18px !important;
        transition: all 0.2s ease !important;
    }

    /* Página Actual: Resaltado sutil dentro de la píldora (Sin sombras) */
    .dataTables_wrapper .dataTables_paginate span .paginate_button.current,
    .contTable .pagination .page-item.active .page-link {
        background: rgba(255, 255, 255, 0.15) !important;
        color: #fff !important;
        font-weight: 700 !important;
        box-shadow: none !important;
        border: none !important;
    }

    .dataTables_wrapper .dataTables_paginate .ellipsis {
        color: rgba(255, 255, 255, 0.3) !important;
        padding: 0 5px !important;
    }

    /* 3. OCULTAR EXTRAS */
    .dataTables_wrapper .dataTables_paginate .paginate_button.first,
    .dataTables_wrapper .dataTables_paginate .paginate_button.last,
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        display: none !important;
    }

    /* Acción: Botón Icono iOS Style */
    .action-btn-ios {
        background: transparent !important;
        border: none !important;
        color: #007aff !important;
        padding: 5px !important;
        margin: 0 auto;
        font-size: 16px;
        transition: transform 0.2s;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
    }

    .action-btn-ios:active {
        transform: scale(0.9);
        background: rgba(255, 255, 255, 0.05) !important;
    }

    .action-btn-ios.delete {
        color: #ff3b30 !important; /* Rojo iOS */
        background: rgba(255, 59, 48, 0.1) !important;
    }

    .action-btn-ios i {
        background: transparent !important;
    }

    /* Estilo de Alerta Nativa iOS */
    .ios-alert-content {
        background: rgba(30,30,30,0.75) !important;
        backdrop-filter: blur(25px) saturate(180%) !important;
        -webkit-backdrop-filter: blur(25px) saturate(180%) !important;
        border: none !important;
        border-radius: 14px !important;
        color: #fff !important;
        overflow: hidden !important;
    }

    .ios-alert-header {
        padding: 20px 15px 10px 15px;
        text-align: center;
    }

    .ios-alert-title {
        font-size: 17px !important;
        font-weight: 600 !important;
        margin: 0 0 5px 0 !important;
        color: #fff !important;
    }

    .ios-alert-message {
        font-size: 13px !important;
        color: rgba(255, 255, 255, 0.8) !important;
        margin: 0 !important;
        line-height: 1.4;
    }

    .ios-alert-actions {
        display: flex;
        flex-direction: column;
        border-top: 1px solid rgba(255, 255, 255, 0.15);
    }

    .ios-alert-btn {
        background: transparent !important;
        border: none !important;
        padding: 12px !important;
        font-size: 17px !important;
        width: 100%;
        color: #007aff !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        cursor: pointer;
        outline: none !important;
    }

    .ios-alert-btn:last-child {
        border-bottom: none;
    }

    .ios-alert-btn-danger {
        color: #ff3b30 !important;
        font-weight: 600 !important;
    }

    .ios-alert-btn-cancel {
        font-weight: 400 !important;
    }

    .ios-alert-btn:active {
        background: rgba(255, 255, 255, 0.1) !important;
    }
</style>
@endpush

@push('scripts')
<script>

    // Variables para script_dataTable.js
    var order_type = 1;
    var csrfToken = "{{ csrf_token() }}";
    var routePlateDataTable = "{{ route('plate.datatable') }}";
    var routePlateFetch = "{{ route('plate.fetch') }}";
    var routePlateNull = "{{ route('plate.null') }}";

    // Configuración personalizada para estilo iOS (Unificada con Usuarios)
    window.datatableOptions = {
        lengthChange: false,
        autoWidth: false, // Desactivar auto-cálculo para usar solo nuestro CSS
        pageLength: 20, // Aumentado a 20 para llenar los 750px de altura
        pagingType: "simple_numbers",
        scrollY: "700px", // Expandido proporcionalmente a la tarjeta
        scrollCollapse: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Buscar placa...",
            info: "Placas _START_ a _END_ de _TOTAL_",
            paginate: {
                previous: "", 
                next: ""
            }
        },
        columns: [
            { data: 'plate_name', name: 'plate_name' },
            { data: 'plate_entry_date', name: 'plate_entry_date' },
            { 
                data: null,
                orderable: false,
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return `
                        <button class="action-btn-ios delete invoiceInfo" data-id="${row.id}" title="Borrar">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                }
            }
        ]
    };
    
    // Aquí comprobamos si DataTable está disponible antes de cargar el script
    if (typeof $.fn.DataTable === 'undefined') {
        console.error('La biblioteca DataTables no está cargada correctamente.');
    }

    
    // Funciones para mostrar/ocultar las secciones
    function funcShow() {
        $(".contFile").fadeIn("slow");
        $(".contTable").fadeOut("slow");
        
        // Actualizar visual de pestañas
        $("#mode-lote").addClass("active");
        $("#mode-individual").removeClass("active");
    }

    function funcHide() {
        $(".contFile").fadeOut("slow");
        $(".contTable").fadeIn("slow");
        
        // Actualizar visual de pestañas
        $("#mode-individual").addClass("active");
        $("#mode-lote").removeClass("active");
    }

    // Ajusta dinámicamente la altura del contenedor de la tabla en móviles
    // para que se adapte al espacio disponible sin solaparse con la bottom bar
    // Ajusta el contenedor de la tabla para asegurar que nada se recorte
    function adjustTableContainerHeight() {
        const container = document.getElementById('plateTableContainer');
        if (!container) return;

        // Liberamos la altura para aprovechar el espacio y evitar recortes
        container.style.maxHeight = 'none';
        container.style.overflowY = 'visible';
    }

    // Ejecutar al cargar la página y cuando se redimensiona la ventana
    document.addEventListener('DOMContentLoaded', adjustTableContainerHeight);
    window.addEventListener('resize', adjustTableContainerHeight);
    window.addEventListener('orientationchange', adjustTableContainerHeight);
</script>
<script src="{{ asset('js/script_dataTable.js') }}"></script>
@endpush