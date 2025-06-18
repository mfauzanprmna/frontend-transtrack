@extends('layouts.admin')

@section('main-content')
    <h1 class="h3 mb-4 text-gray-800">Dataset Management</h1>

    <!-- Custom Tab Buttons Centered -->
    <div class="d-flex justify-content-center mb-3">
        <div class="btn-group" role="group" aria-label="Dataset Tabs">
            <button class="btn btn-outline-primary rounded-pill active" id="btn-upload" data-toggle="tab" data-target="#upload-dataset">Upload Dataset</button>
            <button class="btn btn-outline-primary rounded-pill  mx-2" id="btn-tabel" data-toggle="tab" data-target="#tabel-dataset">Tabel Dataset</button>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content">
        <div class="tab-pane fade  " id="tabel-dataset">
            @include('basic.tabel')
        </div>
        <div class="tab-pane fade show active" id="upload-dataset">
            @include('basic.create')
        </div>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.btn-group .btn').click(function () {
                $('.btn-group .btn').removeClass('active');
                $(this).addClass('active');

                const target = $(this).data('target');
                $('.tab-pane').removeClass('show active');
                $(target).addClass('show active');
            });
        });
    </script>
@endsection
