@extends('layouts.app')

@section('content')
    <div class="row d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="import-card">
            <div class="avatar">
                <img src="{{ asset('img/Logo_Placapp.png') }}" alt="Avatar">
            </div>
            <form method="POST" action="{{ route('upload.excel') }}" enctype="multipart/form-data" id="imageUploadForm" class="import-form">
                @csrf
                <div class="import-zone">
                    <span class="import-help-text" id="helpText">Arrastre Archivo Aquí</span>
                    <input type='file' name="excel" id="file" class="import-input" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, text/csv" required />
                    <div id="uploadedImg" class="import-preview">
                        <span class="unveil"></span>
                    </div>
                </div>
                <div class="import-actions">
                    <button type="submit" class="import-btn">Subir Archivo</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('js/script.js') }}"></script>
@endpush