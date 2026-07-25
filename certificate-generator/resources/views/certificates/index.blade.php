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

        /* ---- Overview stats matrix ---- */
        .certificates-page .stat-tile {
            background: #fff;
            border-radius: .9rem;
            box-shadow: 0 2px 10px rgba(20, 30, 60, .06);
            padding: 1.1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: .9rem;
            height: 100%;
        }

        .certificates-page .stat-tile .icon-wrap {
            width: 46px;
            height: 46px;
            min-width: 46px;
            border-radius: .7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #fff;
        }

        .certificates-page .stat-tile .stat-value {
            font-size: 1.45rem;
            font-weight: 700;
            color: #1b2a4a;
            line-height: 1.15;
        }

        .certificates-page .stat-tile .stat-label {
            font-size: .72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .03em;
            color: #6b7280;
            margin-top: .1rem;
        }

        .certificates-page .icon-blue   { background: linear-gradient(135deg, #0d47a1, #1976d2); }
        .certificates-page .icon-aqua   { background: linear-gradient(135deg, #0e9e6e, #1baf7a); }
        .certificates-page .icon-orange { background: linear-gradient(135deg, #d1531f, #eb6834); }
        .certificates-page .icon-violet { background: linear-gradient(135deg, #382b80, #4a3aa7); }
        .certificates-page .icon-amber  { background: linear-gradient(135deg, #c98500, #eda100); }
        .certificates-page .icon-red    { background: linear-gradient(135deg, #c53837, #e34948); }

        .certificates-page .breakdown-card .card-body {
            padding: 1.25rem 1.35rem;
        }

        .certificates-page .segmented-bar {
            display: flex;
            height: 14px;
            border-radius: 999px;
            overflow: hidden;
            background: #eef1f6;
            margin-bottom: 1rem;
            gap: 2px;
        }

        .certificates-page .legend-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: .85rem;
            margin-bottom: .45rem;
        }

        .certificates-page .legend-row:last-child {
            margin-bottom: 0;
        }

        .certificates-page .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: .45rem;
        }

        .certificates-page .breakdown-subhead {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .03em;
            color: #9aa5b6;
            margin-bottom: .6rem;
        }

        .certificates-page .mini-bar-row {
            margin-bottom: .8rem;
        }

        .certificates-page .mini-bar-row:last-child {
            margin-bottom: 0;
        }

        .certificates-page .mini-bar-row .mini-bar-label {
            display: flex;
            justify-content: space-between;
            font-size: .82rem;
            font-weight: 600;
            color: #1b2a4a;
            margin-bottom: .3rem;
        }

        .certificates-page .mini-bar-track {
            height: 10px;
            border-radius: 999px;
            background: #eef1f6;
            overflow: hidden;
        }

        .certificates-page .mini-bar-fill {
            height: 100%;
            border-radius: 999px;
        }

        .certificates-page .breakdown-divider {
            border-top: 1px dashed #e3e8f0;
            margin: 1rem 0;
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

        {{-- Overview stats matrix --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-tile">
                    <div class="icon-wrap icon-blue"><i class="fa-solid fa-certificate"></i></div>
                    <div>
                        <div class="stat-value">{{ number_format($stats['total']) }}</div>
                        <div class="stat-label">Certificates</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-tile">
                    <div class="icon-wrap icon-aqua"><i class="fa-solid fa-map-location-dot"></i></div>
                    <div>
                        <div class="stat-value">{{ number_format($stats['districts']) }}</div>
                        <div class="stat-label">Districts</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-tile">
                    <div class="icon-wrap icon-orange"><i class="fa-solid fa-school"></i></div>
                    <div>
                        <div class="stat-value">{{ number_format($stats['schools']) }}</div>
                        <div class="stat-label">Schools</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-tile">
                    <div class="icon-wrap icon-violet"><i class="fa-solid fa-industry"></i></div>
                    <div>
                        <div class="stat-value">{{ number_format($stats['sectors']) }}</div>
                        <div class="stat-label">Sectors</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-tile">
                    <div class="icon-wrap icon-amber"><i class="fa-solid fa-briefcase"></i></div>
                    <div>
                        <div class="stat-value">{{ number_format($stats['job_roles']) }}</div>
                        <div class="stat-label">Job Roles</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-tile">
                    <div class="icon-wrap icon-red"><i class="fa-solid fa-layer-group"></i></div>
                    <div>
                        <div class="stat-value">{{ number_format($stats['levels']) }}</div>
                        <div class="stat-label">NSQF Levels</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            {{-- Gender distribution --}}
            <div class="col-12 col-xl-4">
                <div class="card breakdown-card h-100">
                    <div class="card-body">
                        <div class="section-title mb-3"><i class="fa-solid fa-venus-mars me-1"></i> Gender
                            Distribution</div>

                        @php
                            $genderColors = [
                                'male' => '#0d6efd',
                                'female' => '#d63384',
                            ];
                        @endphp

                        <div class="segmented-bar">
                            @forelse ($stats['gender'] as $g)
                                <span
                                    style="width: {{ $g['pct'] }}%; background-color: {{ $genderColors[strtolower($g['label'])] ?? '#6c757d' }};"></span>
                            @empty
                                <span style="width: 100%; background-color: #e3e8f0;"></span>
                            @endforelse
                        </div>

                        @forelse ($stats['gender'] as $g)
                            <div class="legend-row">
                                <span>
                                    <span class="legend-dot"
                                        style="background-color: {{ $genderColors[strtolower($g['label'])] ?? '#6c757d' }};"></span>
                                    {{ $g['label'] }}
                                </span>
                                <span class="fw-semibold">{{ number_format($g['count']) }}
                                    <span class="text-muted">({{ $g['pct'] }}%)</span></span>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No data available yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Class & NSQF level mix --}}
            <div class="col-12 col-xl-4">
                <div class="card breakdown-card h-100">
                    <div class="card-body">
                        <div class="section-title mb-3"><i class="fa-solid fa-layer-group me-1"></i> Class & Level
                            Mix</div>

                        @if ($stats['class']->isNotEmpty())
                            <div class="breakdown-subhead">Class / Std</div>
                            @foreach ($stats['class'] as $c)
                                <div class="mini-bar-row">
                                    <div class="mini-bar-label">
                                        <span>{{ $c['label'] }}</span>
                                        <span>{{ number_format($c['count']) }}</span>
                                    </div>
                                    <div class="mini-bar-track">
                                        <div class="mini-bar-fill"
                                            style="width: {{ $c['pct'] }}%; background: linear-gradient(90deg, #0d47a1, #1976d2);">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        @if ($stats['class']->isNotEmpty() && $stats['level']->isNotEmpty())
                            <div class="breakdown-divider"></div>
                        @endif

                        @if ($stats['level']->isNotEmpty())
                            <div class="breakdown-subhead">NSQF Level</div>
                            @foreach ($stats['level'] as $l)
                                <div class="mini-bar-row">
                                    <div class="mini-bar-label">
                                        <span>{{ $l['label'] }}</span>
                                        <span>{{ number_format($l['count']) }}</span>
                                    </div>
                                    <div class="mini-bar-track">
                                        <div class="mini-bar-fill"
                                            style="width: {{ $l['pct'] }}%; background: linear-gradient(90deg, #0aa5c0, #0dcaf0);">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        @if ($stats['class']->isEmpty() && $stats['level']->isEmpty())
                            <p class="text-muted mb-0">No data available yet.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Top districts --}}
            <div class="col-12 col-xl-4">
                <div class="card breakdown-card h-100">
                    <div class="card-body">
                        <div class="section-title mb-3"><i class="fa-solid fa-ranking-star me-1"></i> Top Districts
                        </div>
                        @forelse ($stats['top_districts'] as $d)
                            <div class="mini-bar-row">
                                <div class="mini-bar-label">
                                    <span>{{ $d['label'] }}</span>
                                    <span>{{ number_format($d['count']) }}</span>
                                </div>
                                <div class="mini-bar-track">
                                    <div class="mini-bar-fill"
                                        style="width: {{ $d['pct'] }}%; background: linear-gradient(90deg, #0d47a1, #1976d2);">
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No data available yet.</p>
                        @endforelse
                    </div>
                </div>
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
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="section-title mb-3"><i class="fa-solid fa-file-import me-1"></i> Import Data
                        </div>
                        <form action="{{ route('certificates.import') }}" method="POST"
                            enctype="multipart/form-data"
                            class="import-dropzone d-flex flex-column flex-md-row align-items-md-center gap-2 flex-grow-1">
                            @csrf
                            <input type="file" name="file" accept=".csv" class="form-control" required>
                            <button class="btn btn-action btn-primary">
                                <i class="fa-solid fa-upload"></i> Import CSV
                            </button>
                        </form>
                        <span class="text-muted small mt-2"><i class="fa-solid fa-circle-exclamation me-1"></i>CSV
                            file import only.</span>
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
        });
    </script>
@endsection
