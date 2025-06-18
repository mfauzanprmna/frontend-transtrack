

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
                @if ($datasets->count())
                    <tr>
                        <th>No</th>
                        <th>Filename</th>
                        <th>Column Date</th>
                        <th>Column Sales</th>
                        <th>Column Family</th>
                        <th>Action</th>
                    </tr>
                @endif
            </thead>
            <tbody>
                @foreach ($datasets as $dataset)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $dataset->file_name }}</td>
                        <td>{{ $dataset->date_column }}</td>
                        <td>{{ $dataset->sales_column }}</td>
                        <td>{{ $dataset->family_column }}</td>
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
            </tbody>
        </table>
    </div>

    {{ $datasets->links() }}