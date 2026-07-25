<?php

namespace App\Http\Controllers;

// use PDF;   // Barryvdh\DomPDF\Facade as PDF;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;
use Endroid\QrCode\Builder\Builder;               // Endroid QR builder
use Endroid\QrCode\Writer\PngWriter;             // The PNG writer
use App\Services\CertificateService;
use Illuminate\Http\Request;
use App\Models\Certificate;


class CertificateController extends Controller
{
    public function __construct(protected CertificateService $service) {}

    public function index(Request $request)
    {
        // Always send sectors for the first dropdown
        $sectors = Certificate::distinct()->pluck('ssc_name');

        // If AJAX, return DataTables JSON
        if ($request->ajax()) {
            return $this->service->listDataTable($request);
        }

        // Overview metrics for the stats matrix
        $stats = $this->service->getOverviewStats();

        // Otherwise serve the main view
        return view('certificates.index', compact('sectors', 'stats'));
    }

    // AJAX: districts for a sector
    public function districts(Request $request)
    {
        $list = Certificate::where('ssc_name', $request->sector)
            ->distinct()
            ->pluck('district');
        return response()->json($list);
    }

    // AJAX: school codes for sector+district
    public function schools(Request $request)
    {
        $list = Certificate::where('ssc_name',  $request->sector)
            ->where('district',   $request->district)
            ->distinct()
            ->pluck('school_code');
        return response()->json($list);
    }

    public function standards(Request $req)
    {
        $list = Certificate::where('ssc_name',   $req->sector)
            ->where('district',   $req->district)
            ->where('school_code', $req->school)
            ->distinct()
            ->pluck('class_standard');
        return response()->json($list);
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls,csv']);
        $this->service->import($request->file('file'));
        return redirect()->route('certificates.index')
            ->with('success', 'Data imported successfully.');
    }


    // public function download(Request $request, $name = null)
    // {
    //     // 1) Prepare dynamic text
    //     $name   = urldecode($name) ?: 'Kimberly Palmerston';
    //     $issuer = 'Bartholomew Henderson';

    //     // 2) Build the static QR payload
    //     $qrPayload = implode("\n", [
    //         'Training Type: Recognition of Prior Learning (RPL)',
    //         "Candidate's Organization: Public Health Engineering Department (PHED), HARYANA",
    //         'Candidate Name: Aamin',
    //         'Aadhaar No.: 999999999999',
    //         'Candidate ID: SVSU/RPL/2022/011',
    //         'Job Role: Water Supply Pump Operator',
    //         'Certificate No.: SVSU/ACD/RPL/2022/011',
    //         'Date of Issue: 22.06.2022',
    //         'Issuing Authority: Shri Vishwakarma Skill University, Dudhola, Palwal Haryana',
    //     ]);

    //     // 3) Generate a 150×150 PNG QR code (Endroid)
    //     $qrResult = Builder::create()
    //         ->writer(new PngWriter())
    //         ->data($qrPayload)
    //         ->size(150)
    //         ->margin(10)
    //         ->build();
    //     // getDataUri() returns "data:image/png;base64,...."
    //     $qrBase64 = $qrResult->getDataUri();

    //     // 4) Fetch remote logo (100×100) and base64-encode it
    //     $logoUrl  = 'https://www.logopeople.in/wp-content/uploads/2013/01/government-of-india-1024x1024.jpg';
    //     $response = Http::get($logoUrl);
    //     if ($response->successful()) {
    //         $logoData   = $response->body();
    //         $logoBase64 = 'data:image/jpeg;base64,' . base64_encode($logoData);
    //     } else {
    //         $logoBase64 = null;
    //     }

    //     // 5) Collect all data for the view
    //     $data = [
    //         'name'       => $name,
    //         'issuer'     => $issuer,
    //         'qrBase64'   => $qrBase64,
    //         'logoBase64' => $logoBase64,
    //         'leftName'   => 'Isabel Mercado',
    //         'leftTitle'  => 'President',
    //         'rightName'  => 'Kimberly Nguyen',
    //         'rightTitle' => 'Vice President',
    //     ];

    //     // 6) Render the PDF
    //     $pdf = PDF::loadView('certificate', $data)
    //         ->setPaper('letter', 'landscape')
    //         ->setOptions(['isRemoteEnabled' => true]);

    //     return $pdf->stream('certificate.pdf');
    // }


    // public function download(Request $request, $name = null)
    // {
    //     // 1) Static certificate data
    //     $candidateName = 'Mandeep S/o Satpal';
    //     $jobRole       = 'Solanaceous Crop Cultivator (AGR/Q0402)';
    //     $nsqfLevel     = '2';
    //     $issueDate     = '2023-01-13';
    //     $aadhaarMasked = '**********2345';
    //     $rollNo        = '10121-1022333137';
    //     $qrSuffix      = '21-22-22275';

    //     // 2) Build the QR payload & image (unchanged)
    //     $qrPayload = implode("\n", [
    //         'Training Type: Recognition of Prior Learning (RPL)',
    //         "Candidate's Organization: Public Health Engineering Department (PHED), HARYANA",
    //         "Candidate Name: {$candidateName}",
    //         "Aadhaar No.: " . str_repeat('*', 10) . substr($aadhaarMasked, -4),
    //         "Roll No.: {$rollNo}",
    //         "Date of Issue: " . \Carbon\Carbon::parse($issueDate)->format('d.m.Y'),
    //     ]);

    //     $qrResult  = Builder::create()
    //         ->writer(new PngWriter())
    //         ->data($qrPayload)
    //         ->size(150)
    //         ->margin(10)
    //         ->build();
    //     $qrDataUri = $qrResult->getDataUri();

    //     // 3) Helper to load & encode either a URL or a local file
    //     $encode = function (string $source): ?string {
    //         // If it's a URL, fetch via HTTP
    //         if (filter_var($source, FILTER_VALIDATE_URL)) {
    //             $resp = Http::get($source);
    //             if ($resp->ok()) {
    //                 $mime = $resp->header('Content-Type') ?: 'application/octet-stream';
    //                 return 'data:' . $mime . ';base64,' . base64_encode($resp->body());
    //             }
    //         }
    //         // Otherwise assume it's a local path
    //         if (file_exists($source)) {
    //             $ext = strtolower(pathinfo($source, PATHINFO_EXTENSION));
    //             $mime = match ($ext) {
    //                 'svg'  => 'image/svg+xml',
    //                 'png'  => 'image/png',
    //                 'jpg', 'jpeg' => 'image/jpeg',
    //                 'webp' => 'image/webp',
    //                 default => 'application/octet-stream',
    //             };
    //             return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($source));
    //         }
    //         return null;
    //     };

    //     // 4) Fetch logos & signatures from public/images
    //     $logoLeft   = $encode(public_path('images/skill-india-logo.svg'));
    //     $hbselogo   = $encode(public_path('images/hbse-logo.webp'));
    //     $logoCenter = $encode(public_path('images/satyamev-logo.png'));
    //     $logoRight  = $encode(public_path('images/svsu-logo.png'));
    //     $signature1 = $encode(public_path('images/coe-signature.png'));
    //     $signature2 = $encode(public_path('images/coe-signature.png'));

    //     // 5) Pass everything to the Blade
    //     $data = compact(
    //         'candidateName',
    //         'jobRole',
    //         'nsqfLevel',
    //         'issueDate',
    //         'aadhaarMasked',
    //         'rollNo',
    //         'qrDataUri',
    //         'qrSuffix',
    //         'logoLeft',
    //         'hbselogo',
    //         'logoCenter',
    //         'logoRight',
    //         'signature1',
    //         'signature2'
    //     );

    //     // 6) Render & stream PDF
    //     $pdf = PDF::loadView('certificate', $data)
    //         ->setPaper('letter', 'landscape')
    //         ->setOptions(['isRemoteEnabled' => true]);

    //     return $pdf->stream('certificate.pdf');
    // }



    public function download(int $id, string $type = 'download')
    {
        $cert = $this->service->get($id);

        // 1) Prepare dynamic fields
        $candidateName = $cert->candidate_name;
        $gender = $cert->gender;
        $fatherName    = $cert->father_name;
        $ssc_name      = $cert->ssc_name;
        $jobRole       = $cert->job_role;
        $certificate_no = $cert->certificate_no;
        $nsqfLevel     = $cert->level;
        $issueDateRaw  = $cert->date_of_issue;
        $issueDate     = \Carbon\Carbon::parse($issueDateRaw)->format('d/m/Y');
        $aadhaarMasked = 'XXXX XXXX ' . substr($cert->aadhaar_number, -4);
        $rollNo        = $cert->alternate_id;
        $qrSuffix      = $cert->certificate_no;

        // 2) Build QR payload
        $qrPayload = implode("\n", [
            "Sector: {$cert->ssc_name}",
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

        $qrResult  = Builder::create()
            ->writer(new PngWriter())
            ->data($qrPayload)
            ->size(150)
            ->margin(10)
            ->build();
        $qrDataUri = $qrResult->getDataUri();

        // 3) Helper to embed local images
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

        $logoLeft   = $encode(public_path('images/skill_india-logo.png'));
        $logoCenter = $encode(public_path('images/satyamev-logo.png'));
        $logoRight  = $encode(public_path('images/svsu-logo.png'));
        $hbselogo   = $encode(public_path('images/hbse-logo.webp'));
        $signature1 = $encode(public_path('images/coe-signature.png'));
        $signature2 = $encode(public_path('images/secratory.png'));

        // 4) Bundle view data
        $data = compact(
            'candidateName',
            'gender',
            'fatherName',
            'ssc_name',
            'jobRole',
            'nsqfLevel',
            'issueDate',
            'aadhaarMasked',
            'certificate_no',
            'rollNo',
            'qrDataUri',
            'qrSuffix',
            'logoLeft',
            'logoCenter',
            'logoRight',
            'hbselogo',
            'signature1',
            'signature2'
        );

        $pdf = PDF::loadView('certificate', $data)
            ->setPaper('letter', 'landscape')     // must come before addInfo()
            ->addInfo([                          // now inject metadata
                'Title'     => "{$rollNo}",
                'Author'    => 'Shri Vishwakarma Skill University',
                'Subject'   => 'Skill Development Certificate',
                'Keywords'  => 'certificate, skill, PDF, SVSU',
                'Creator'   => 'SVSU Certification Portal',
            ]);

        if ($type === 'view') {
            return $pdf->stream("{$rollNo}.pdf");
        }

        return $pdf->download("{$rollNo}.pdf");
    }

    public function destroy(Request $request, int $id)
    {
        $this->service->delete($id);
        return redirect()
            ->route('certificates.index')
            ->with('success', "Deleted certificate #{$id}.");
    }

    public function destroyAll(Request $request)
    {
        $count = $this->service->deleteAll();
        return redirect()
            ->route('certificates.index')
            ->with('success', "Deleted all certificates ({$count}).");
    }
}
