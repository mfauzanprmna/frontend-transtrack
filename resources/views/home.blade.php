@extends('layouts.admin')

@section('main-content')
    <h1 class="h3 mb-4 text-gray-800">Forecast per Family</h1>

    {{-- Alert untuk error --}}
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- Form input --}}
    <form method="POST" action="{{ route('home.forecast') }}" class="mb-4" id="forecast-form">
        {{-- CSRF token untuk keamanan --}}
        @csrf
        <div class="form-row">
            <div class="col">
                <label>Model ID</label>
                <select name="model_id" class="form-control">
                    <option value="">Pilih Model</option>
                    @foreach ($models as $model)
                        <option value="{{ $model->model_name }}">
                            {{ $model->model_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label>Jumlah Minggu</label>
                <input type="number" name="n_weeks" class="form-control" value="{{ old('n_weeks', $n_weeks ?? 2) }}"
                    min="1" required>
            </div>
            <div class="col-auto d-flex align-items-end">
                <button type="submit" class="btn btn-primary" id="upload-btn">
                    <span id="forecast-text">Forecast Model</span>
                    <span id="forecast-spinner" class="spinner-border spinner-border-sm d-none ml-2" role="status"
                        aria-hidden="true"></span>
                </button>

            </div>
        </div>
    </form>

    {{-- Tampilkan grafik hanya jika ada hasil --}}
    @if (!empty($datasets))
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Grafik Forecast per Family</h6>
            </div>
            <div class="card-body">
                <canvas id="forecastChart" height="100"></canvas>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const colors = [
                '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
                '#858796', '#fd7e14', '#6f42c1', '#20c997', '#ff6384'
            ];

            const rawDatasets = {!! json_encode($datasets) !!};
            const labels = {!! json_encode($labels) !!};

            const datasets = rawDatasets.map((ds, i) => ({
                ...ds,
                borderColor: colors[i % colors.length],
                backgroundColor: colors[i % colors.length] + '33',
                hidden: false,
                borderWidth: 2
            }));

            const ctx = document.getElementById('forecastChart').getContext('2d');
            let activeIndex = null;

            const forecastChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'nearest',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    hover: {
                        mode: 'dataset',
                        onHover: (event, elements) => {
                            if (elements.length) {
                                const index = elements[0].datasetIndex;
                                if (index !== activeIndex) {
                                    activeIndex = index;
                                    forecastChart.data.datasets.forEach((ds, i) => {
                                        ds.borderWidth = i === index ? 3 : 1;
                                        ds.borderColor = i === index ?
                                            colors[i % colors.length] :
                                            colors[i % colors.length] + '66';
                                    });
                                    forecastChart.update();
                                }
                            } else if (activeIndex !== null) {
                                activeIndex = null;
                                forecastChart.data.datasets.forEach((ds, i) => {
                                    ds.borderWidth = 2;
                                    ds.borderColor = colors[i % colors.length];
                                });
                                forecastChart.update();
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form upload file
            const forecastForm = document.querySelector('form#forecast-form');
            const forecastBtn = document.getElementById('upload-btn');
            const forecastSpinner = document.getElementById('forecast-spinner');
            const forecastText = document.getElementById('forecast-text');

            if (forecastForm) {
                forecastForm.addEventListener('submit', function() {
                    forecastBtn.disabled = true;
                    forecastText.textContent = 'Proses...';
                    forecastSpinner.classList.remove('d-none');
                });
            }
        });
    </script>
@endsection
