@extends('layouts.app')

@section('content')
<div align="center">
    <div class="form-wrapper">
        <div class="avatar">
            <img src="{{ asset('img/Logo_Placapp.png') }}" alt="Avatar">
        </div>
        <h2 style="color:white;" class="blink_text">
            BUSCAR PLACA
        </h2>
        <input type="plate" id="search_textBox" autofocus="autofocus" />
        <div style="margin-top: 40px;">
            <div id="suggestion_list">
                <!-- Aquí carga la las placas encontradas -->
            </div>
        </div>
    </div>
</div>

<div class="color-selector" style="--blue: #50C4ED; --red: #FC2947; --yellow: #F4E931; --green: #70E000; --pink: #FF96C5; --black: #2b2b28;">
    <input type="radio" id="red" name="colors" />
    <label class="colors" for="red" />Último Día</label>
    <input type="radio" id="green" name="colors" />
    <label class="colors" for="green" />Última Semana</label>
    <input type="radio" id="pink" name="colors" />
    <label class="colors" for="pink" />Último Mes</label>
    <input type="radio" id="blue" name="colors" />
    <label class="colors" for="blue" />Últimos 3 Meses</label>
    <input type="radio" id="yellow" name="colors" />
    <label class="colors" for="yellow" />Últimos 6 Meses</label>
    <input type="radio" id="black" name="colors" />
    <label class="colors" for="black" />Más de 6 Meses</label>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $("#search_textBox").keyup(function() {
        var input = $(this);
        var charLength = input.val().length;

        if (charLength === 3) {
            $.ajax({
                type: "POST",
                url: "{{ route('plate.search') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    keyword: input.val()
                },
                beforeSend: function() {
                    $("#search_textBox").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
                },
                success: function(data) {
                    $("#search_textBox").css("background", "#FFF");
                    if ($.trim(data)) {   
                        $("#suggestion_list").html(data);
                        var emails = document.querySelectorAll('.email');
                        emails.forEach(function(email) {
                            var clickCount = 0;
                            email.addEventListener('click', function(event) {
                                console.log("Clic simple");
                                emails.forEach(function(email) {
                                    email.classList.remove('expand');
                                });
                                email.classList.add('expand');
                                $("#search_textBox").focus()
                                event.stopPropagation();
                            });
                        });
                        
                        document.addEventListener('click', function(event) {
                            emails.forEach(function(email) {
                                if (!email.contains(event.target)) {
                                    email.classList.remove('expand');
                                    selectPlate(1);
                                }
                            });
                        });
                
                        var xTouches = document.querySelectorAll('.x-touch');
                        xTouches.forEach(function(xTouch) {
                            xTouch.addEventListener('click', function(event) {
                                var email = this.closest('.email');
                                email.classList.remove('expand');
                                event.stopPropagation();
                            });
                        });
                        $("#suggestion_list").show();
                    }
                }
            });
        } else if (charLength === 4) {
            var newValue = input.val().substr(3);
            input.val(newValue);
            $("#suggestion_list").hide();
        } else {
            $("#suggestion_list").hide();
        }
    });
});

function selectPlate(val) {
    $("#search_textBox").val("");
    $("#search_textBox").focus()
    $("#suggestion_list").hide();
}
</script>
@endpush