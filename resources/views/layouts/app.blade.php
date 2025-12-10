<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="{{ asset('img/Logo_Placapp.png') }}" />
    <!--===============================================================================================-->

    <title>Sistema Buscador de Placas</title>

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css" rel="stylesheet">
    
    @stack('styles')
    <!-- Mobile-only DataTables tweaks: hide length selector, keep search visible -->
    <style>
        @media (max-width: 480px) {
            /* Oculta el control 'mostrar X registros' generado por DataTables */
            .dataTables_length { display: none !important; }

            /* Mantener el filtro (search) visible y ocupando el ancho */
            .dataTables_filter { display: block !important; width: 100% !important; margin-top: 8px; }
            .dataTables_filter label { width: 100%; display: flex; justify-content: flex-start; }
            .dataTables_filter input { width: 100% !important; max-width: 100% !important; }

            /* Ajustes adicionales para que la tabla sea usable en móvil */
            .dataTables_paginate { display: flex; justify-content: center; margin-top: 8px; }
        }
    </style>
</head>

<body>
    <div class="close-container">
        <div class="leftright"></div>
        <div class="rightleft"></div>
        <label class="close">salir</label>
        <input type="hidden" id="my-id" value="{{ Auth::id() }}">
    </div>
    
    @yield('content')

    <nav class="mobile-bottom-nav">
        <div class="mobile-bottom-nav__item @if(Route::is('home')) mobile-bottom-nav__item--active @endif">
            <div class="mobile-bottom-nav__item-content url_button">
                <i class="material-icons">home</i>
                Inicio
            </div>
        </div>
        @if(Auth::user() && Auth::user()->role <= 1)
            <div class="mobile-bottom-nav__item @if(Route::is('upload.form')) mobile-bottom-nav__item--active @endif">
                <div class="mobile-bottom-nav__item-content url_button">
                    <i class="material-icons">upload_file</i>
                    Importar
                </div>
            </div>
            <div class="mobile-bottom-nav__item @if(Route::is('delete.form')) mobile-bottom-nav__item--active @endif">
                <div class="mobile-bottom-nav__item-content url_button">
                    <i class="material-icons">delete</i>
                    Borrar
                </div>
            </div>
            <div class="mobile-bottom-nav__item @if(Route::is('users.*')) mobile-bottom-nav__item--active @endif">
                <div class="mobile-bottom-nav__item-content url_button">
                    <i class="material-icons">manage_accounts</i>
                    Usuarios
                </div>
            </div>
        @endif
    </nav>

    <!-- jQuery -->
    <script src="{{ asset('js/jquery-3.7.0.min.js') }}"></script>
    
    <!-- Bootstrap 4 JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    
    <!-- Script JS personalizado -->
    <script src="{{ asset('js/script.js') }}"></script>
    
    <!-- Inicialización de DataTables -->
    @include('layouts.partials.datatables-init')
    
    <script>
        $(document).ready(function() {
            // Asegúrate de que la clase 'url_button' funcione para la navegación
            $('.url_button').on('click', function() {
                var icon = $(this).find('i:first-child').text();
                switch(icon) {
                    case 'home':
                        window.location.href = "{{ route('home') }}";
                        break;
                    case 'upload_file':
                        window.location.href = "{{ route('upload.form') }}";
                        break;
                    case 'delete':
                        window.location.href = "{{ route('delete.form') }}";
                        break;
                    case 'manage_accounts':
                        window.location.href = "{{ route('users.index') }}";
                        break;
                    case 'exit_to_app':
                    case 'logout':
                        window.location.href = "{{ route('logout') }}";
                        break;
                }
            });

            // Iniciar el rastreador de estado en línea
            if (typeof tracker !== 'undefined') {
                tracker.start();
            }
        });
    </script>
    
    @stack('scripts')
    
    <!-- OnlineTracker (si el usuario está autenticado) -->
    @auth
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tracker = new OnlineTracker({
                interval: 3000, // 3 segundos
                endpoint: '{{ route("online.update") }}',
                csrfToken: "{{ csrf_token() }}"
            });
            tracker.start();
        });
    </script>
    @endauth
    
    <!-- Rutas globales para JavaScript -->
    <script>
        var laravelRoutes = {
            home: "{{ route('home') }}",
            upload: "{{ route('upload.form') }}",
            delete: "{{ route('delete.form') }}",
            users: "{{ route('users.index') }}",
            logout: "{{ route('logout') }}",
            onlineUsers: "{{ route('online.users') }}",
            uploadExcel: "{{ route('upload.excel') }}",
            deleteExcel: "{{ route('delete.excel') }}",
            online: "{{ route('online.update') }}"
        };
        
        var csrfToken = "{{ csrf_token() }}";
    </script>
</body>

</html>