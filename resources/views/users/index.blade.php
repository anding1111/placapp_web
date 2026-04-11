@extends('layouts.app')

@section('content')
<a href="{{ route('online.users') }}" class="ios-nav-btn-online">
    <i class="fas fa-users"></i>
    <span class="ios-online-dot"></span>
</a>
<div class="row users-main-row" style="min-height: 85vh;">
    <div class="col-lg-11 col-md-11 col-sm-12 contTable" id="usersTableContainer" style="padding: 0 5px;">
        <div class="import-card-table">
            <div class="table-header-ios">
                GESTIÓN DE USUARIOS
            </div>
            <div class="dataTable_wrapper">
                <table class="table" id="dataTables-users" width="100%">
                    <thead>
                        <tr>
                            <th>ID.</th>
                            <th>Usuario</th>
                            <th>Nombre</th>
                            <th>Perfil</th>
                            <th style="text-align:center">Acción</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
            
            <!-- Sheet Modal para Gestión de Usuario (Add/Edit) -->
            <div id="userSheetOverlay" class="user-sheet-overlay" onclick="hideUserSheet()"></div>
            <div id="userSheet" class="user-sheet">
                <div class="user-sheet-header">
                    <div class="user-sheet-drag-handle"></div>
                    <h3 id="userSheetTitle">AÑADIR USUARIO</h3>
                    <button type="button" class="user-sheet-close" onclick="hideUserSheet()">Cerrar</button>
                </div>
                <div class="user-sheet-body">
                    <form id="userActionForm">
                        <input type="hidden" id="userId" name="id">
                        
                        <div class="modern-form-group">
                            <label>Nombre Completo</label>
                            <input class="modern-input" id="userName" name="name" required type="text" placeholder="Ej: Juan Pérez">
                            <span class="error-msg-ios" id="error-name"></span>
                        </div>

                        <div class="modern-form-group">
                            <label>Usuario / Correo</label>
                            <input class="modern-input" id="userUsername" name="username" required type="text" placeholder="usuario@ejemplo.com" autocomplete="off">
                            <span class="error-msg-ios" id="error-username"></span>
                        </div>

                        <div id="passwordFields">
                            <div class="modern-form-group">
                                <label id="passLabel">Contraseña</label>
                                <div class="password-wrapper-ios">
                                    <input class="modern-input" id="userPassword" name="password" type="password" placeholder="••••••••" autocomplete="new-password">
                                    <i class="fas fa-eye show-pass-ios" onclick="togglePassVisibility(this)"></i>
                                </div>
                                <span class="error-msg-ios" id="error-password"></span>
                                <small id="passHint" class="form-text text-muted" style="display:none">Dejar en blanco para conservar actual</small>
                            </div>

                            <div class="modern-form-group">
                                <label>Repetir Contraseña</label>
                                <div class="password-wrapper-ios">
                                    <input class="modern-input" id="userPasswordConfirm" name="password_confirmation" type="password" placeholder="••••••••" autocomplete="new-password">
                                    <i class="fas fa-eye show-pass-ios" onclick="togglePassVisibility(this)"></i>
                                </div>
                                <span class="error-msg-ios" id="error-password_confirmation"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="modern-form-group">
                                    <label>Perfil</label>
                                    <select class="modern-select-ios" id="userRole" name="rol">
                                        <option value="1">Admin</option>
                                        <option value="2" selected>Usuario</option>
                                    </select>
                                    <span class="error-msg-ios" id="error-rol"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="modern-form-group">
                                    <label>Nivel</label>
                                    <select class="modern-select-ios" id="userLevel" name="level">
                                        @foreach($availableLevels as $value => $display)
                                            <option value="{{ $value }}">{{ $display }}</option>
                                        @endforeach
                                    </select>
                                    <span class="error-msg-ios" id="error-level"></span>
                                </div>
                            </div>
                        </div>

                        <div class="user-sheet-footer">
                            <button type="button" id="submitUserBtn" onclick="saveUser()" class="btn-ios-submit">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Alerta Nativa de Borrado (iOS Style) -->
            <div class="modal fade" id="null_modal_user" role="dialog">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 280px; margin: auto;">
                    <div class="modal-content ios-alert-content">
                        <div class="ios-alert-header">
                            <h4 class="ios-alert-title">¿Eliminar Usuario?</h4>
                            <p class="ios-alert-message">Esta acción no se puede deshacer.</p>
                        </div>
                        <div class="modal-body ios-alert-body" style="padding: 0 15px 15px 15px; text-align: center; font-size: 13px; color: rgba(255,255,255,0.6);">
                            <!-- El nombre del usuario se inyectará aquí -->
                        </div>
                        <div class="ios-alert-actions">
                            <button type="button" id="null-confirm-user" class="ios-alert-btn ios-alert-btn-danger" data-dismiss="modal">Eliminar</button>
                            <button type="button" class="ios-alert-btn ios-alert-btn-cancel" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS Específico para estilo iOS 17 -->
@push('styles')
<style>
    /* Centrado y Contenedor */
    .users-main-row {
        width: 100%; 
        margin: 0; 
        margin-top: 60px; /* Espacio para el logout en Desktop */
        display: flex;
        justify-content: center;
        align-items: center; /* Centrado solo en Desktop por defecto */
    }

    .import-card-table {
        background-color: rgba(28, 28, 30, 0.7);
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 20px 15px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
        width: 100%;
        display: flex;
        flex-direction: column;
        min-height: 600px; /* Altura estable en Desktop */
    }

    /* Botón Navegación Online Style iOS - Simétrico al Logout */
    .ios-nav-btn-online {
        position: fixed;
        top: 20px;
        left: 20px;
        width: 44px;
        height: 44px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 18px;
        z-index: 9999;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none !important;
    }

    .ios-nav-btn-online:hover {
        transform: scale(1.1);
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.3);
        color: #fff !important; /* Forzar blanco para evitar azul heredado */
    }

    /* Tooltip sutil estilo iOS */
    .ios-nav-btn-online::after {
        content: 'Usuarios en Línea';
        position: absolute;
        left: 55px;
        white-space: nowrap;
        background: rgba(44, 44, 46, 0.8);
        backdrop-filter: blur(15px);
        padding: 5px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 500;
        color: #fff;
        opacity: 0;
        transform: translateX(-10px);
        transition: all 0.3s ease;
        pointer-events: none;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .ios-nav-btn-online:hover::after {
        opacity: 1;
        transform: translateX(0);
    }

    .ios-nav-btn-online:active {
        transform: scale(0.92);
        background: rgba(255, 255, 255, 0.2);
    }

    .ios-online-dot {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 10px;
        height: 10px;
        background: #34c759; /* Verde nativo de iOS */
        border: 2px solid #1c1c1e;
        border-radius: 50%;
        box-shadow: 0 0 10px rgba(52, 199, 89, 0.5);
        transition: all 0.3s ease;
    }

    .ios-nav-btn-online:hover .ios-online-dot {
        box-shadow: 0 0 15px rgba(52, 199, 89, 1);
        transform: scale(1.1);
    }

    @media (max-width: 768px) {
        .users-main-row {
            margin-top: 85px; /* Más espacio para evitar que los iconos superiores tapen el contenido */
            align-items: flex-start; /* Empezar desde arriba en mvil */
        }
        .import-card-table {
            padding: 15px 8px;
            min-height: 65vh; /* Ms alta en mvil */
        }
    }

    .table-header-ios {
        text-align: center;
        color: rgba(255, 255, 255, 0.4);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.2px;
        margin-bottom: 20px;
        text-transform: uppercase;
    }

    /* Rediseño de Tabla */
    #dataTables-users {
        border: none !important;
        background: transparent !important;
        width: 100% !important;
        margin: 0 !important;
        table-layout: fixed !important; /* Fuerza a las columnas a respetar el ancho del contenedor */
    }

    #dataTables-users thead th {
        background: transparent !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        color: rgba(255, 255, 255, 0.6) !important;
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 600;
        padding: 12px 10px;
    }

    #dataTables-users tbody td {
        border: none !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        color: rgba(255, 255, 255, 0.9) !important;
        padding: 14px 10px;
        font-size: 14px;
        vertical-align: middle;
        background: transparent !important;
        word-break: break-word !important; /* Fuerza el salto de lnea en palabras largas */
        overflow-wrap: anywhere !important; /* Proteccin extra para desbordamiento */
    }

    /* Ajuste de anchos de columna (ID está oculto, por lo que nth-child(1) es Usuario) */
    #dataTables-users th:nth-child(1), #dataTables-users td:nth-child(1) { width: 42%; } /* Usuario */
    #dataTables-users th:nth-child(2), #dataTables-users td:nth-child(2) { width: 33%; } /* Nombre */
    #dataTables-users th:nth-child(3), #dataTables-users td:nth-child(3) { width: 15%; } /* Perfil */
    #dataTables-users th:nth-child(4), #dataTables-users td:nth-child(4) { width: 10%; } /* Acciones */

    @media (max-width: 480px) {
        #dataTables-users tbody td {
            font-size: 13px; /* Un poco más pequeño en móviles muy estrechos */
            padding: 12px 6px;
        }
        #dataTables-users th:nth-child(1), #dataTables-users td:nth-child(1) { width: 38%; }
        #dataTables-users th:nth-child(2), #dataTables-users td:nth-child(2) { width: 32%; }
        #dataTables-users th:nth-child(3), #dataTables-users td:nth-child(3) { width: 18%; }
        #dataTables-users th:nth-child(4), #dataTables-users td:nth-child(4) { width: 12%; }
    }

    /* Buscador iOS Style */
    .dataTables_filter {
        float: none !important;
        text-align: center !important;
        margin-bottom: 20px;
    }

    .dataTables_filter label {
        width: 100%;
        color: transparent;
        position: relative;
    }

    .dataTables_filter input {
        width: 100% !important;
        max-width: 340px;
        background: rgba(255, 255, 255, 0.08) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 12px !important;
        color: #fff !important;
        padding: 10px 12px 10px 40px !important;
        font-size: 14px;
        outline: none;
        transition: all 0.3s;
    }

    .dataTables_filter label:after {
        content: '\f002';
        font-family: 'FontAwesome';
        position: absolute;
        left: calc(50% - 155px);
        top: 50%;
        transform: translateY(-50%);
        color: rgba(255, 255, 255, 0.4);
        font-size: 14px;
    }

    /* NUCLEAR FIX: Paginación Circular con Especificidad Extrema */
    .contTable .pagination {
        /* margin: 20px 0 10px 0 !important; */
        display: flex !important;
        justify-content: center !important;
        gap: 8px !important;
        border: none !important;
    }

    .contTable .page-item, 
    .contTable .paginate_button {
        background: transparent !important;
        border: none !important;
        padding: 0 !important;
    }

    .contTable .page-link,
    .contTable .paginate_button a {
        background: rgba(255, 255, 255, 0.08) !important;
        border: none !important;
        color: rgba(255, 255, 255, 0.6) !important;
        width: 36px !important;
        height: 36px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 50% !important;
        font-size: 13px !important;
        transition: all 0.3s ease !important;
    }

    .contTable .page-item.active .page-link {
        background: #007aff !important;
        color: #fff !important;
        font-weight: 700 !important;
        box-shadow: 0 4px 12px rgba(0, 122, 255, 0.4) !important;
    }

    /* Botón Flotante iOS Style */
    .floatingButtonWrap {
        bottom: 95px !important; /* Ajustado para estar más cerca de la barra sin colisionar */
        right: 20px !important;
        transition: all 0.3s ease !important;
    }

    .floatingButtonWrap.fab-hidden {
        opacity: 0 !important;
        pointer-events: none !important;
        transform: scale(0.8) !important;
    }

    .floatingButton {
        width: 54px !important;
        height: 54px !important;
        background: linear-gradient(135deg, #007aff 0%, #0056b3 100%) !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3) !important;
        transition: all 0.2s ease-in-out !important;
        border: none !important;
        position: relative !important;
        padding: 0 !important;
        overflow: visible !important;
        text-decoration: none !important;
    }

    .floatingButton:hover {
        filter: brightness(1.1);
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.4) !important;
    }

    .floatingButton:active {
        transform: scale(0.92) !important;
        filter: brightness(0.9);
        transition: all 0.1s ease !important;
    }

    .floatingButton .material-icons {
        margin: 0 !important;
        font-size: 26px !important; /* Icono más elegante */
        color: white !important;
        font-weight: normal !important;
    }

    /* iOS Sheet Modal Styles */
    .user-sheet-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(4px);
        z-index: 10000;
        display: none;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .user-sheet {
        position: fixed;
        bottom: -100%;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        max-width: 500px;
        background: rgba(44, 44, 46, 0.95);
        backdrop-filter: blur(30px);
        -webkit-backdrop-filter: blur(30px);
        border-radius: 25px 25px 0 0;
        z-index: 10001;
        transition: bottom 0.4s cubic-bezier(0.2, 0.8, 0.2, 1);
        padding: 20px;
        box-shadow: 0 -10px 40px rgba(0,0,0,0.5);
    }

    .user-sheet.show {
        bottom: 0;
    }

    .user-sheet.show + .user-sheet-overlay, 
    .user-sheet-overlay.show {
        display: block;
        opacity: 1;
    }

    .user-sheet-header {
        position: relative;
        text-align: center;
        margin-bottom: 25px;
    }

    .user-sheet-drag-handle {
        width: 40px;
        height: 5px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 3px;
        margin: 0 auto 15px;
    }

    .user-sheet-header h3 {
        color: #fff;
        font-size: 17px;
        font-weight: 700;
        margin: 0;
    }

    .user-sheet-close {
        position: absolute;
        right: 0;
        top: 10px;
        background: transparent;
        border: none;
        color: #007aff;
        font-size: 16px;
        font-weight: 500;
    }

    /* Form Styles within Sheet */
    .modern-form-group {
        margin-bottom: 18px;
    }

    .modern-form-group label {
        display: block;
        color: rgba(255, 255, 255, 0.5);
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 8px;
        margin-left: 5px;
    }

    /* Estilo de Alerta Nativa iOS */
    .ios-alert-content {
        background: rgba(30, 30, 30, 0.75) !important;
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

    /* Redefinimos el modal antiguo para que no interfiera */
    #null_modal_user .modal-header, #null_modal_user .modal-footer { display: none; }
    #null_modal_user .modal-content { border: none; background: transparent; }

    .modern-input {
        width: 100% !important;
        background: rgba(255, 255, 255, 0.08) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 12px !important;
        color: #fff !important;
        padding: 12px 15px !important;
        font-size: 15px !important;
        outline: none !important;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .modern-select-ios {
        width: 100% !important;
        background-color: #2c2c2e !important; /* Fondo sólido oscuro estilo iOS */
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 12px !important;
        color: #fff !important;
        padding: 12px 15px !important;
        font-size: 15px !important;
        outline: none !important;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23ffffff' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 15px center;
    }

    .modern-input.is-invalid-ios, .modern-select-ios.is-invalid-ios {
        border-color: rgba(255, 69, 58, 0.5) !important;
        box-shadow: 0 0 0 3px rgba(255, 69, 58, 0.1) !important;
    }

    .error-msg-ios {
        display: block;
        color: #ff453a;
        font-size: 11px;
        margin-top: 5px;
        margin-left: 5px;
        height: 0;
        opacity: 0;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .error-msg-ios.show {
        height: auto;
        opacity: 1;
        margin-bottom: 5px;
    }

    /* Estilo para las opciones del desplegable */
    .modern-select-ios option {
        background-color: #1c1c1e !important; /* Fondo oscuro sólido de iOS */
        color: #fff !important;
    }

    .password-wrapper-ios {
        position: relative;
    }

    .show-pass-ios {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(255, 255, 255, 0.3);
        cursor: pointer;
    }

    .btn-ios-submit {
        width: 100%;
        background: #007aff !important;
        color: #fff !important;
        border: none !important;
        border-radius: 14px !important;
        padding: 15px !important;
        font-size: 17px !important;
        font-weight: 600 !important;
        margin-top: 10px !important;
        transition: all 0.2s;
    }

    .btn-ios-submit:active {
        transform: scale(0.98);
        opacity: 0.9;
    }

    .action-btn-ios {
        background: transparent !important;
        border: none !important;
        color: #007aff !important;
        padding: 5px !important;
        margin: 0 5px;
        font-size: 16px;
        transition: transform 0.2s;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .action-btn-ios:hover {
        transform: scale(1.2);
    }

    .action-btn-ios.delete {
        color: #ff3b30 !important; /* Rojo elegante de iOS */
    }

    .action-btn-ios i {
        background: transparent !important;
    }
</style>
@endpush

<div class="floatingButtonWrap">
    <div class="floatingButtonInner">
        <a href="javascript:void(0)" onclick="showUserSheet()" class="floatingButton">
            <i class="material-icons">add</i>
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
    var routeUserFetchJson = "{{ route('user.fetch.json', ':id') }}";
    var routeUserStore = "{{ route('users.store') }}";
    var routeUserUpdate = "{{ route('users.update', ':id') }}";
    var routeUserNull = "{{ route('user.null') }}";
    
    // Configuración personalizada del datatable de usuarios
    window.usersTableOptions = {
        lengthChange: false,
        pageLength: 10,
        pagingType: "numbers",
        autoWidth: false, // Permitir que el CSS controle los anchos
        scrollY: '550px', // Mucho ms altura de scroll
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Buscar usuario...",
            info: "Usuarios _START_ a _END_ de _TOTAL_",
            paginate: {
                previous: "",
                next: ""
            }
        },
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
            { data: 'id', name: 'id', visible: false },
            { data: 'username', name: 'username' },
            { data: 'name', name: 'name' },
            { data: 'role', name: 'role' },
            { 
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false,
                className: 'text-center align-middle',
                render: function(data, type, row) {
                    return `
                        <div class="d-flex justify-content-center align-items-center">
                            <a href="javascript:void(0)" onclick="showUserSheet(${row.id})" class="action-btn-ios" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="action-btn-ios delete invoiceInfoUser" data-id="${row.id}" title="Borrar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ]
    };

    // --- Lógica de iOS Sheet (Add/Edit User) ---

    function showUserSheet(id = null) {
        const sheet = document.getElementById('userSheet');
        const overlay = document.getElementById('userSheetOverlay');
        const form = document.getElementById('userActionForm');
        const title = document.getElementById('userSheetTitle');
        const fab = document.querySelector('.floatingButtonWrap');
        
        // Reset Error Messages
        document.querySelectorAll('.error-msg-ios').forEach(el => el.classList.remove('show'));
        document.querySelectorAll('.is-invalid-ios').forEach(el => el.classList.remove('is-invalid-ios'));

        // Reset Form
        form.reset();
        document.getElementById('userId').value = id || '';
        
        if (id) {
            title.innerText = 'EDITAR USUARIO';
            document.getElementById('passHint').style.display = 'block';
            document.getElementById('passLabel').innerText = 'NUEVA CONTRASEÑA';
            
            // Cargar datos del usuario
            fetch(routeUserFetchJson.replace(':id', id), {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const user = data.user;
                    if (document.getElementById('userName')) document.getElementById('userName').value = user.name;
                    if (document.getElementById('userUsername')) document.getElementById('userUsername').value = user.username;
                    if (document.getElementById('userRole')) document.getElementById('userRole').value = user.rol;
                    if (document.getElementById('userLevel')) document.getElementById('userLevel').value = user.level;
                }
            });
        } else {
            title.innerText = 'AÑADIR USUARIO';
            document.getElementById('passHint').style.display = 'none';
            document.getElementById('passLabel').innerText = 'CONTRASEÑA';
        }

        if (fab) fab.classList.add('fab-hidden');
        overlay.style.display = 'block';
        setTimeout(() => {
            overlay.classList.add('show');
            sheet.classList.add('show');
        }, 10);
    }

    function hideUserSheet() {
        const sheet = document.getElementById('userSheet');
        const overlay = document.getElementById('userSheetOverlay');
        const fab = document.querySelector('.floatingButtonWrap');
        
        sheet.classList.remove('show');
        overlay.classList.remove('show');
        if (fab) fab.classList.remove('fab-hidden');
        setTimeout(() => overlay.style.display = 'none', 400);
    }

    function saveUser() {
        const id = document.getElementById('userId').value;
        const form = document.getElementById('userActionForm');
        const formData = new FormData(form);
        const submitBtn = document.getElementById('submitUserBtn');
        
        // Limpiar errores previos
        document.querySelectorAll('.error-msg-ios').forEach(el => el.classList.remove('show'));
        document.querySelectorAll('.is-invalid-ios').forEach(el => el.classList.remove('is-invalid-ios'));

        submitBtn.disabled = true;
        submitBtn.innerText = 'Guardando...';

        // Determinar endpoint (store vs update)
        let url = id ? routeUserUpdate.replace(':id', id) : routeUserStore;
        
        if (id) {
            formData.append('_method', 'PUT'); // Para que el controlador reconozca el update
        }
        
        fetch(url, {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest', // Laravel detecta AJAX
                'Accept': 'application/json' 
            },
            body: formData
        })
        .then(async response => {
            const data = await response.json();
            if (response.ok) {
                // Éxito: Cerrar, Limpiar y Recargar Tabla
                hideUserSheet();
                if (window.LaravelDataTables && window.LaravelDataTables["dataTables-users"]) {
                    window.LaravelDataTables["dataTables-users"].ajax.reload();
                } else {
                    location.reload(); // Fallback si no hay DT global
                }
            } else if (response.status === 422) {
                // Errores de Validación Inline
                for (const field in data.errors) {
                    const errorSpan = document.getElementById(`error-${field}`);
                    const inputField = document.getElementsByName(field)[0];
                    
                    if (errorSpan) {
                        errorSpan.innerText = data.errors[field][0];
                        errorSpan.classList.add('show');
                    }
                    
                    if (inputField) {
                        inputField.classList.add('is-invalid-ios');
                    }
                }
            } else {
                alert('Ocurrió un error inesperado al procesar la solicitud.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión con el servidor.');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerText = 'Guardar';
        });
    }

    function togglePassVisibility(btn) {
        const input = btn.previousElementSibling;
        if (input.type === "password") {
            input.type = "text";
            btn.classList.remove('fa-eye');
            btn.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            btn.classList.remove('fa-eye-slash');
            btn.classList.add('fa-eye');
        }
    }

    // Ajusta dinámicamente la altura del contenedor de la tabla en móviles
    // para que se adapte al espacio disponible sin solaparse con la bottom bar
    function adjustTableContainerHeight() {
        const container = document.getElementById('usersTableContainer');
        if (!container) return;

        // Solo aplica en pantallas pequeñas (≤768px)
        if (window.innerWidth <= 768) {
            const bottomBar = document.querySelector('body > div:last-child');
            const bottomBarHeight = bottomBar ? bottomBar.offsetHeight : 80;
            const viewportHeight = window.innerHeight;
            const containerTop = container.getBoundingClientRect().top + window.scrollY;
            
            // Altura disponible: viewport - posición del contenedor - altura de la barra inferior de navegación (aprox 70px)
            const availableHeight = viewportHeight - containerTop - 70; 
            container.style.maxHeight = Math.max(450, availableHeight) + 'px';
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
</script>
<script src="{{ asset('js/script_dataTable.js') }}"></script>
@endpush