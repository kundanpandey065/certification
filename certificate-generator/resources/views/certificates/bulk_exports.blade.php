@extends('layouts.app')
@section('title', 'Bulk PDF Export')
@section('content')
    <div class="container-fluid mt-4">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Filters + Generate --}}
        <div class="card mb-3">
            <div class="card-body">
                <form action="{{ route('bulk-exports.generate') }}" method="POST" class="row g-2 align-items-center">
                    @csrf
                    <div class="col-auto">
                        <select name="sector" id="sector" class="form-select">
                            <option value="">All Sectors</option>
                            @foreach (\App\Models\Certificate::distinct()->pluck('ssc_name') as $s)
                                <option value="{{ $s }}" {{ old('sector') == $s ? 'selected' : '' }}>
                                    {{ $s }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-auto">
                        <select name="district" id="district" class="form-select" disabled>
                            <option value="">All Districts</option>
                        </select>
                    </div>

                    <div class="col-auto">
                        <select name="school" id="school" class="form-select" disabled>
                            <option value="">All Schools</option>
                        </select>
                    </div>

                    {{-- New: Class/Standard --}}
                    <div class="col-auto">
                        <select name="class_standard" id="class_standard" class="form-select" disabled>
                            <option value="">All Classes</option>
                        </select>
                    </div>

                    <div class="col-auto d-flex align-items-center">
                        <button id="generateBtn" class="btn btn-success" disabled>
                            Generate Bulk PDF
                        </button>
                        <span id="bulkWarning" class="text-danger ms-3" style="display:none; white-space: nowrap;">
                            ⚠️ Don’t refresh the page
                        </span>
                    </div>
                </form>
            </div>
        </div>
        {{-- History --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Export History</h5>
                <button id="deleteAllBtn" class="btn btn-sm btn-danger">
                    Delete All
                </button>
                {{-- Hidden form to delete all --}}
                <form id="deleteAllForm" action="{{ route('bulk-exports.destroyAll') }}" method="POST"
                    style="display:none;">
                    @csrf @method('DELETE')
                </form>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Filters</th>
                            <th>Count</th>
                            <th>File</th>
                            <th>Created</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($exports as $ex)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ $ex->sector ?: 'all' }} /
                                    {{ $ex->district ?: 'all' }} /
                                    {{ $ex->school_code ?: 'all' }} /
                                    {{ $ex->class_standard ?: 'all' }}
                                </td>
                                <td>{{ $ex->record_count }}</td>
                                <td>
                                    <a href="{{ route('bulk-exports.download', $ex->id) }}" class="btn btn-sm btn-primary">
                                        {{ $ex->file_name }}
                                    </a>
                                </td>
                                <td>{{ $ex->created_at->timezone(config('app.timezone'))->isoFormat('LLL') }}
                                </td>
                                <td>
                                    {{-- Delete single entry --}}
                                    <button class="btn btn-sm btn-danger deleteBtn" data-id="{{ $ex->id }}">
                                        Delete
                                    </button>
                                    {{-- Hidden form --}}
                                    <form id="deleteForm-{{ $ex->id }}"
                                        action="{{ route('bulk-exports.destroy', $ex->id) }}" method="POST"
                                        style="display:none;">
                                        @csrf @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $exports->links() }}
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // AJAX endpoints
            const districtsUrl = "{{ route('certificates.districts') }}";
            const schoolsUrl = "{{ route('certificates.schools') }}";
            const standardsUrl = "{{ route('certificates.bulk.standards') }}";

            // Grab all selects + the button
            const sectorSelect = document.getElementById('sector');
            const districtSelect = document.getElementById('district');
            const schoolSelect = document.getElementById('school');
            const classSelect = document.getElementById('class_standard');
            const generateBtn = document.getElementById('generateBtn');
            const bulkExportForm = document.querySelector('form[action="{{ route('bulk-exports.generate') }}"]');
            const warningDiv = document.getElementById('bulkWarning');



            // Only enable if *all* have a non‑empty, non‑dummy value
            function updateGenerateState() {
                console.log(sectorSelect.value, districtSelect.value, schoolSelect.value, classSelect.value);

                if (
                    sectorSelect.value !== '' &&
                    districtSelect.value !== '' &&
                    schoolSelect.value !== '' &&
                    classSelect.value !== ''
                ) {
                    generateBtn.removeAttribute('disabled');
                } else {
                    generateBtn.setAttribute('disabled', '');
                }
            }

            // Whenever a user picks a new sector, district or school:
            // 1) clear downstream selects
            // 2) disable them & show "Loading…"
            // 3) fetch the next list
            // 4) repopulate and re-enable
            // 5) re-run the button‑state checker

            sectorSelect.addEventListener('change', () => {
                districtSelect.disabled = true;
                districtSelect.innerHTML = '<option value="">Loading…</option>';
                schoolSelect.disabled = true;
                schoolSelect.innerHTML = '<option value="">All Schools</option>';
                classSelect.disabled = true;
                classSelect.innerHTML = '<option value="">All Classes</option>';
                updateGenerateState();

                fetch(`${districtsUrl}?sector=${encodeURIComponent(sectorSelect.value)}`)
                    .then(r => r.json())
                    .then(list => {
                        let html = '<option value="">All Districts</option>';
                        list.forEach(d => html += `<option>${d}</option>`);
                        districtSelect.innerHTML = html;
                        districtSelect.disabled = false;
                    });
            });

            districtSelect.addEventListener('change', () => {
                schoolSelect.disabled = true;
                schoolSelect.innerHTML = '<option value="">Loading…</option>';
                classSelect.disabled = true;
                classSelect.innerHTML = '<option value="">All Classes</option>';
                updateGenerateState();

                fetch(
                        `${schoolsUrl}?sector=${encodeURIComponent(sectorSelect.value)}&district=${encodeURIComponent(districtSelect.value)}`
                    )
                    .then(r => r.json())
                    .then(list => {
                        let html = '<option value="">All Schools</option>';
                        list.forEach(s => html += `<option>${s}</option>`);
                        schoolSelect.innerHTML = html;
                        schoolSelect.disabled = false;
                    });
            });

            schoolSelect.addEventListener('change', () => {
                classSelect.disabled = true;
                classSelect.innerHTML = '<option value="">Loading…</option>';
                updateGenerateState();

                fetch(
                        `${standardsUrl}?sector=${encodeURIComponent(sectorSelect.value)}&district=${encodeURIComponent(districtSelect.value)}&school=${encodeURIComponent(schoolSelect.value)}`
                    )
                    .then(r => r.json())
                    .then(list => {
                        let html = '<option value="">All Classes</option>';
                        list.forEach(c => html += `<option>${c}</option>`);
                        classSelect.innerHTML = html;
                        classSelect.disabled = false;
                    });
            });

            // And any manual change to any select should re-check the button
            [sectorSelect, districtSelect, schoolSelect, classSelect]
            .forEach(el => el.addEventListener('change', updateGenerateState));

            // Finally: show spinner + disable button on submit
            bulkExportForm.addEventListener('submit', function() {
                generateBtn.disabled = true;
                generateBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>' +
                    'Generating…';
                warningDiv.style.display = 'block';
            });



            // Initial check in case old() values filled some selects
            updateGenerateState();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const deleteAllBtn = document.getElementById('deleteAllBtn');
            const deleteAllForm = document.getElementById('deleteAllForm');

            deleteAllBtn.addEventListener('click', () => {
                Swal.fire({
                    title: 'Delete ALL exports?',
                    text: 'This will permanently remove all bulk-export records.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete all',
                    cancelButtonText: 'No, keep them'
                }).then(result => {
                    if (result.isConfirmed) {
                        deleteAllForm.submit();
                    }
                });
            });

            // Delegate single-delete buttons
            document.querySelectorAll('.deleteBtn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.dataset.id;
                    Swal.fire({
                        title: 'Delete this export?',
                        text: 'This cannot be undone.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it',
                        cancelButtonText: 'No'
                    }).then(result => {
                        if (result.isConfirmed) {
                            document.getElementById(`deleteForm-${id}`).submit();
                        }
                    });
                });
            });
        });
    </script>


@endsection
