//Plates Datatables Ajax Response
var platesTbl = '';
$(function () {
	// draw function [called if the database updates]
	function draw_data() {
		if ($.fn.dataTable.isDataTable('#dataTables-placas') && platesTbl != '') {
			platesTbl.draw(true)
		} else {
			load_data();
		}
	}

	function load_data() {
		platesTbl = $('#dataTables-placas').DataTable({

			"processing": true,
			"serverSide": true,
			"language": {
				"url": "./assets/datatables/Spanish.json"
			},
			"ajax": {
				url: "./fetch-plates.php",
				method: 'POST',
				data: {
					"order_type": order_type
				}
			},
			columns: [{
				data: 'id',
			},
			{
				data: 'plate',
			},
			{
				data: 'plate_date',
				// render: $.fn.dataTable.render.number('.', ',', 0),
				// className: 'text-right',
			},
			{
				data: null,
				orderable: false,
				className: 'text-center',
				render: function (data, type, row, meta) {
					return '<a href="#null_modal" class="invoiceInfo btn btn-light btn-small" id="invId" data-toggle="modal" data-id="' + (row.id) + '">Borrar</a>';
				}
			}
			],
			drawCallback: function (settings) {
				$('ul.pagination').addClass("pagination-sm");
				$('.invoiceInfo').click(function () {
					var userid = $(this).data('id');
					// AJAX request
					$.ajax({
						url: 'fetch-plate.php',
						type: 'post',
						data: { userid: userid },
						success: function (response) {
							// Add response in Modal body
							$('.modal-body').html(response);
							// Display Modal
							$('#null_modal').modal('show');
						}
					});
				});
				$("#null-confirm").click(function () {
					var id = $('#numInvoice').val();
					$.ajax({
						url: "null-plate.php",
						type: "post",
						data: { invId: id }
					}).done(function (msg) {
						window.location.reload();
					});
				});
			},

			"order": [
				[0, "desc"]
			],
			scrollCollapse: true,
			scrollY: '360px',
			pagingType: 'simple_numbers',
			initComplete: function (settings) {
				$('.paginate_button').addClass('p-1')
			}
		});
	}
	load_data()

});

//Users Datatables Ajax Response
var usersTbl = '';
$(function () {
	// draw function [called if the database updates]
	function draw_data() {
		if ($.fn.dataTable.isDataTable('#dataTables-users') && platesTbl != '') {
			platesTbl.draw(true)
		} else {
			load_data();
		}
	}

	function load_data() {
		platesTbl = $('#dataTables-users').DataTable({

			"processing": true,
			"serverSide": true,
			"language": {
				"url": "./assets/datatables/Spanish.json"
			},
			"ajax": {
				url: "./fetch-users.php",
				method: 'POST',
				data: {
					"order_type": order_type,
					"level_user": level_user
				}
			},
			columns: [{
				data: 'id',
			},
			{
				data: 'username',
			},
			{
				data: 'name',
				// render: $.fn.dataTable.render.number('.', ',', 0),
				// className: 'text-right',
			},
			{
				data: 'role',
			},
			{
				data: null,
				orderable: false,
				className: 'text-center',
				render: function (data, type, row, meta) {
					// return '<a href="invoice.php?invId=' + (row.id) + '&type=1 " class="btn btn-default">Ver</a><a href="#null_modal" class=" invoiceInfo btn btn-default btn-small" id="invId" data-toggle="modal" data-id="' + (row.id) + '">Editar</a>';
					return '<a href="edit_user.php?userId=' + (row.id) + '" class="btn btn-secondary btn-small">Editar</a><a href="#null_modal_user" class="invoiceInfo btn btn-light btn-small" id="invId" data-toggle="modal" data-id="' + (row.id) + '">Borrar</a>';
				}
			}
			],
			drawCallback: function (settings) {
				$('ul.pagination').addClass("pagination-sm");
				$('.invoiceInfo').click(function () {
					var userid = $(this).data('id');
					// AJAX request
					$.ajax({
						url: 'fetch-user.php',
						type: 'post',
						data: { userid: userid },
						success: function (response) {
							// Add response in Modal body
							$('.modal-body').html(response);
							// Display Modal
							$('#null_modal').modal('show');
						}
					});
				});
				$("#null-confirm-user").click(function () {
					var id = $('#numInvoice').val();
					//var saldo = $('#saAbona').val(); 
					$.ajax({
						url: "null-user.php",
						type: "post",
						data: { invId: id }
					}).done(function (msg) {
						window.location.reload();
					});
				});
			},

			"order": [
				[0, "desc"]
			],
			scrollCollapse: true,
			scrollY: '380px',
			initComplete: function (settings) {
				$('.paginate_button').addClass('p-1')
			}
		});
	}
	load_data()

});