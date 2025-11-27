<div class="row" style="font-size:12px; margin-left:10px;">
    <div class="col-sm-6 invoice-col">    
        <input type="hidden" id="numInvoice" value="{{ $plate->plate_id }}">
    </div>
</div>

<div class="col-sm-12" style="margin-top: 15px;">
    <table class="table table-hover" border="0" width="100%">
        <thead>
            <tr>                                
                <th style="width:20%">Placa</th>                                
                <th style="width:35%; text-align:right">Entrada</th>
                <th style="width:20%; text-align:right">Ubicación</th>
                <th style="width:25%; text-align:right">Detalles</th>
            </tr>
        </thead>
        <tbody style="max-height: 30vh; overflow-y: auto; overflow-x: hidden;">
            <tr>
                <td style="width:20%">{{ $plate->plate_name }}</td>
                <td style="text-align:right; width:35%">{{ $plate->plate_entry_date }}</td>
                <td style="text-align:right; width:20%">{{ $plate->plate_location }}</td>
                <td style="text-align:right; width:25%">{{ $plate->plate_detail }}</td>
            </tr>
        </tbody>
    </table>
</div>