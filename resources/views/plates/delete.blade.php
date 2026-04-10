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

<!-- Vista de tabla de placas -->
<div class="row justify-content-center contTable mt-3" id="plateTableContainer">
    <div class="col-lg-8 col-md-10 col-sm-12">
        <div class="import-card-table">
            <div class="table-header-ios">
                LISTADO DE PLACAS
            </div>
            <div class="dataTable_wrapper">
                <table class="table" id="dataTables-placas" width="100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Placa</th>
                            <th>Fecha</th>
                            <th style="text-align:center">Acción</th>
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
            
            <!-- Modal Eliminación -->
            <div class="modal fade" id="null_modal" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <label class="modal-title" style="width: 100%; text-align:center;">BORRAR PLACA</label>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <!-- El contenido del modal se cargará dinámicamente -->
                        </div>
                        <div class="modal-footer" style="text-align: center;">
                            <button type="button" id="null-confirm" class="btn btn-default btn-lg" data-dismiss="modal" style="color:#fff;background-color:#33B5E5;">BORRAR</button>
                            <button type="button" class="btn btn-default btn-lg" data-dismiss="modal" style="color:#fff;background-color:#33B5E5;">SALIR</button>
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
        margin-bottom: 5px;
        z-index: 100;
        position: relative;
    }

    @media (max-width: 768px) {
        .mode-switcher-container {
            margin-top: 55px; /* Ms espacio en móvil para el botón de cerrar sesión */
        }
    }

    .segmented-control {
        display: flex;
        background-color: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        padding: 2px;
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
        padding: 15px;
        margin-top: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
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
    .dataTable_wrapper {
        border: none !important;
    }

    #dataTables-placas {
        border: none !important;
        background: transparent !important;
        margin-top: 10px !important;
    }

    #dataTables-placas thead th {
        background: transparent !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        color: rgba(255, 255, 255, 0.6) !important;
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 600;
        padding: 10px 5px;
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
        padding: 12px 5px;
        font-size: 14px;
        vertical-align: middle;
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

    /* NUCLEAR FIX: Eliminación total de cuadros blancos de Bootstrap/DataTables */
    .contTable .pagination {
        margin: 20px 0 !important;
        display: flex !important;
        justify-content: center !important;
        border: none !important;
        gap: 8px !important;
    }

    .contTable .page-item,
    .contTable .paginate_button {
        background: transparent !important;
        border: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .contTable .page-link,
    .contTable .paginate_button a {
        background: rgba(255, 255, 255, 0.08) !important;
        background-color: rgba(255, 255, 255, 0.08) !important;
        border: none !important;
        color: rgba(255, 255, 255, 0.6) !important;
        width: 34px !important;
        height: 34px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 50% !important; /* Círculos perfectos estilo iOS */
        font-size: 13px !important;
        font-weight: 500 !important;
        transition: all 0.3s ease !important;
        margin: 0 !important;
        box-shadow: none !important;
    }

    .contTable .page-item.active .page-link,
    .contTable .paginate_button.current {
        background: #007aff !important;
        background-color: #007aff !important;
        color: #fff !important;
        font-weight: 700 !important;
        box-shadow: 0 4px 12px rgba(0, 122, 255, 0.4) !important;
        transform: scale(1.1);
    }

    .contTable .page-item:hover .page-link:not(.active),
    .contTable .paginate_button:hover:not(.current) {
        background: rgba(255, 255, 255, 0.15) !important;
        color: #fff !important;
    }

    .contTable .page-item.disabled .page-link,
    .contTable .paginate_button.disabled {
        background: transparent !important;
        color: rgba(255, 255, 255, 0.1) !important;
        opacity: 0.3;
        pointer-events: none;
    }

    /* Ocultar botones de Anterior/Siguiente de texto para un look minimalista */
    .contTable .page-item.previous, 
    .contTable .page-item.next,
    .contTable .paginate_button.previous,
    .contTable .paginate_button.next {
        display: none !important;
    }

    /* Botón Borrar más estilizado */
    .btn-small-red {
        background: rgba(255, 69, 58, 0.15) !important;
        color: #ff453a !important;
        border: 1px solid rgba(255, 69, 58, 0.3) !important;
        border-radius: 8px;
        padding: 4px 12px;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-small-red:hover {
        background: #ff453a !important;
        color: #fff !important;
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

    // Configuración personalizada para estilo iOS
    window.datatableOptions = {
        lengthChange: false, // Ocultar "Mostrar X entradas" para menos ruido
        pageLength: 8, // Menos filas para que la tabla sea más corta y manejable
        pagingType: "numbers", // Solo números para evitar los botones de texto toscos
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
            { data: 'id', name: 'id' },
            { data: 'plate_name', name: 'plate_name' },
            { data: 'plate_entry_date', name: 'plate_entry_date' },
            { 
                data: null,
                orderable: false,
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return '<button class="invoiceInfo btn-small-red" data-id="' + row.id + '">Borrar</button>';
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
    function adjustTableContainerHeight() {
        const container = document.getElementById('plateTableContainer');
        if (!container) return;

        // Solo aplica en pantallas pequeñas (≤768px)
        if (window.innerWidth <= 768) {
            const bottomBar = document.querySelector('body > div:last-child');
            const bottomBarHeight = bottomBar ? bottomBar.offsetHeight : 80;
            const viewportHeight = window.innerHeight;
            const containerTop = container.getBoundingClientRect().top + window.scrollY;
            
            // Altura disponible: viewport - posición del contenedor - altura de bottom bar
            const availableHeight = viewportHeight - containerTop - bottomBarHeight - 20; // 20px de margen
            container.style.maxHeight = Math.max(200, availableHeight) + 'px';
            container.style.overflowY = 'auto';
        } else {
            // En pantallas grandes, remover restricción de altura
            container.style.maxHeight = 'none';
            container.style.overflowY = 'visible';
        }
    }

    // Ejecutar al cargar la página y cuando se redimensiona la ventana
    document.addEventListener('DOMContentLoaded', adjustTableContainerHeight);
    window.addEventListener('resize', adjustTableContainerHeight);
    window.addEventListener('orientationchange', adjustTableContainerHeight);

    // Carga script_dataTable.js después de definir las variables
</script>
<script src="{{ asset('js/script_dataTable.js') }}"></script>
@endpush