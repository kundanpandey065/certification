<?php

namespace App\Services;

use App\Repositories\CertificateRepositoryInterface;
use App\Repositories\BulkExportRepositoryInterface;
use App\Models\BulkExport;
use ZipArchive;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BulkExportService
{
    public function __construct(
        protected CertificateService $certificateService,
        protected CertificateRepositoryInterface $certRepo,
        protected BulkExportRepositoryInterface $exportRepo
    ) {}

    /**
     * Generate a ZIP of up to 100 PDFs for the given filters.
     * Returns the BulkExport record.
     */
    public function generateBulkExport(array $filters, int $limit = 100)
    {
        // 1) Fetch certificates query and data
        $query = $this->certRepo->filterQuery($filters);
        $certs = $query->limit($limit)->get();
        $count = $certs->count();

        // 2) Build filename (now includes class_standard)
        // $sec  = $filters['sector']          ?: 'all';
        // $dist = $filters['district']        ?: 'all';
        // $sch  = $filters['school']          ?: 'all';
        // $cls  = $filters['class_standard']  ?: 'all';

        // $fileName = sprintf(
        //     'CERTIFICATES_%s_%s_%s_%s_%d.zip',
        //     str_replace(' ', '-', $sec),
        //     str_replace(' ', '-', $dist),
        //     str_replace(' ', '-', $sch),
        //     str_replace(' ', '-', $cls),
        //     $count
        // );



        // 2) Build a human‑readable, unique filename
        $sec      = $filters['sector']         ?: 'all sectors';
        $dist     = $filters['district']       ?: 'all districts';
        $sch      = $filters['school']         ?: 'all schools';
        $cls      = $filters['class_standard'] ?: 'all classes';
        $PDFcount = $count; // integer count of records

        // Capitalize each word for readability
        $human = fn(string $text): string => ucwords(str_replace(['_', '-'], ' ', $text));

        // Unique timestamp suffix in India time
        $timestamp = now()
            ->setTimezone('Asia/Kolkata')
            ->format('YmdHis');

        $fileName = sprintf(
            'Certificates(%d)–%s–%s–%s–%s–%s.zip',
            $PDFcount,
            $human($sec),
            $human($dist),
            $human($sch),
            $human($cls),
            $timestamp
        );


        // 3) Prepare storage
        $exportsDir = storage_path('app/exports');
        if (!is_dir($exportsDir)) {
            mkdir($exportsDir, 0755, true);
        }
        $zipPath = "{$exportsDir}/{$fileName}";

        // 4) Create ZIP
        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // dd(count($certs));
        // foreach ($certs as $cert) {
        //     $pdf = $this->certificateService->generatePdfContent($cert);
        //     $pdfName = "Certificate_{$cert->certificate_no}.pdf";
        //     $zip->addFromString($pdfName, $pdf);
        // }

        foreach ($certs as $cert) {
            $pdf      = $this->certificateService->generatePdfContent($cert);

            // include the record's ID (or loop index) to guarantee uniqueness
            $pdfName = sprintf(
                '%s.pdf',
                $cert->alternate_id,
            );
            $zip->addFromString($pdfName, $pdf);
        }


        $zip->close();

        // 5) Record export in database
        $now = Carbon::now(env('APP_TIMEZONE'))->toDateTimeString();
        return $this->exportRepo->create([
            'sector'       => $filters['sector'] ?? null,
            'district'     => $filters['district'] ?? null,
            'school_code'  => $filters['school'] ?? null,
            'class_standard' => $filters['class_standard'] ?? null,
            'record_count' => $count,
            'file_name'    => $fileName,
            'file_path'    => "exports/{$fileName}",
            'created_at'     => $now,
            'updated_at'     => null,
        ]);
    }
    /** List past exports paginated */
    public function list(int $perPage = 15)
    {
        return $this->exportRepo->paginate($perPage);
    }

    /** Find a single export */
    public function get(int $id)
    {
        return $this->exportRepo->find($id);
    }

    public function delete(int $id): bool
    {
        $export = $this->exportRepo->find($id);
        if (! $export) {
            return false;
        }

        $relative = $export->file_path;                // e.g. "exports/Certificates(...).zip"
        $fullPath = storage_path("app/{$relative}");   // full filesystem path

        // 1) Try Storage facade first
        if (Storage::disk('local')->exists($relative)) {
            Storage::disk('local')->delete($relative);
        }
        // 2) Fallback to unlink() if Storage didn’t find it
        elseif (file_exists($fullPath)) {
            @unlink($fullPath);
        }
        // 3) If still missing, log a warning
        else {
            Log::warning("BulkExportService::delete — file not found", [
                'relative' => $relative,
                'fullPath' => $fullPath,
            ]);
        }

        // 4) Now remove the database record
        return $this->exportRepo->delete($id);
    }

    public function deleteAll(): int
    {
        $all = BulkExport::all();
        $deletedCount = 0;

        foreach ($all as $export) {
            $relative = $export->file_path;
            $fullPath = storage_path("app/{$relative}");

            if (Storage::disk('local')->exists($relative)) {
                Storage::disk('local')->delete($relative);
            } elseif (file_exists($fullPath)) {
                @unlink($fullPath);
            } else {
                Log::warning("BulkExportService::deleteAll — file not found", [
                    'relative' => $relative,
                    'fullPath' => $fullPath,
                ]);
            }

            $deletedCount++;
        }

        // Remove all DB records in one go
        $this->exportRepo->deleteAll();

        return $deletedCount;
    }
}
