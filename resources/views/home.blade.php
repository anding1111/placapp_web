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
        <div class="search-input-wrapper">
            <input type="plate" id="search_textBox" autofocus="autofocus" />
            <div id="ios_loader" class="ios-loader" style="display:none;">
                <span></span><span></span><span></span><span></span>
                <span></span><span></span><span></span><span></span>
                <span></span><span></span><span></span><span></span>
            </div>
        </div>
        <div class="text-white mt-2" style="font-size: 0.7em; opacity: 0.6; letter-spacing: 1px; font-weight: 300;">INGRESE 3 PRIMERAS LETRAS</div>
        <div style="margin-top: 15px;">
            <div id="suggestion_list">
                <!-- Aquí carga la las placas encontradas -->
            </div>
        </div>
    </div>
</div>

<div class="color-selector" style="--blue: #50C4ED; --red: #FC2947; --yellow: #F4E931; --green: #70E000; --pink: #FF96C5; --black: #2b2b28;">
    <div class="color-selector-header">ANTIGÜEDAD</div>
    <div class="color-info-item" data-color="red">Hoy (Día)</div>
    <div class="color-info-item" data-color="green">Semana</div>
    <div class="color-info-item" data-color="pink">Mes</div>
    <div class="color-info-item" data-color="blue">3 Meses</div>
    <div class="color-info-item" data-color="yellow">6 Meses</div>
    <div class="color-info-item" data-color="black">+6 Meses</div>
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
                    $("#ios_loader").fadeIn(200);
                },
                success: function(data) {
                    $("#ios_loader").fadeOut(200);
                    if ($.trim(data)) {   
                        $("#suggestion_list").html(data);
                        var emails = document.querySelectorAll('.email');
                        
                        if (emails.length > 2) {
                            $(".avatar").slideUp(250);
                        } else {
                            $(".avatar").slideDown(250);
                        }
                        
                        var lineBreak = null;
                        
                        emails.forEach(function(email) {
                            email.addEventListener('click', function(event) {
                                if (lineBreak && lineBreak.parentNode) {
                                    lineBreak.parentNode.removeChild(lineBreak);
                                    lineBreak = null;
                                }
                                
                                emails.forEach(function(e) {
                                    e.classList.remove('expand');
                                });
                                
                                var nextSibling = email.nextElementSibling;
                                var isLastInRow = false;
                                if (nextSibling && nextSibling.classList.contains('email')) {
                                    if (nextSibling.offsetTop > email.offsetTop) {
                                        isLastInRow = true;
                                    }
                                }
                                
                                email.classList.add('expand');
                                $(".avatar").slideUp(250);
                                
                                // Auto-scroll inteligente para asegurar visibilidad total de la card
                                setTimeout(function() {
                                    email.scrollIntoView({ 
                                        behavior: 'smooth', 
                                        block: 'nearest',
                                        inline: 'start'
                                    });
                                }, 150);
                                
                                if (isLastInRow && nextSibling) {
                                    lineBreak = document.createElement('div');
                                    lineBreak.style.width = '100%';
                                    lineBreak.style.height = '0';
                                    lineBreak.id = 'expansion-break';
                                    nextSibling.parentNode.insertBefore(lineBreak, nextSibling);
                                }
                                
                                $("#search_textBox").focus()
                                event.stopPropagation();
                            });
                        });
                        
                        document.addEventListener('click', function(event) {
                            emails.forEach(function(email) {
                                if (!email.contains(event.target)) {
                                    email.classList.remove('expand');
                                }
                            });
                            if (lineBreak && lineBreak.parentNode) {
                                lineBreak.parentNode.removeChild(lineBreak);
                                lineBreak = null;
                            }

                            // Si el clic es en la navegación, ignorar reseteo para evitar parpadeo
                            if (event.target.closest('.mobile-bottom-nav') || event.target.closest('.sidebar-toggle') || event.target.closest('.header-btn')) {
                                return;
                            }

                            selectPlate(1, false); // No forzar foco al limpiar por clic externo
                        });
                
                        var xTouches = document.querySelectorAll('.x-touch');
                        xTouches.forEach(function(xTouch) {
                            xTouch.addEventListener('click', function(event) {
                                var email = this.closest('.email');
                                email.classList.remove('expand');
                                if (lineBreak && lineBreak.parentNode) {
                                    lineBreak.parentNode.removeChild(lineBreak);
                                    lineBreak = null;
                                }
                                if (emails.length <= 2) {
                                    $(".avatar").slideDown(250);
                                }
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
            $(".avatar").slideDown(250); 
        } else {
            $("#suggestion_list").hide();
            $(".avatar").slideDown(250); 
        }
    });

});

function selectPlate(val, shouldFocus = true) {
    $("#search_textBox").val("");
    if (shouldFocus) {
        $("#search_textBox").focus();
    }
    $("#suggestion_list").hide();
    $(".avatar").slideDown(250); // Restaurar logo globalmente
}
</script>
@endpush