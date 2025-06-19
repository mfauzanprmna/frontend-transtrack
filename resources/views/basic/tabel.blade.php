@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif


<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">

            <tr>
                <th>No</th>
                <th>Filename</th>
                <th>Column Date</th>
                <th>Column Sales</th>
                <th>Column Family</th>
                <th>Training Model</th>
                <th>Action</th>
            </tr>

        </thead>
        <tbody>
            @if ($datasets->count())
                @foreach ($datasets as $dataset)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $dataset->file_name }}</td>
                        <td>{{ $dataset->date_column }}</td>
                        <td>{{ $dataset->sales_column }}</td>
                        <td>{{ $dataset->family_column }}</td>
                        <td>
                            @if ($dataset->modelDatasets)
                                <span class="badge badge-success">Model sudah ada</span>
                            @else
                                <span class="badge badge-secondary">Belum ada model</span>
                            @endif
                        <td>
                            <a href="{{ route('dataset.edit', $dataset->id) }}"
                                class="btn btn-sm btn-primary mb-2 mr-2">Edit</a>
                            <form action="{{ route('dataset.destroy', $dataset->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </form>

                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data dataset yang tersedia.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

{{ $datasets->links() }}
