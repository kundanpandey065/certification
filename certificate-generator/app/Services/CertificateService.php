<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Certificate;
use App\Repositories\CertificateRepositoryInterface;
use Maatwebsite\Excel\Excel;
use Illuminate\Http\UploadedFile;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class CertificateService
{
    public function __construct(
        protected CertificateRepositoryInterface $repo,
        protected Excel $excel
    ) {}

    public function import(UploadedFile $file): void
    {
        $collection = $this->excel->toCollection(null, $file)->first();
        foreach ($collection as $row) {
            // skip first row if it contains headers
            if (isset($row[0]) && $row[0] === 'Sl. No.') {
                continue; // Skip the header row
            }
            $data = [
                'sl_no'                  => $row[0] ?? null,
                'state_ut'               => $row[1] ?? null,
                'district'               => $row[2] ?? null,
                'school_name'            => $row[3] ?? null,
                'candidate_name'         => $row[5] ?? null,
                'class_standard'         => $row[6] ?? null,
                'aadhaar_number'         => $row[7] ?? null,
                'school_code'            => $row[8] ?? null,
                'alternate_id'           => $row[9] ?? null,
                'father_name'            => $row[10] ?? null,
                'ssc_name'               => $row[11] ?? null,
                'job_role'               => $row[12] ?? null,
                'level'                  => $row[13] ?? null,
                'pass_fail'              => $row[14] ?? null,
                'training_type'          => $row[15] ?? null,
                'candidate_organization' => $row[16] ?? null,
                'candidate_id'           => $row[17] ?? null,
                'certificate_no'         => $row[18] ?? null,
                'issuing_authority'      => $row[19] ?? null,
                'date_of_issue'          => $row[20] ? \Carbon\Carbon::parse($row[20])->format('Y-m-d') : null,
                'gender'      => $row[21] ?? null,

            ];
            $this->repo->create($data);
        }
    }

    // public function listDataTable()
    // {
    //     $query = $this->repo->query();
    //     return datatables()->eloquent($query)
    //         ->addIndexColumn()
    //         ->make(true);
    // }
    public function list(int $perPage = 100)
    {
        return $this->repo->paginate($perPage);
    }

    public function get(int $id)
    {
        return $this->repo->find($id);
    }

    public function filterCertificates(array $filters)
    {
        return $this->repo->filter($filters);
    }

    /** Bulk‐ZIP all matching certificates into one download */
    // public function bulkDownloadZip(array $filters)
    // {
    //     $certs = $this->filterCertificates($filters);

    //     $tmpFile = tempnam(sys_get_temp_dir(), 'certs') . '.zip';
    //     $zip = new \ZipArchive();
    //     $zip->open($tmpFile, \ZipArchive::CREATE);

    //     foreach ($certs as $cert) {
    //         // prepare the same data array you use in single‐download
    //         $data = $this->prepareCertificateData($cert);

    //         // render PDF to string
    //         $pdf = PDF::loadView('certificate', $data)
    //             ->setPaper('letter', 'landscape')
    //             ->addInfo([
    //                 'Title'   => "Certificate {$cert->certificate_no}",
    //                 'Author'  => 'SVSU',
    //                 'Subject' => 'Skill Certificate',
    //                 'Creator' => 'SVSU Portal',
    //             ])
    //             ->output();

    //         $zip->addFromString(
    //             "Certificate_{$cert->certificate_no}.pdf",
    //             $pdf
    //         );
    //     }

    //     $zip->close();

    //     return response()
    //         ->download($tmpFile, 'certificates.zip')
    //         ->deleteFileAfterSend(true);
    // }


    public function listDataTable(Request $request)
    {
        // Extract filter params
        $filters = $request->only(['sector', 'district', 'school', 'class_standard']);
        $query   = $this->repo->filterQuery($filters);

        return DataTables::eloquent($query)
            ->filter(function ($query) use ($request) {
                $search = $request->input('search.value');
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('district',       'like', "%{$search}%")
                            ->orWhere('school_name',    'like', "%{$search}%")
                            ->orWhere('candidate_name', 'like', "%{$search}%")
                            ->orWhere('gender',          'like', "%{$search}%")
                            ->orWhere('class_standard', 'like', "%{$search}%")
                            ->orWhere('aadhaar_number', 'like', "%{$search}%")
                            ->orWhere('alternate_id',   'like', "%{$search}%")
                            ->orWhere('father_name',    'like', "%{$search}%")
                            ->orWhere('ssc_name',       'like', "%{$search}%")
                            ->orWhere('job_role',       'like', "%{$search}%")
                            ->orWhere('level',          'like', "%{$search}%")
                            ->orWhere('candidate_id',   'like', "%{$search}%")
                            ->orWhere('certificate_no', 'like', "%{$search}%")
                            ->orWhere('date_of_issue',  'like', "%{$search}%");
                    });
                }
            }, true)
            ->addColumn('action', function ($cert) {
                $viewUrl     = route('certificates.download', [$cert->id, 'view']);
                $downloadUrl = route('certificates.download', [$cert->id, 'download']);
                return "
                  <div class='action-btns'>
                    <a target='_blank' href='{$viewUrl}' class='btn btn-icon btn-info' title='View'>
                      <i class='fa-regular fa-eye'></i>
                    </a>
                    <a href='{$downloadUrl}' class='btn btn-icon btn-primary' title='Download'>
                      <i class='fa-solid fa-download'></i>
                    </a>
                    <button class='btn btn-icon btn-danger delete-btn' data-id='{$cert->id}' title='Delete'>
                      <i class='fa-solid fa-trash'></i>
                    </button>
                  </div>
                ";
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function prepareCertificateData($cert): array
    {
        $candidateName = $cert->candidate_name;
        $gender = $cert->gender;
        $fatherName    = $cert->father_name;
        $sscName       = $cert->ssc_name;
        $jobRole       = $cert->job_role;
        $certNo        = $cert->certificate_no;
        $issueDate     = Carbon::parse($cert->date_of_issue)->format('d/m/Y');
        $aadhaarMasked = 'XXXX XXXX ' . substr($cert->aadhaar_number, -4);
        $rollNo        = $cert->alternate_id;

        // QR payload
        $qrPayload = implode("\n", [
            "Sector: {$sscName}",
            "Job Role: {$jobRole}",
            "NSQF Level: {$cert->level}",
            "Aadhar No.: {$aadhaarMasked}",
            "Date of Issuance: {$issueDate}",
            "Candidate Name: {$candidateName}",
            "Father Name: {$fatherName}",
            "School Name: {$cert->school_name}",
            "Roll No.: {$rollNo}",
            "Issuing Authority: {$cert->issuing_authority}",
        ]);

        $qr = Builder::create()
            ->writer(new PngWriter())
            ->data($qrPayload)
            ->size(150)
            ->margin(10)
            ->build();
        $qrDataUri = $qr->getDataUri();

        // Helper to embed images
        $encode = function (string $source): ?string {
            // If it's a URL, fetch via HTTP
            if (filter_var($source, FILTER_VALIDATE_URL)) {
                $resp = Http::get($source);
                if ($resp->ok()) {
                    $mime = $resp->header('Content-Type') ?: 'application/octet-stream';
                    return 'data:' . $mime . ';base64,' . base64_encode($resp->body());
                }
            }
            // Otherwise assume it's a local path
            if (file_exists($source)) {
                $ext = strtolower(pathinfo($source, PATHINFO_EXTENSION));
                $mime = match ($ext) {
                    'svg'  => 'image/svg+xml',
                    'png'  => 'image/png',
                    'jpg', 'jpeg' => 'image/jpeg',
                    'webp' => 'image/webp',
                    default => 'application/octet-stream',
                };
                return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($source));
            }
            return null;
        };

        return [
            'candidateName' => $candidateName,
            'gender' => $gender,
            'fatherName'    => $fatherName,
            'ssc_name'      => $sscName,
            'jobRole'       => $jobRole,
            'nsqfLevel'     => $cert->level,
            'issueDate'     => $issueDate,
            'aadhaarMasked' => $aadhaarMasked,
            'certificate_no' => $certNo,
            'rollNo'        => $rollNo,
            'qrDataUri'     => $qrDataUri,
            'qrSuffix'      => $certNo,
            'logoLeft'      => $encode(public_path('images/skill_india-logo.png')),
            'logoCenter'    => $encode(public_path('images/satyamev-logo.png')),
            'logoRight'     => $encode(public_path('images/svsu-logo.png')),
            'hbselogo'      => $encode(public_path('images/hbse-logo.webp')),
            'signature1'    => $encode(public_path('images/coe-signature.png')),
            'signature2'    => $encode(public_path('images/secratory.png')),
        ];
    }

    /**
     * Generate PDF content for a given certificate model.
     */
    public function generatePdfContent($cert): string
    {
        $data = $this->prepareCertificateData($cert);

        $pdf = PDF::loadView('certificate', $data)
            ->setPaper('letter', 'landscape')
            ->addInfo([
                'Title'    => "Certificate {$data['certificate_no']}",
                'Author'   => 'Shri Vishwakarma Skill University',
                'Subject'  => 'Skill Development Certificate',
                'Keywords' => 'certificate, skill, PDF, SVSU',
                'Creator'  => 'SVSU Certification Portal',
            ]);

        return $pdf->output();
    }

    /**
     * Aggregate, dashboard-ready overview metrics for the whole dataset.
     */
    public function getOverviewStats(): array
    {
        $countDistinct = fn(string $column) => Certificate::whereNotNull($column)
            ->where($column, '!=', '')
            ->distinct()
            ->count($column);

        $breakdown = function (string $column, ?callable $labelFn = null, ?int $limit = null, bool $byCount = false) {
            $query = Certificate::selectRaw("{$column}, COUNT(*) as c")
                ->whereNotNull($column)
                ->where($column, '!=', '')
                ->groupBy($column);

            $query = $byCount ? $query->orderByDesc('c') : $query->orderBy($column);
            if ($limit) {
                $query->limit($limit);
            }

            $counts = $query->pluck('c', $column);
            $max = $counts->max() ?: 1;

            return $counts->map(fn($count, $key) => [
                'label' => $labelFn ? $labelFn($key) : $key,
                'count' => $count,
                'pct'   => (int) round($count / $max * 100),
            ])->values();
        };

        return [
            'total'         => Certificate::count(),
            'districts'     => $countDistinct('district'),
            'schools'       => $countDistinct('school_code'),
            'sectors'       => $countDistinct('ssc_name'),
            'job_roles'     => $countDistinct('job_role'),
            'levels'        => $countDistinct('level'),
            'gender'        => $breakdown('gender'),
            'class'         => $breakdown('class_standard'),
            'level'         => $breakdown('level', fn($k) => "Level {$k}"),
            'top_districts' => $breakdown('district', byCount: true, limit: 5),
        ];
    }

    public function delete(int $id): bool
    {
        return $this->repo->delete($id);
    }

    /**
     * Delete all certificate records.
     * Returns number of deleted rows.
     */
    public function deleteAll(): int
    {
        return $this->repo->deleteAll();
    }
}
