@extends('layouts.admin')

@section('main-content')
    <h1 class="h3 mb-4 text-gray-800">Stock Recommendation</h1>

    <form method="POST" action="{{ route('stock.recommendation') }}">
        @csrf
        <div class="form-group">
            <label for="model_id">Model ID (optional)</label>
            <select name="model_id" class="form-control">
                <option value="">Select Model</option>
                @foreach ($models as $model)
                    <option value="{{ $model->model_name }}">{{ $model->model_name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Get Recommendation</button>
    </form>

    @if(session('recommendations'))
        <hr>
        <h4>Recommended Restock Plans</h4>
        <p><strong>Total:</strong> {{ session('recommendations')['total_recommendations'] }}</p>

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
                    </tr>
                </thead>
                <tbody>
                    @foreach(session('recommendations')['recommendations'] as $rec)
                        <tr>
                            <td>{{ $rec['store_nbr'] }}</td>
                            <td>{{ $rec['family'] }}</td>
                            <td>{{ $rec['forecast_avg'] }}</td>
                            <td>{{ $rec['historical_avg'] }}</td>
                            <td>{{ $rec['growth_percent'] }}%</td>
                            <td class="text-success font-weight-bold">{{ $rec['restock_recommendation_percent'] }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
    @endif
@endsection
