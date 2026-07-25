<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BulkExportService;

class BulkExportController extends Controller
{
    public function __construct(protected BulkExportService $bulkExportService) {}

    /**
     * Show bulk export page with history.
     */
    public function index()
    {
        $exports = $this->bulkExportService->list();
        return view('certificates.bulk_exports', compact('exports'));
    }

    /**
     * Handle bulk export generation.
     */
    public function generate(Request $request)
    {
        $filters = $request->only([
            'sector',
            'district',
            'school',
            'class_standard'
        ]);

        $export = $this->bulkExportService->generateBulkExport($filters);

        return redirect()
            ->route('bulk-exports.index')
            ->with('success', "Bulk ZIP created: {$export->file_name}");
    }

    /**
     * Download a stored bulk export ZIP.
     */
    public function download(int $id)
    {
        $export = $this->bulkExportService->get($id);
        return response()->download(storage_path("app/{$export->file_path}"));
    }

    public function destroy(int $id)
    {
        $this->bulkExportService->delete($id);
        return redirect()
            ->route('bulk-exports.index')
            ->with('success', 'Deleted export #' . $id);
    }

    public function destroyAll()
    {
        $deleted = $this->bulkExportService->deleteAll();
        return redirect()
            ->route('bulk-exports.index')
            ->with('success', "Deleted all exports ({$deleted} records).");
    }
}
