@extends('layouts.app')
@section('title', 'Bulk PDF Export')

@section('styles')
    <style>
        .bulk-exports-page {
            --brand-start: #0d47a1;
            --brand-end: #1976d2;
        }

        .bulk-exports-page .page-header h2 {
            font-weight: 700;
            color: #1b2a4a;
        }

        .bulk-exports-page .page-header p {
            font-size: .95rem;
        }

        .bulk-exports-page .card {
            border: none;
            border-radius: .9rem;
            box-shadow: 0 2px 10px rgba(20, 30, 60, .06);
        }

        .bulk-exports-page .section-title {
            font-size: .8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #6b7280;
        }

        .bulk-exports-page .form-label {
            font-size: .8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .03em;
            color: #6b7280;
            margin-bottom: .35rem;
        }

        .bulk-exports-page .form-select {
            height: 42px;
            border-radius: .55rem;
        }

        .bulk-exports-page .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .45rem;
            height: 42px;
            padding: 0 1.15rem;
            font-size: .9rem;
            font-weight: 600;
            border-radius: .55rem;
            white-space: nowrap;
            line-height: 1;
        }

        .bulk-exports-page .table-card .card-header {
            background: linear-gradient(90deg, var(--brand-start), var(--brand-end));
            color: #fff;
            border-top-left-radius: .9rem;
            border-top-right-radius: .9rem;
            padding: 1rem 1.35rem;
        }

        .bulk-exports-page table#exportsTable thead th {
            background-color: #eef3fb;
            color: #1b2a4a;
            font-size: .8rem;
            text-transform: uppercase;
            letter-spacing: .02em;
            white-space: nowrap;
            border-bottom: 2px solid #dbe4f3;
        }

        .bulk-exports-page table#exportsTable tbody tr:hover {
            background-color: #f4f8ff;
        }

        .bulk-exports-page table#exportsTable td {
            vertical-align: middle;
            font-size: .875rem;
        }

        .bulk-exports-page .filter-chip {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            background: #eef3fb;
            color: #1b2a4a;
            border-radius: .4rem;
            padding: .2rem .55rem;
            font-size: .78rem;
            margin: .1rem .2rem .1rem 0;
        }

        .bulk-exports-page .count-badge {
            background: #e6f4ea;
            color: #1e7e34;
            font-weight: 700;
            border-radius: .5rem;
            padding: .3rem .6rem;
            font-size: .85rem;
        }

        .bulk-exports-page .file-link {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
        }

        .bulk-exports-page .empty-state {
            padding: 3rem 1rem;
            text-align: center;
            color: #6b7280;
        }

        .bulk-exports-page .empty-state i {
            font-size: 2.5rem;
            color: #c9d4e8;
            margin-bottom: .75rem;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid mt-4 bulk-exports-page">

        {{-- Success message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Page header --}}
        <div class="page-header mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h2 class="mb-1"><i class="fa-solid fa-file-zipper text-primary me-2"></i>Bulk PDF Export</h2>
                <p class="text-muted mb-0">Generate ZIP archives of certificates filtered by sector, district,
                    school and class.</p>
            </div>
        </div>

        {{-- Filters + Generate --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="section-title mb-3"><i class="fa-solid fa-filter me-1"></i> Filters</div>
                <form action="{{ route('bulk-exports.generate') }}" method="POST" class="row g-3 align-items-end">
                    @csrf
                    <div class="col-12 col-md-6 col-xl-3">
                        <label class="form-label"><i class="fa-solid fa-industry me-1"></i>Sector</label>
                        <select name="sector" id="sector" class="form-select">
                            <option value="">All Sectors</option>
                            @foreach (\App\Models\Certificate::distinct()->pluck('ssc_name') as $s)
                                <option value="{{ $s }}" {{ old('sector') == $s ? 'selected' : '' }}>
                                    {{ $s }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-6 col-xl-3">
                        <label class="form-label"><i class="fa-solid fa-map-location-dot me-1"></i>District</label>
                        <select name="district" id="district" class="form-select" disabled>
                            <option value="">All Districts</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-6 col-xl-3">
                        <label class="form-label"><i class="fa-solid fa-school me-1"></i>School</label>
                        <select name="school" id="school" class="form-select" disabled>
                            <option value="">All Schools</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-6 col-xl-2">
                        <label class="form-label"><i class="fa-solid fa-layer-group me-1"></i>Class / Std</label>
                        <select name="class_standard" id="class_standard" class="form-select" disabled>
                            <option value="">All Classes</option>
                        </select>
                    </div>

                    <div class="col-12 col-xl-1 d-flex align-items-center">
                        <button id="generateBtn" class="btn btn-action btn-success w-100" disabled>
                            <i class="fa-solid fa-bolt"></i>
                        </button>
                    </div>

                    <div class="col-12">
                        <span id="bulkWarning" class="text-danger small" style="display:none;">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i>Generating ZIP&mdash;please don&rsquo;t
                            refresh the page.
                        </span>
                    </div>
                </form>
            </div>
        </div>

        {{-- History --}}
        <div class="card table-card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span class="fw-semibold"><i class="fa-solid fa-clock-rotate-left me-2"></i>Export History</span>
                @if ($exports->count())
                    <button id="deleteAllBtn" type="button" class="btn btn-sm btn-light text-danger fw-semibold">
                        <i class="fa-solid fa-trash-can me-1"></i>Delete All
                    </button>
                @endif
                {{-- Hidden form to delete all --}}
                <form id="deleteAllForm" action="{{ route('bulk-exports.destroyAll') }}" method="POST"
                    style="display:none;">
                    @csrf @method('DELETE')
                </form>
            </div>

            <div class="card-body table-responsive">
                @if ($exports->count())
                    <table id="exportsTable" class="table table-bordered table-striped align-middle mb-0">
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
                                        <span class="filter-chip"><i class="fa-solid fa-industry"></i>{{ $ex->sector ?: 'All' }}</span>
                                        <span class="filter-chip"><i class="fa-solid fa-map-location-dot"></i>{{ $ex->district ?: 'All' }}</span>
                                        <span class="filter-chip"><i class="fa-solid fa-school"></i>{{ $ex->school_code ?: 'All' }}</span>
                                        <span class="filter-chip"><i class="fa-solid fa-layer-group"></i>{{ $ex->class_standard ?: 'All' }}</span>
                                    </td>
                                    <td><span class="count-badge">{{ $ex->record_count }}</span></td>
                                    <td>
                                        <a href="{{ route('bulk-exports.download', $ex->id) }}"
                                            class="btn btn-sm btn-primary file-link">
                                            <i class="fa-solid fa-download"></i>{{ $ex->file_name }}
                                        </a>
                                    </td>
                                    <td>
                                        <i class="fa-regular fa-clock me-1 text-muted"></i>{{ $ex->created_at->timezone(config('app.timezone'))->isoFormat('LLL') }}
                                    </td>
                                    <td>
                                        {{-- Delete single entry --}}
                                        <button class="btn btn-sm btn-outline-danger deleteBtn" data-id="{{ $ex->id }}">
                                            <i class="fa-solid fa-trash-can"></i>
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
                    <div class="mt-3">
                        {{ $exports->links() }}
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fa-solid fa-box-open d-block"></i>
                        <p class="mb-0 fw-semibold">No exports yet</p>
                        <p class="mb-0 small">Use the filters above and click generate to create your first bulk PDF
                            ZIP.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
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
                generateBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
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

            if (deleteAllBtn) {
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
            }

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
