@extends('layouts.admin')

@section('main-content')
    <form action="{{ route('xgboost-train') }}" method="POST" id="train-form" class="mb-4">
        @csrf

        <div class="form-group">
            <label for="model_id">Dataset</label>
            <select name="dataset_id" class="form-control" required>
                <option value="">Pilih Dataset</option>
                @foreach ($data as $dataset)
                    <option value="{{ $dataset->id }}">{{ $dataset->file_name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Train Model</button>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">

                <tr>
                    <th>No</th>
                    <th>Nama Dataset</th>
                    <th>Nama Model</th>
                    <th>Family Name</th>
                </tr>
            </thead>
            <tbody>
                @if ($model->count())
                    @foreach ($model as $dataset)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $dataset->fileDataset->file_name }}</td>
                            <td>{{ $dataset->model_name }}</td>
                            <td>{{ $dataset->family_name, '-' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data model yang tersedia.</td>
                    </tr>
                @endif

            </tbody>
        </table>
    </div>

    {{ $model->links() }}
@endsection
