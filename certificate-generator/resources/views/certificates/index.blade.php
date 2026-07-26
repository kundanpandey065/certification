{{-- resources/views/certificates/index.blade.php --}}
@extends('layouts.app')

@section('title', 'All Certificates List')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
    <style>
        .certificates-page {
            --brand-start: #0d47a1;
            --brand-end: #1976d2;
        }

        .certificates-page .page-header h2 {
            font-weight: 700;
            color: #1b2a4a;
        }

        .certificates-page .page-header p {
            font-size: .95rem;
        }

        /* ---- Unified button sizing ---- */
        .certificates-page .btn-action {
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

        .certificates-page .btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            padding: 0;
            border-radius: .5rem;
            font-size: .85rem;
        }

        .certificates-page .action-btns {
            display: flex;
            align-items: center;
            gap: .35rem;
            flex-wrap: nowrap;
        }

        .certificates-page .form-select,
        .certificates-page .form-control {
            height: 42px;
            border-radius: .55rem;
        }

        .certificates-page .form-label {
            font-size: .8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .03em;
            color: #6b7280;
            margin-bottom: .35rem;
        }

        .certificates-page .card {
            border: none;
            border-radius: .9rem;
            box-shadow: 0 2px 10px rgba(20, 30, 60, .06);
        }

        .certificates-page .section-title {
            font-size: .8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #6b7280;
        }

        .certificates-page .import-dropzone {
            border: 1.5px dashed #b6c6e3;
            border-radius: .75rem;
            background: #f6f9ff;
            padding: 1rem 1.25rem;
            cursor: pointer;
            transition: border-color .15s ease, background-color .15s ease;
        }

        .certificates-page .import-dropzone:hover,
        .certificates-page .import-dropzone.is-dragover {
            border-color: var(--brand-end);
            background: #eef4ff;
        }

        .certificates-page .import-dropzone-icon {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e3ecfc;
            color: var(--brand-start);
            font-size: 1.3rem;
        }

        .certificates-page .import-requirements {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: .82rem;
            color: #5c6b81;
            display: flex;
            flex-direction: column;
            gap: .4rem;
        }

        .certificates-page .import-requirements li i {
            width: 16px;
        }

        .certificates-page .table-card .card-header {
            background: linear-gradient(90deg, var(--brand-start), var(--brand-end));
            color: #fff;
            border-top-left-radius: .9rem;
            border-top-right-radius: .9rem;
            padding: 1rem 1.35rem;
        }

        .certificates-page table#myTable thead th {
            background-color: #eef3fb;
            color: #1b2a4a;
            font-size: .8rem;
            text-transform: uppercase;
            letter-spacing: .02em;
            white-space: nowrap;
            border-bottom: 2px solid #dbe4f3;
        }

        .certificates-page table#myTable tbody tr:hover {
            background-color: #f4f8ff;
        }

        .certificates-page table#myTable td {
            vertical-align: middle;
            font-size: .875rem;
        }

        .certificates-page .badge-gender-m {
            background-color: #0d6efd;
        }

        .certificates-page .badge-gender-f {
            background-color: #d63384;
        }

        .certificates-page .badge-gender-o {
            background-color: #6c757d;
        }

        .certificates-page .badge-level {
            background-color: #0dcaf0;
            color: #063846;
        }

        .certificates-page .dataTables_wrapper .dataTables_filter input,
        .certificates-page .dataTables_wrapper .dataTables_length select {
            border-radius: .5rem;
            border: 1px solid #dbe4f3;
        }

        .certificates-page .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: linear-gradient(90deg, var(--brand-start), var(--brand-end)) !important;
            border: none !important;
            color: #fff !important;
            border-radius: .4rem;
        }

        .certificates-page .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: .4rem;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid mt-4 certificates-page">

        {{-- Success message --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Page header --}}
        <div class="page-header mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h2 class="mb-1"><i class="fa-solid fa-certificate text-primary me-2"></i>All Certificates</h2>
                <p class="text-muted mb-0">Browse, filter and manage every issued certificate in one place.</p>
            </div>
            <div>
                <button id="deleteAllBtn" type="button" class="btn btn-action btn-danger">
                    <i class="fa-solid fa-trash-can"></i> Delete All
                </button>
                <form id="deleteAllForm" action="{{ route('certificates.deleteAll') }}" method="POST"
                    style="display:none;">
                    @csrf
                </form>
            </div>
        </div>

        <div class="row g-3 mb-4">
            {{-- Filters --}}
            <div class="col-12 col-xl-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="section-title mb-3"><i class="fa-solid fa-filter me-1"></i> Filters</div>
                        <div class="d-flex flex-column gap-3">
                            <div>
                                <label class="form-label"><i class="fa-solid fa-industry me-1"></i>Sector</label>
                                <select id="sector" class="form-select">
                                    <option value="">All Sectors</option>
                                    @foreach ($sectors as $s)
                                        <option value="{{ $s }}">{{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="form-label"><i class="fa-solid fa-map-location-dot me-1"></i>District</label>
                                <select id="district" class="form-select" disabled>
                                    <option value="">All Districts</option>
                                </select>
                            </div>

                            <div>
                                <label class="form-label"><i class="fa-solid fa-school me-1"></i>School</label>
                                <select id="school" class="form-select" disabled>
                                    <option value="">All Schools</option>
                                </select>
                            </div>

                            <div>
                                <label class="form-label"><i class="fa-solid fa-layer-group me-1"></i>Class /
                                    Std</label>
                                <select id="class_standard" class="form-select" disabled>
                                    <option value="">All Classes</option>
                                </select>
                            </div>

                            <div>
                                <button id="filterBtn" class="btn btn-action btn-primary w-100">
                                    <i class="fa-solid fa-magnifying-glass"></i> Apply Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Import --}}
            <div class="col-12 col-xl-8">
                <div class="card">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="section-title mb-3"><i class="fa-solid fa-file-import me-1"></i> Import Data
                        </div>

                        <form id="importForm" action="{{ route('certificates.import') }}" method="POST"
                            enctype="multipart/form-data" class="d-flex flex-column">
                            @csrf
                            <label for="importFile"
                                class="import-dropzone d-flex flex-column align-items-center justify-content-center text-center gap-2 mb-3">
                                <div class="import-dropzone-icon">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                </div>
                                <div>
                                    <span class="fw-semibold text-primary">Click to browse</span>
                                    <span class="text-muted"> or drag & drop a file here</span>
                                </div>
                                <span id="importFileName" class="text-muted small">No file selected</span>
                                <input type="file" id="importFile" name="file" accept=".csv,.xlsx,.xls"
                                    class="d-none" required>
                            </label>

                            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                                <ul class="import-requirements">
                                    <li><i class="fa-solid fa-check text-success"></i>Accepted formats: CSV, XLSX,
                                        XLS</li>
                                    <li><i class="fa-solid fa-check text-success"></i>First row should contain
                                        column headers</li>
                                </ul>
                                <button type="submit" class="btn btn-action btn-primary flex-shrink-0">
                                    <i class="fa-solid fa-upload"></i> Import
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hidden form for individual delete; we'll swap its action dynamically --}}
        <form id="deleteForm" method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>

        {{-- DataTable --}}
        <div class="card table-card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span class="fw-semibold"><i class="fa-solid fa-list me-2"></i>Certificate Records</span>
            </div>
            <div class="card-body table-responsive">
                <table id="myTable" class="table table-bordered table-striped align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>District</th>
                            <th>School Name</th>
                            <th>Candidate Name</th>
                            <th>Gender</th>
                            <th>Class/Std</th>
                            <th>Aadhaar</th>
                            <th>School Code</th>
                            <th>Roll No.</th>
                            <th>Father's Name</th>
                            <th>SSC Name</th>
                            <th>Job Role</th>
                            <th>Level</th>
                            <th>Candidate ID</th>
                            <th>Certificate No</th>
                            <th>Date of Issue</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- DataTables JS --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>

    <script>
        $(document).ready(function() {

            // URLs for dependent dropdowns
            const districtsUrl = '{{ route('certificates.districts') }}';
            const schoolsUrl = '{{ route('certificates.schools') }}';

            // 1) Initialize DataTable and store in a variable
            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                searching: true,
                ajax: {
                    url: '{{ route('certificates.index') }}',
                    dataType: 'json',
                    data: function(d) {
                        d.sector = $('#sector').val();
                        d.district = $('#district').val();
                        d.school = $('#school').val();
                    },
                    error: function(xhr) {
                        console.error('DataTables AJAX error:', xhr.responseText);
                    }
                },
                columns: [{
                        // client‐side row counter
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: (data, type, row, meta) => {

                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'district',
                        name: 'district'
                    },
                    {
                        data: 'school_name',
                        name: 'school_name'
                    },
                    {
                        data: 'candidate_name',
                        name: 'candidate_name'
                    },
                    {
                        data: 'gender',
                        name: 'gender',
                        render: function(data) {
                            if (!data) return '';
                            const v = data.toString().trim().toLowerCase();
                            const cls = v.startsWith('m') ? 'badge-gender-m' :
                                v.startsWith('f') ? 'badge-gender-f' : 'badge-gender-o';
                            return `<span class="badge rounded-pill ${cls}">${data}</span>`;
                        }
                    },
                    {
                        data: 'class_standard',
                        name: 'class_standard'
                    },
                    {
                        data: 'aadhaar_number',
                        name: 'aadhaar_number'
                    },
                    {
                        data: 'school_code',
                        name: 'school_code'
                    },
                    {
                        data: 'alternate_id',
                        name: 'alternate_id'
                    },
                    {
                        data: 'father_name',
                        name: 'father_name'
                    },
                    {
                        data: 'ssc_name',
                        name: 'ssc_name'
                    },
                    {
                        data: 'job_role',
                        name: 'job_role'
                    },
                    {
                        data: 'level',
                        name: 'level',
                        render: function(data) {
                            if (!data) return '';
                            return `<span class="badge rounded-pill badge-level">${data}</span>`;
                        }
                    },
                    {
                        data: 'candidate_id',
                        name: 'candidate_id'
                    },
                    {
                        data: 'certificate_no',
                        name: 'certificate_no'
                    },
                    {
                        data: 'date_of_issue',
                        name: 'date_of_issue'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Delete All
            $('#deleteAllBtn').on('click', () => {
                Swal.fire({
                    title: 'Delete ALL certificates?',
                    text: 'This will remove every certificate record.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete all',
                    cancelButtonText: 'Cancel'
                }).then(res => {
                    if (res.isConfirmed) {
                        $('#deleteAllForm').submit();
                    }
                });
            });

            // Single delete (delegated)
            $('#myTable tbody').on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Delete this certificate?',
                    text: 'You cannot undo this.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel'
                }).then(res => {
                    if (res.isConfirmed) {
                        // point the hidden form at the right URL and submit via POST
                        $('#deleteForm')
                            .attr('action', `{{ url('/') }}/certificates/${id}/delete`)
                            .submit();
                    }
                });
            });

            // 2) When Sector changes, reload Districts
            $('#sector').on('change', function() {
                const sec = this.value;
                const $district = $('#district').prop('disabled', true).html('<option>Loading…</option>');
                const $school = $('#school').prop('disabled', true).html('<option>All Schools</option>');

                $.getJSON(districtsUrl, {
                    sector: sec
                }, function(list) {
                    let opts = '<option value="">All Districts</option>';
                    list.forEach(function(d) {
                        opts += `<option>${d}</option>`;
                    });
                    $district.html(opts).prop('disabled', false);
                });
            });

            // 3) When District changes, reload School Codes
            $('#district').on('change', function() {
                const sec = $('#sector').val();
                const dist = this.value;
                const $school = $('#school').prop('disabled', true).html('<option>Loading…</option>');

                $.getJSON(schoolsUrl, {
                    sector: sec,
                    district: dist
                }, function(list) {
                    let opts = '<option value="">All Schools</option>';
                    list.forEach(function(s) {
                        opts += `<option>${s}</option>`;
                    });
                    $school.html(opts).prop('disabled', false);
                });
            });

            // 4) When School changes → load Class/Standards
            $('#school').on('change', function() {
                const sec = $('#sector').val(),
                    dist = $('#district').val(),
                    sch = this.value;

                const $class = $('#class_standard')
                    .prop('disabled', true)
                    .html('<option>Loading…</option>');

                $.getJSON('{{ route('certificates.standards') }}', {
                    sector: sec,
                    district: dist,
                    school: sch
                }, function(list) {
                    let opts = '<option value="">All Classes</option>';
                    list.forEach(c => opts += `<option>${c}</option>`);
                    $class.html(opts).prop('disabled', false);
                });
            });


            // 5) When Apply Filters button is clicked, reload the DataTable
            $('#filterBtn').on('click', function() {
                table.ajax.reload();
            });

            // 6) Import dropzone: filename preview + drag & drop support
            const $dropzone = $('.import-dropzone');
            const $fileInput = $('#importFile');
            const $fileName = $('#importFileName');

            function showFileName(files) {
                if (files && files.length) {
                    $fileName.text(files[0].name).removeClass('text-muted').addClass('fw-semibold text-primary');
                } else {
                    $fileName.text('No file selected').removeClass('fw-semibold text-primary').addClass(
                        'text-muted');
                }
            }

            $fileInput.on('change', function() {
                showFileName(this.files);
            });

            $dropzone.on('dragover', function(e) {
                e.preventDefault();
                $(this).addClass('is-dragover');
            });

            $dropzone.on('dragleave drop', function(e) {
                e.preventDefault();
                $(this).removeClass('is-dragover');
            });

            $dropzone.on('drop', function(e) {
                const files = e.originalEvent.dataTransfer.files;
                if (files && files.length) {
                    $fileInput[0].files = files;
                    showFileName(files);
                }
            });
        });
    </script>
@endsection
