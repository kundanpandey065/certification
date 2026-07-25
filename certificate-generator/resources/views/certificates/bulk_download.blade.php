@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row g-2">
                    <div class="col">
                        <select id="sector" class="form-select">
                            <option value="">Select Sector</option>
                            @foreach ($sectors as $s)
                                <option>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <select id="district" class="form-select" disabled>
                            <option value="">Select District</option>
                        </select>
                    </div>
                    <div class="col">
                        <select id="school" class="form-select" disabled>
                            <option value="">Select School Code</option>
                        </select>
                    </div>
                    <div class="col">
                        <select id="class_standard" class="form-select" disabled>
                            <option value="">Select Class/Standard</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button id="filterBtn" class="btn btn-primary">Filter</button>
                    </div>
                    <div class="col-auto">
                        <form id="bulkDownloadForm" action="{{ route('certificates.bulk.download') }}" method="POST">
                            @csrf
                            <input type="hidden" name="sector">
                            <input type="hidden" name="district">
                            <input type="hidden" name="school">
                            <input type="hidden" name="class_standard">
                            <button type="submit" class="btn btn-success" {{ $results->isEmpty() ? 'disabled' : '' }}>
                                Bulk Download
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body table-responsive">
                <table id="bulkTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>District</th>
                            <th>School Code</th>
                            <th>Class/Standard</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($results as $i => $cert)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $cert->candidate_name }}</td>
                                <td>{{ $cert->district }}</td>
                                <td>{{ $cert->school_code }}</td>
                                <td>{{ $cert->class_standard }}</td>
                                <td>
                                    <a target="_blank" href="{{ route('certificates.download', [$cert->id, 'view']) }}"
                                        class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sectorSelect = document.getElementById('sector');
            const districtSelect = document.getElementById('district');
            const schoolSelect = document.getElementById('school');
            const filterBtn = document.getElementById('filterBtn');
            const bulkForm = document.getElementById('bulkDownloadForm');
            const baseUrl = '{{ route('certificates.bulk') }}';

            // 1) On Sector change → load Districts
            sectorSelect.addEventListener('change', () => {
                const sector = sectorSelect.value;
                districtSelect.disabled = true;
                schoolSelect.disabled = true;
                districtSelect.innerHTML = '<option>Loading…</option>';
                schoolSelect.innerHTML = '<option value="">Select School Code</option>';

                fetch(`${baseUrl}/districts?sector=${encodeURIComponent(sector)}`)
                    .then(r => r.json())
                    .then(list => {
                        let opts = '<option value="">Select District</option>';
                        list.forEach(d => opts += `<option value="${d}">${d}</option>`);
                        districtSelect.innerHTML = opts;
                        districtSelect.disabled = false;
                    })
                    .catch(() => districtSelect.innerHTML = '<option value="">Error</option>');
            });

            // 2) On District change → load School Codes
            districtSelect.addEventListener('change', () => {
                const sector = sectorSelect.value;
                const district = districtSelect.value;
                schoolSelect.disabled = true;
                schoolSelect.innerHTML = '<option>Loading…</option>';

                fetch(
                        `${baseUrl}/schools?sector=${encodeURIComponent(sector)}&district=${encodeURIComponent(district)}`
                    )
                    .then(r => r.json())
                    .then(list => {
                        let opts = '<option value="">Select School Code</option>';
                        list.forEach(s => opts += `<option value="${s}">${s}</option>`);
                        schoolSelect.innerHTML = opts;
                        schoolSelect.disabled = false;
                    })
                    .catch(() => schoolSelect.innerHTML = '<option value="">Error</option>');
            });

            // 4) On School change → load Class/Standards
            schoolSelect.addEventListener('change', () => {
                const sec = sectorSelect.value;
                const dist = districtSelect.value;
                const sch = schoolSelect.value;

                classSelect.disabled = true;
                classSelect.innerHTML = '<option>Loading…</option>';

                fetch(
                        `${baseUrl}/standards?sector=${encodeURIComponent(sec)}&district=${encodeURIComponent(dist)}&school=${encodeURIComponent(sch)}`)
                    .then(r => r.json())
                    .then(list => {
                        let opts = '<option value="">Select Class/Standard</option>';
                        list.forEach(c => opts += `<option value="${c}">${c}</option>`);
                        classSelect.innerHTML = opts;
                        classSelect.disabled = false;
                    })
                    .catch(() => classSelect.innerHTML = '<option value="">Error</option>');
            });


            // 5) On Filter click → reload page with query params
            filterBtn.addEventListener('click', () => {
                const params = new URLSearchParams();
                if (sectorSelect.value) params.append('sector', sectorSelect.value);
                if (districtSelect.value) params.append('district', districtSelect.value);
                if (schoolSelect.value) params.append('school', schoolSelect.value);
                window.location = `${baseUrl}?${params.toString()}`;
            });

            // 4) Before Bulk Download POST → sync hidden inputs
            bulkForm.addEventListener('submit', () => {
                bulkForm.querySelector('input[name=sector]').value = sectorSelect.value;
                bulkForm.querySelector('input[name=district]').value = districtSelect.value;
                bulkForm.querySelector('input[name=school]').value = schoolSelect.value;
                bulkForm.querySelector('input[name=class_standard]').value = classSelect.value;
            });
        });
    </script>
@endsection
