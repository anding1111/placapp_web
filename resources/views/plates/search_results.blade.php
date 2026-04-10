@if($plates->isEmpty())
    <div class="d-flex align-items-center justify-content-center flex-column" style="width:100%; padding: 5px 0; text-align: center; margin-top: 5px;">
        <i class="fas fa-search" style="font-size: 1.4em; color: #ffffff; margin-bottom: 5px; opacity: 0.5;"></i>
        <div style="color: #ffffff; font-size: 0.9em; font-weight: 600; letter-spacing: 0.5px; opacity: 0.95;">Placa no encontrada</div>
    </div>
@else
    @foreach($plates as $plate)
        @php
            $date_entry = new DateTime($plate->plate_entry_date);
            $date_today = new DateTime();
            $diff = $date_entry->diff($date_today);
            $days = intval($diff->format("%a"));
            
            $class_boton = '';
            $bg_color = '';
            $text_color = '#000000'; // Default text color

            if ($days > 182) {
                $class_boton = "boton_over_six_month";
                $bg_color = "#1d1e2c";
                $text_color = "#ffffff";
            } elseif ($days > 90) {
                $class_boton = "boton_under_six_month";
                $bg_color = "#F4E931";
            } elseif ($days > 30) {
                $class_boton = "boton_under_three_month";
                $bg_color = "#50C4ED";
            } elseif ($days > 8) {
                $class_boton = "boton_month";
                $bg_color = "#FF96C5";
            } elseif ($days > 1) {
                $class_boton = "boton_week";
                $bg_color = "#70E000";
            } else {
                $class_boton = "boton_day";
                $bg_color = "#FC2947";
            }
        @endphp

        <div class="{{ $class_boton }} email">
            <div class="from">
                <div class="from-contents">
                    <div class="name prevent-select">{{ $plate->plate_name }}</div>
                </div>
            </div>
            <div class="to">
                <div class="to-contents">
                    <div class="top" style="background: {{ $bg_color }};">
                        <div class="name-large prevent-select" style="color: {{ $text_color }}; margin-left: 15px; font-weight: 800; font-size: 18px;">{{ $plate->plate_name }}</div>
                        <div class="x-touch">
                            <div class="x">
                                <div class="line1" style="background: {{ $text_color }};"></div>
                                <div class="line2" style="background: {{ $text_color }};"></div>
                            </div>
                        </div>
                    </div>
                    <div class="bottom">
                        @if(empty($plate->plate_location) && empty($plate->plate_detail))
                             <div class="d-flex align-items-center justify-content-center flex-column" style="width:100%; min-height: 80px; padding: 10px;">
                                <div class="text-center">
                                    <div class="mb-1">
                                        <i class="fas fa-info-circle" style="font-size: 1.5em; color: #FC2947; opacity: 0.8;"></i>
                                    </div>
                                    <div style="color: #ffffff; font-size: 0.82em; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.9;">
                                        Sin Información
                                    </div>
                                </div>
                             </div>
                        @else
                            <div class="row-card" style="flex-direction: column; align-items: flex-start; padding-left: 15px; padding-right: 15px; margin-top: 10px; height: auto; width: 100%; box-sizing: border-box;">
                                <div style="display: flex; align-items: center; margin-bottom: 2px; opacity: 0.8;">
                                    <img class="twitter" src="{{ asset('img/location-car.svg') }}" style="height: 14px; width: 14px; margin: 0; margin-right: 6px; filter: brightness(0) invert(1);" alt="Ubicación">
                                    <div style="font-size: 10px; text-transform: uppercase; font-weight: 700; color: #a1a1a6;">Ubicación</div>
                                </div>
                                <div class="link" style="margin-left: 20px; font-weight: 600; font-size: 13px; color: #ffffff; width: calc(100% - 20px); word-break: break-word; white-space: normal;">
                                    {{ !empty($plate->plate_location) ? trim($plate->plate_location) : 'No registrada' }}
                                </div>
                            </div>

                            <div class="row-card" style="flex-direction: column; align-items: flex-start; padding-left: 15px; padding-right: 15px; margin-top: 10px; height: auto; width: 100%; box-sizing: border-box;">
                                <div style="display: flex; align-items: center; margin-bottom: 2px; opacity: 0.8;">
                                    <img class="medium" src="{{ asset('img/info-car.svg') }}" style="height: 14px; width: 14px; margin: 0; margin-right: 6px; filter: brightness(0) invert(1);" alt="Información">
                                    <div style="font-size: 10px; text-transform: uppercase; font-weight: 700; color: #a1a1a6;">Detalles del Vehículo</div>
                                </div>
                                <div class="link" style="margin-left: 20px; font-weight: 600; font-size: 13px; color: #ffffff; line-height: 1.25; width: calc(100% - 20px); word-break: break-word; white-space: normal;">
                                    {{ !empty($plate->plate_detail) ? trim($plate->plate_detail) : 'No registrados' }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif