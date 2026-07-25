<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Certificate;
use App\Services\CertificateService;
use App\Services\BulkExportService;

class BulkCertificateController extends Controller
{
    public function __construct(
        protected CertificateService $certificateService,
        protected BulkExportService   $bulkExportService
    ) {}

    /**
     * Show filter form + result table.
     * Accepts optional sector, district, school and class_standard.
     */
    public function index(Request $req)
    {
        $sectors = Certificate::distinct()->pluck('ssc_name');

        $filters = [
            'sector'         => $req->get('sector'),
            'district'       => $req->get('district'),
            'school'         => $req->get('school'),
            'class_standard' => $req->get('class_standard'),
        ];

        $results = $this->certificateService->filterCertificates($filters);

        return view('certificates.bulk_download', compact('sectors', 'results', 'filters'));
    }

    /**
     * AJAX: return districts for a given sector.
     */
    public function districts(Request $req)
    {
        $list = Certificate::where('ssc_name', $req->sector)
            ->distinct()
            ->pluck('district');

        return response()->json($list);
    }

    /**
     * AJAX: return school_codes for sector + district.
     */
    public function schools(Request $req)
    {
        $list = Certificate::where('ssc_name',  $req->sector)
            ->where('district',    $req->district)
            ->distinct()
            ->pluck('school_code');

        return response()->json($list);
    }

    /**
     * AJAX: return class_standard for sector + district + school.
     */
    public function standards(Request $req)
    {
        $list = Certificate::where('ssc_name',   $req->sector)
            ->where('district',     $req->district)
            ->where('school_code',  $req->school)
            ->distinct()
            ->pluck('class_standard');

        return response()->json($list);
    }

    /**
     * Handle the bulk‐export generation (up to 100 PDFs).
     */
    public function generate(Request $request)
    {
        // Collect filters, now including class_standard
        $filters = $request->only([
            'sector',
            'district',
            'school',
            'class_standard',
        ]);

        // Delegate to BulkExportService
        $export = $this->bulkExportService->generateBulkExport($filters);

        return redirect()
            ->route('bulk-exports.index')
            ->with('success', "Bulk ZIP created: {$export->file_name}");
    }
}
