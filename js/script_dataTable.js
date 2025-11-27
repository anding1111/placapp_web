//Plates Datatables Ajax Response
var platesTbl = '';
$(function () {
    // draw function [called if the database updates]
    function draw_data() {
        if ($.fn.dataTable.isDataTable('#dataTables-placas') && platesTbl != '') {
            platesTbl.draw(true);
        } else {
            load_data();
        }
    }

    function load_data() {
        // Opciones predeterminadas para DataTables de placas
        var options = {
            processing: true,
            serverSide: true,
            ajax: {
                url: routePlateDataTable, // Solo usar la ruta de Laravel
                method: 'POST',
                data: function(d) {
                    d._token = csrfToken || $('meta[name="csrf-token"]').attr('content');
                    d.order_type = order_type || 1;
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
                        return '<a href="#null_modal" class="invoiceInfo btn btn-light btn-small" id="invId" data-toggle="modal" data-id="' + (row.id) + '">Borrar</a>';
                    }
                }
            ],
            order: [[0, "desc"]],
            scrollY: '360px',
            initComplete: function (settings) {
                $('.paginate_button').addClass('p-1');
            }
        };

        // Si hay opciones personalizadas definidas, fusionarlas
        if (window.datatableOptions) {
            // Fusionar objetos anidados (ajax, language, etc)
            if (window.datatableOptions.ajax) {
                options.ajax = window.datatableOptions.ajax;
            }
            if (window.datatableOptions.language) {
                options.language = window.datatableOptions.language;
            }
            if (window.datatableOptions.drawCallback) {
                options.drawCallback = window.datatableOptions.drawCallback;
            }
            if (window.datatableOptions.columns) {
                options.columns = window.datatableOptions.columns;
            }
        }

        platesTbl = $('#dataTables-placas').DataTable(options);
        
        // Añadir manejador de clics para eliminar placas
        $('#dataTables-placas').on('click', '.invoiceInfo', function() {
            var plateId = $(this).data('id');
            
            $.ajax({
                url: routePlateFetch,
                type: 'POST',
                data: { 
                    plateId: plateId,
                    _token: csrfToken || $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('.modal-body').html(response);
                    $('#null_modal').modal('show');
                }
            });
        });
        
        // Añadir manejador de clics para confirmar eliminación
        $(document).on('click', '#null-confirm', function() {
            var id = $('#numInvoice').val();
            $.ajax({
                url: routePlateNull,
                type: 'POST',
                data: { 
                    invId: id,
                    _token: csrfToken || $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        platesTbl.draw(false);
                    }
                }
            });
        });
    }
    
    // Iniciar carga de datos solo si existe la tabla en la página
    if ($('#dataTables-placas').length > 0 && typeof routePlateDataTable !== 'undefined') {
        load_data();
    }
});

//Users Datatables Ajax Response
var usersTbl = '';
$(function () {
    // draw function [called if the database updates]
    function draw_data() {
        if ($.fn.dataTable.isDataTable('#dataTables-users') && usersTbl != '') {
            usersTbl.draw(true);
        } else {
            load_data();
        }
    }

    function load_data() {
        // Opciones predeterminadas para DataTables de usuarios
        var options = {
            processing: true,
            serverSide: true,
            ajax: {
                url: routeUserDataTable,
                method: 'POST',
                data: function(d) {
                    d._token = csrfToken || $('meta[name="csrf-token"]').attr('content');
                    d.order_type = order_type || 1;
                    d.level_user = level_user || 0;
                }
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'username', name: 'username' },
                { data: 'name', name: 'name' },
                { data: 'role', name: 'role' },
                { 
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    name: 'action',
                    render: function (data, type, row, meta) {
                        var editUrl = routeUserEdit ? 
                            routeUserEdit.replace(':id', row.id) : 
                            '#';
                            
                        return '<a href="' + editUrl + '" class="btn btn-secondary btn-small">Editar</a>' + 
                               '<a href="#null_modal_user" class="invoiceInfoUser btn btn-light btn-small" id="invId" data-toggle="modal" data-id="' + row.id + '">Borrar</a>';
                    }
                }
            ],
            order: [[0, "desc"]],
            scrollY: '380px',
            initComplete: function (settings) {
                $('.paginate_button').addClass('p-1');
            }
        };

        // Si hay opciones personalizadas para usuarios, fusionarlas
        if (window.usersTableOptions) {
            if (window.usersTableOptions.ajax) {
                options.ajax = window.usersTableOptions.ajax;
            }
            if (window.usersTableOptions.language) {
                options.language = window.usersTableOptions.language;
            }
            if (window.usersTableOptions.drawCallback) {
                options.drawCallback = window.usersTableOptions.drawCallback;
            }
            if (window.usersTableOptions.columns) {
                options.columns = window.usersTableOptions.columns;
            }
        }

        usersTbl = $('#dataTables-users').DataTable(options);
        
        // Añadir manejador de clics para ver detalles de usuario
        $('#dataTables-users').on('click', '.invoiceInfoUser', function() {
            var userId = $(this).data('id');
            
            $.ajax({
                url: routeUserFetch,
                type: 'POST',
                data: { 
                    userId: userId,
                    _token: csrfToken || $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('.modal-body').html(response);
                    $('#null_modal_user').modal('show');
                }
            });
        });
        
        // Añadir manejador de clics para confirmar eliminación de usuario
        $(document).on('click', '#null-confirm-user', function() {
            var id = $('#numInvoice').val();
            $.ajax({
                url: routeUserNull,
                type: 'POST',
                data: { 
                    invId: id,
                    _token: csrfToken || $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        usersTbl.draw(false);
                    }
                }
            });
        });
    }
    
    // Iniciar carga de datos solo si existe la tabla en la página
    if ($('#dataTables-users').length > 0 && typeof routeUserDataTable !== 'undefined') {
        load_data();
    }
});