@extends('layouts.app')

@section('content')
<div class="online-container">
    <div class="online-user icon"></div>
</div>
<div class="form-wrapper-table col-lg-8 col-md-10 col-sm-12 col-xs-12">
    <div class="panel panel-default w3-card-4">
        <div class="row justify-content-center mb-10">
            USUARIOS
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <div class="dataTable_wrapper">
                <table class="table table-striped table-bordered table-hover" id="dataTables-users" width="100%">
                    <thead>
                        <tr>
                            <th>ID.</th>
                            <th>Usuario</th>
                            <th>Nombre</th>
                            <th>Perfil</th>
                            <th style="text-align:center">Opciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
            
            <!-- Modal Eliminación de Usuario -->
            <div class="modal fade" id="null_modal_user" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <label class="modal-title" style="width: 100%; text-align:center;">BORRAR USUARIO</label>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <!-- El contenido del modal se cargará dinámicamente -->
                        </div>
                        <div class="modal-footer" style="text-align: center;">
                            <button type="button" id="null-confirm-user" class="btn btn-default btn-lg" data-dismiss="modal" style="color:#fff;background-color:#33B5E5;">BORRAR</button>
                            <button type="button" class="btn btn-default btn-lg" data-dismiss="modal" style="color:#fff;background-color:#33B5E5;">SALIR</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="floatingButtonWrap">
    <div class="floatingButtonInner">
        <a href="{{ route('users.create') }}" class="floatingButton">
            <i class="material-icons">person_add</i>
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Variables para script_dataTable.js
    var order_type = 1;
    var level_user = {{ Auth::user()->level }};
    var csrfToken = "{{ csrf_token() }}";
    var routeUserDataTable = "{{ route('user.datatable') }}";
    var routeUserFetch = "{{ route('user.fetch') }}";
    var routeUserNull = "{{ route('user.null') }}";
    var routeUserEdit = "{{ route('users.edit', ':id') }}";
    
    // Configuración personalizada del datatable de usuarios
    window.usersTableOptions = {
        ajax: {
            url: routeUserDataTable,
            method: 'POST',
            data: function(d) {
                d._token = csrfToken;
                d.order_type = order_type;
                d.level_user = level_user;
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'username', name: 'username' },
            { data: 'name', name: 'name' },
            { data: 'role', name: 'role' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    };
</script>
<script src="{{ asset('js/script_dataTable.js') }}"></script>
@endpush