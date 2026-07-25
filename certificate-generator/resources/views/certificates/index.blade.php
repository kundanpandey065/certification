{{-- resources/views/certificates/index.blade.php --}}
@extends('layouts.app')

@section('title', 'All Certificates List')

@section('content')
    <div class="container-fluid mt-4">

        {{-- Success message --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Filters + Import --}}
        <div class="card mb-3">
            <div class="card-body">
                <div class="row g-2 align-items-center">
                    {{-- Title --}}
                    <div class="col-auto">
                        <h5 class="mb-0">All Certificates</h5>
                    </div>

                    {{-- Sector --}}
                    <div class="col-auto">
                        <select id="sector" class="form-select form-select-sm">
                            <option value="">All Sectors</option>
                            @foreach ($sectors as $s)
                                <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- District --}}
                    <div class="col-auto">
                        <select id="district" class="form-select form-select-sm" disabled>
                            <option value="">All Districts</option>
                        </select>
                    </div>

                    {{-- School --}}
                    <div class="col-auto">
                        <select id="school" class="form-select form-select-sm" disabled>
                            <option value="">All Schools</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <select id="class_standard" class="form-select form-select-sm" disabled>
                            <option value="">All Classes</option>
                        </select>
                    </div>

                    {{-- Apply --}}
                    <div class="col-auto">
                        <button id="filterBtn" class="btn btn-sm btn-secondary">
                            Apply Filters
                        </button>
                    </div>



                    {{-- Spacer (push import form right) --}}
                    <div class="col"> {{-- Delete All --}}
                        {{-- Delete All --}}
                        <div class="row mb-2">
                            <div class="col-auto">
                                <button id="deleteAllBtn" type="button" class="btn btn-md btn-danger">
                                    Delete All Certificates
                                </button>
                                <form id="deleteAllForm" action="{{ route('certificates.deleteAll') }}" method="POST"
                                    style="display:none;">
                                    @csrf
                                </form>
                            </div>
                        </div>
                        <form id="deleteForm" method="POST" style="display:none;">
                            @csrf
                        </form>
                    </div>

                    {{-- Import form --}}
                    <div class="col-auto">
                        <form action="{{ route('certificates.import') }}" method="POST" enctype="multipart/form-data"
                            class="d-flex align-items-center g-2">
                            @csrf
                            <input type="file" name="file" accept=".csv" class="form-control form-control-sm"
                                required>

                            <button class="btn btn-sm btn-primary ms-2">
                                Import
                            </button>
                        </form>
                        <span class="text-muted">⚠️ CSV file import only.</span>
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
        <div class="card">
            <div class="card-body table-responsive">
                <table id="myTable" class="table table-bordered table-striped" style="width:100%">
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

    {{-- DataTables CSS/JS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
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
                        name: 'gender'
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
                        name: 'level'
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
                            .attr('action', `{{url('/')}}/certificates/${id}/delete`)
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
