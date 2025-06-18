@extends('layouts.admin')

@section('main-content')
    <h1 class="h3 mb-4 text-gray-800">Forecasting</h1>

    {{-- Alert Section --}}
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @elseif(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    {{-- Forecast Form --}}
    <form action="{{ route('forecast.xgboost') }}" method="POST" class="mb-4">
        @csrf
        <div class="form-group">
            <label for="model_id">Model ID</label>
            <select name="model_id" class="form-control" required>
                <option value="">Pilih Model</option>
                @foreach ($models as $model)
                    <option value="{{ $model->name }}">{{ $model->model_name }} ({{ $model->family_name }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="n_weeks">Jumlah Minggu</label>
            <input type="number" name="n_weeks" class="form-control" min="1" value="2">
        </div>
        <button type="submit" class="btn btn-primary">
            <span id="submit-text">Proses Forecast</span>
            <span id="submit-spinner" class="spinner-border spinner-border-sm d-none ml-2" role="status"
                aria-hidden="true"></span>
        </button>
    </form>

    {{-- Forecast Result --}}
    @isset($data)
        <h5 class="mb-3">Hasil Forecast:</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Store</th>
                    <th>Kategori</th>
                    <th>Forecast</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $row)
                    <tr>
                        <td>{{ $row['date'] }}</td>
                        <td>{{ $row['store_nbr'] }}</td>
                        <td>{{ $row['family'] }}</td>
                        <td>{{ round($row['forecast']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endisset
@endsection

@section('scripts')
    <script>
        const form = document.querySelector('form');
        const btn = form.querySelector('button[type="submit"]');
        const spinner = document.getElementById('submit-spinner');
        const text = document.getElementById('submit-text');

        form.addEventListener('submit', function() {
            btn.disabled = true;
            text.textContent = 'Memproses...';
            spinner.classList.remove('d-none');
        });
    </script>
@endsection
