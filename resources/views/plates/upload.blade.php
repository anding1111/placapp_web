@extends('layouts.app')

@section('content')
<div class="contenedor">
    <div align="center">
        <div class="uploadWrapper">
            <div class="avatar">
                <img src="{{ asset('img/Logo_Placapp.png') }}" alt="Avatar">
            </div>
            <form method="POST" action="{{ route('upload.excel') }}" enctype="multipart/form-data" id="imageUploadForm" class="imageUploadForm">
                @csrf
                <span class="helpText" id="helpText">Arrastre Archivo Aquí</span>
                <input type='file' name="excel" id="file" class="uploadButton" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, text/csv" required />
                <div id="uploadedImg" class="uploadedImg">
                    <span class="unveil"></span>
                </div>
                <span class="pickFile">
                    <button type="submit" class="pickFileButton">Subir Archivo</button>
                </span>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/script.js') }}"></script>
@endpush