<h1 class="h3 mb-4 text-gray-800">Add New Dataset</h1>

@if (session('filename_conflict'))
    <div class="alert alert-warning">
        {{ session('message') }}
    </div>
@endif

{{-- Form Upload --}}
<form id="upload-form" action="{{ route('dataset.import') }}" method="POST" enctype="multipart/form-data" class="mb-3">
    @csrf

    <div class="form-group d-flex align-items-center">
        <input type="file" name="file" class="form-control mr-2" required>
        <button type="submit" class="btn btn-success" id="upload-btn">
            <span id="upload-text">Import Excel/CSV</span>
            <span id="upload-spinner" class="spinner-border spinner-border-sm d-none ml-2" role="status"
                aria-hidden="true"></span>
        </button>

    </div>
</form>

@if (!empty($headers))
    {{-- Form Mapping Kolom --}}
    <form action="{{ route('dataset.store') }}" method="POST" id="submit-form">
        @csrf

        <div class="form-group">
            <label>Kolom Tanggal</label>
            <select name="date_col" class="form-control" required>
                @foreach ($headers as $header)
                    <option value="{{ $header }}">{{ $header }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Kolom Penjualan</label>
            <select name="sales_col" class="form-control" required>
                @foreach ($headers as $header)
                    <option value="{{ $header }}">{{ $header }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Kolom Kategori/Family</label>
            <select name="family_col" class="form-control" required>
                @foreach ($headers as $header)
                    <option value="{{ $header }}">{{ $header }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Kolom Store</label>
            <select name="store_col" class="form-control" required>
                @foreach ($headers as $header)
                    <option value="{{ $header }}">{{ $header }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary" id="submit-btn">
            {{-- Spinner untuk loading --}}
            <span id="submit-text">Simpan</span>
            <span id="submit-spinner" class="spinner-border spinner-border-sm d-none ml-2" role="status"
                aria-hidden="true"></span>
        </button>
    </form>
@endif


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form upload file
        const uploadForm = document.querySelector('form#upload-form');
        const uploadBtn = document.getElementById('upload-btn');
        const uploadSpinner = document.getElementById('upload-spinner');
        const uploadText = document.getElementById('upload-text');

        if (uploadForm) {
            uploadForm.addEventListener('submit', function() {
                uploadBtn.disabled = true;
                uploadText.textContent = 'Mengunggah...';
                uploadSpinner.classList.remove('d-none');
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Form upload file
        const submitForm = document.querySelector('form#submit-form');
        const submitBtn = document.getElementById('submit-btn');
        const submitSpinner = document.getElementById('submit-spinner');
        const submitText = document.getElementById('submit-text');

        if (submitForm) {
            submitForm.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitText.textContent = 'Proses...';
                submitSpinner.classList.remove('d-none');
            });
        }
    });
</script>
