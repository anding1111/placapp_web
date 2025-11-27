@foreach($plates as $plate)
    @php
        $date_entry = new DateTime($plate->plate_entry_date);
        $date_today = new DateTime();
        $diff = $date_entry->diff($date_today);
        $days = intval($diff->format("%a"));
        
        $class_boton = '';
        if ($days > 182) {
            $class_boton = "boton_over_six_month";
        } elseif ($days > 90) {
            $class_boton = "boton_under_six_month";
        } elseif ($days > 30) {
            $class_boton = "boton_under_three_month";
        } elseif ($days > 8) {
            $class_boton = "boton_month";
        } elseif ($days > 1) {
            $class_boton = "boton_week";
        } else {
            $class_boton = "boton_day";
        }
    @endphp

    @if($user->level > 3)
        <button class="boton {{ $class_boton }}" onClick="selectPlate('{{ $plate->plate_name }}')">
            <span class="text">{{ $plate->plate_name }}</span>
        </button>
    @else
        <div class="{{ $class_boton }} email">
            <div class="from">
                <div class="from-contents">
                    <div class="name link prevent-select">{{ $plate->plate_name }}</div>
                </div>
            </div>
            <div class="to">
                <div class="to-contents">
                    <div class="top">
                        <div class="avatar-large me prevent-select"></div>
                        <div class="name-large prevent-select">{{ $plate->plate_name }}</div>
                        <div class="x-touch">
                            <div class="x">
                                <div class="line1"></div>
                                <div class="line2"></div>
                            </div>
                        </div>
                    </div>
                    <div class="bottom">
                        <div class="row-card">
                            <img class="twitter" src="{{ asset('img/location-car.svg') }}" alt="Ubicación">
                            <div class="link">{{ !empty($plate->plate_location) ? trim($plate->plate_location) : 'Ubicación' }}</div>
                        </div>
                        <div class="row-card">
                            <img class="medium" src="{{ asset('img/info-car.svg') }}" alt="Información">
                            <div class="link">{{ !empty($plate->plate_detail) ? trim($plate->plate_detail) : 'Detalles del Vehículo' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach