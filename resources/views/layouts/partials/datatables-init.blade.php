<!-- Script de inicialización de DataTables -->
<script>
    // Configuración global para DataTables
    $.extend(true, $.fn.dataTable.defaults, {
        processing: true,
        serverSide: true,
        scrollCollapse: true,
        pagingType: 'full_numbers',
        dom: 'frtip',  // 'f' = search, 'r' = processing, 't' = table, 'i' = info, 'p' = pagination
        language: {
            "processing": "Procesando...",
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "info": "_START_ a _END_ de _TOTAL_",
            "infoEmpty": "Mostrando 0 registros",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "search": "Buscar:",
            "paginate": {
                "first": "<i class='fa fa-step-backward'></i>",
                "last": "<i class='fa fa-step-forward'></i>",
                "next": "<i class='fa fa-chevron-right'></i>",
                "previous": "<i class='fa fa-chevron-left'></i>"
            }
        }
    });
    
    // Token CSRF para AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

