@extends('layouts.admin')

@section('main-content')
    <h1 class="h3 mb-4 text-gray-800">Stock Recommendation</h1>

    {{-- Form untuk memilih model --}}
    <form method="POST" action="{{ route('stock.recommendation') }}">
        @csrf
        <div class="form-group">
            <label for="model_id">Model ID (optional)</label>
            <select name="model_id" class="form-control">
                <option value="">Select Model</option>
                @foreach ($models as $model)
                    <option value="{{ $model->model_name }}" {{ old('model_id') == $model->model_name ? 'selected' : '' }}>
                        {{ $model->model_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Get Recommendation</button>
    </form>

    {{-- Tampilkan error jika ada --}}
    @if(session('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
    @endif

    {{-- Tampilkan hasil rekomendasi --}}
    @isset($recommendations)
        <hr>
        <h4>Recommended Restock Plans</h4>
        <p><strong>Total:</strong> {{ $total_recommendations }}</p>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Store Number</th>
                        <th>Family</th>
                        <th>Forecast Avg</th>
                        <th>Historical Avg</th>
                        <th>Growth %</th>
                        <th>Restock Recommendation %</th>
                        <th>Restock Plan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recommendations as $rec)
                        <tr>
                            <td>{{ $rec['store_nbr'] }}</td>
                            <td>{{ $rec['family'] }}</td>
                            <td>{{ round($rec['forecast_avg']) }}</td>
                            <td>{{ $rec['historical_avg'] }}</td>
                            <td>{{ $rec['growth_percent'] }}%</td>
                            @if ($rec['restock_recommendation_percent'] > 25)
                                <td class="text-success">{{ $rec['restock_recommendation_percent'] }}%</td>
                            @else
                                <td class="text-danger">{{ $rec['restock_recommendation_percent'] }}%</td>
                            @endif
                            <td>
                                Barang yang harus di restock sebesar
                                {{ round($rec['historical_avg'] * ($rec['restock_recommendation_percent'] / 100)) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Link navigasi pagination --}}
        <div class="d-flex justify-content-center">
            {{ $recommendations->links() }}
        </div>
    @endisset
@endsection
