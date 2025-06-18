@extends('layouts.admin')

@section('main-content')
    <h1 class="h3 mb-4 text-gray-800"><i class="fas fa-question-circle"></i> Tanya Prediksi (DeepSeek QA)</h1>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('deepseek.ask') }}" id="submit-form">
        @csrf
        <div class="form-group">
            <label for="model_id">Model ID (Opsional)</label>
            <select name="model_id" class="form-control">
                <option value="">Pilih Model</option>
                @foreach ($models as $model)
                    <option value="{{ $model->model_name }}">{{ $model->model_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group mt-3">
            <label for="question">Pertanyaan:</label>
            <textarea name="question" class="form-control" rows="3"
                placeholder="Contoh: Produk mana yang perlu di-restock minggu depan?" required>{{ old('question') }}</textarea>
        </div>

        <div class="form-group mt-3">
            <label for="format">Format Jawaban</label>
            <select name="format" class="form-control">
                <option value="summary" {{ old('format') == 'summary' ? 'selected' : '' }}>Summary</option>
                <option value="records" {{ old('format') == 'records' ? 'selected' : '' }}>Tabel</option>
            </select>
        </div>

        <div class="form-group mt-3">
            <button type="submit" class="btn btn-primary" id="submit-btn">
                {{-- Teks tombol --}}
                {{-- Spinner untuk loading --}}
                <span id="submit-text"><i class="fas fa-paper-plane"></i>Kirim Pertanyaan</span>
                <span id="submit-spinner" class="spinner-border spinner-border-sm d-none ml-2" role="status"
                    aria-hidden="true"></span>
            </button>
        </div>
    </form>

    {{-- Jawaban tampil di bawah jika tersedia --}}
    @if (isset($qa_response))
        <div class="card mt-4 shadow">
            <div class="card-body">
                <h5 class="card-title text-success"><i class="fas fa-lightbulb"></i> Jawaban</h5>

                {{-- Jika format summary --}}
                @if (isset($qa_response['summary']))
                    <div class="alert alert-info">
                        <strong>Ringkasan:</strong> {{ $qa_response['summary'] }}
                    </div>
                @endif

                {{-- Jika format records dan ada data --}}
                @if (isset($qa_response['answer']) && is_array($qa_response['answer']) && count($qa_response['answer']) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered mt-3">
                            <thead class="thead-light">
                                <tr>
                                    @foreach (array_keys($qa_response['answer'][0]) as $col)
                                        <th>{{ ucfirst(str_replace('_', ' ', $col)) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($qa_response['answer'] as $row)
                                    <tr>
                                        @foreach ($row as $cell)
                                            <td>{{ $cell }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @elseif(isset($qa_response['answer']) && is_string($qa_response['answer']))
                    <div class="alert alert-warning">{{ $qa_response['answer'] }}</div>
                @endif
            </div>
        </div>
    @endif

    <script>
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
@endsection
