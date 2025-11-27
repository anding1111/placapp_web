<div class="row" style="font-size:12px; margin-left:10px;">
    <div class="col-sm-6 invoice-col">    
        <input type="hidden" id="numInvoice" value="{{ $user->id }}">
    </div>
</div>

<div class="col-sm-12" style="margin-top: 15px;">
    <table class="table table-hover" border="0" width="100%">
        <thead>
            <tr>                                
                <th style="width:40%">Nombre</th>                                
                <th style="width:35%; text-align:right">Usuario</th>
                <th style="width:25%; text-align:right">Perfil</th>
            </tr>
        </thead>
        <tbody style="max-height: 30vh; overflow-y: auto; overflow-x: hidden;">
            <tr>
                <td style="width:40%">{{ $user->name }}</td>
                <td style="text-align:right; width:35%">{{ $user->username }}</td>
                <td style="text-align:right; width:25%">{{ $user->role <= 1 ? 'Administrador' : 'Usuario' }}</td>
            </tr>
        </tbody>
    </table>
</div>