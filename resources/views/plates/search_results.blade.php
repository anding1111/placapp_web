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
                                <div style="color: #333; font-size: 0.8em; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                                    Sin Información
                                </div>
                            </div>
                         </div>
                    @else
                        <div class="row-card">
                            <img class="twitter" src="{{ asset('img/location-car.svg') }}" alt="Ubicación">
                            <div class="link">{{ !empty($plate->plate_location) ? trim($plate->plate_location) : 'Ubicación' }}</div>
                        </div>
                        <div class="row-card">
                            <img class="medium" src="{{ asset('img/info-car.svg') }}" alt="Información">
                            <div class="link">{{ !empty($plate->plate_detail) ? trim($plate->plate_detail) : 'Detalles' }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endforeach