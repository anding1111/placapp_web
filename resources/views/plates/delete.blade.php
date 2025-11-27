@extends('layouts.app')

@section('content')
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="z-index: 2;">
    <div style="margin-top:10px;">
        <ul class="center">
            <li style="padding: 0px 00px;"><button onclick="funcHide()" class="btn"><i class="fas fa-th-list fa-2x"></i></button></li>
            <li style="padding: 0px 20px;"><button onclick="funcShow();" class="btn"><i class="fas fa-file-excel fa-2x"></i></button></li>
        </ul>
    </div>
</div>

<!-- Vista de carga de Excel para eliminación -->
<div class="contFile hidden">
    <div align="center">
        <div class="uploadWrapper uploadWrapperXlsx">
            <div class="avatar">
                <img src="{{ asset('img/Logo_Placapp.png') }}" alt="Avatar">
            </div>
            <form method="POST" action="{{ route('delete.excel') }}" enctype="multipart/form-data" id="imageUploadForm" class="imageUploadForm">
                @csrf
                <span class="helpText" id="helpText">Arrastre Archivo Aquí</span>
                <input type='file' name="excel" id="file-delete" class="uploadButton" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, text/csv" required />
                <div id="uploadedImg" class="uploadedImg">
                    <span class="unveil"></span>
                </div>
                <span class="pickFile">
                    <button type="submit" class="pickFileButton pickFileButtonXlsx">Subir Archivo</button>
                </span>
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
<div class="form-wrapper-table col-lg-8 col-md-10 col-sm-12 col-xs-12 contTable mt-3">
    <div class="panel panel-default w3-card-4">
        <div class="row justify-content-center mb--10">
            PLACAS
        </div>
        <div class="panel-body">
            <div class="dataTable_wrapper">
                <table class="table table-striped table-bordered table-hover" id="dataTables-placas" width="100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Placa</th>
                            <th>Fecha Entrada</th>
                            <th style="text-align:center">Opciones</th>
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
    
    // Aquí comprobamos si DataTable está disponible antes de cargar el script
    if (typeof $.fn.DataTable === 'undefined') {
        console.error('La biblioteca DataTables no está cargada correctamente.');
    }

    
    // Funciones para mostrar/ocultar las secciones
    function funcShow() {
        $(".contFile").fadeIn("slow");
        $(".contTable").fadeOut("slow");
    }

    function funcHide() {
        $(".contFile").fadeOut("slow");
        $(".contTable").fadeIn("slow");
    }

    // Carga script_dataTable.js después de definir las variables
</script>
<script src="{{ asset('js/script_dataTable.js') }}"></script>
@endpush