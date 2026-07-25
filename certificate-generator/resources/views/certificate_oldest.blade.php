<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Certificate_{{ $certificate_no }}</title>
    <style>
        @page {
            size: letter landscape;
            margin: 0;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: DejaVu Sans, Arial, sans-serif;
        }
    </style>
</head>

<body>
    <section>
        <div style="max-width: 100%;margin:0 auto;border: 12px solid #FDC100;padding: 7px;margin: 10px;">
            <div style="border: 3px solid #FDC100;padding: 15px;">
                <div style="display: flex;justify-content: space-between;display: inline-block;">
                    <div style="margin-top: 25px;max-width: 200px;float: left;">
                        <img src="{{ $logoLeft }}" style="width:190px;">
                    </div>
                    <div
                        style="text-align:center;text-transform: uppercase;max-width: 400px;float: left;margin-left: 170px;">
                        <img src="{{ $logoCenter }}" style="width:40px;">
                        <div style="text-align: center;text-transform: uppercase;">
                            <p class="text-uppercase"
                                style="margin-bottom: 0;font-size: 10px;text-transform: uppercase;line-height: 0.5;">
                                Government of India</p>
                            <p class="text-uppercase"
                                style="margin-bottom: 0;font-size: 10px;text-transform: uppercase;line-height: 0.5;">
                                ministry of skill development & entrepreneurship</p>
                            <p class="text-uppercase"
                                style="margin-bottom: 0;font-size: 10px;text-transform: uppercase;line-height: 0.5;">
                                Government of India</p>
                            <h4 style="letter-spacing: 2px;margin-bottom: 0;">Certificate</h4>
                        </div>
                    </div>
                    <div>
                        <img src="{{ $logoRight }}" style="width:100px;margin-left: 200px;">
                    </div>
                </div>
                <div style="clear: both;"></div>

                <br>


                <div style="text-align:center;max-width: 60%;margin: 0 auto;margin-top: 25px;">
                    <p>This is to Certify that <strong>{{ $candidateName }} S/o {{ $fatherName }}</strong> has
                        successfully cleared the
                        assessment for the job role of {{ $jobRole }} in the {{ $ssc_name }} Sector
                        confirming to NSQF Level {{ $nsqfLevel }}.</p>
                </div>

                <br>

                <div style="max-width:100%;text-align: right;margin-top: 40px;">
                    <div style="display:flex;justify-content: flex-end;float: right;">
                        <div style="width:500px;">
                            <div style="float: left;">
                                <img src="{{ $hbselogo }}" style="width:80px;border-radius: 50%;">
                            </div>
                            <div style="text-align:left;margin-left: 20%;margin-top: 2px;margin-left: 280px;">
                                <p style="font-size: 13px;line-height: 0.5;">Date of Issuance:
                                    {{ $issueDate }}</p>
                                <p style="font-size: 13px;line-height: 0.5;">Aadhar No.: {{ $aadhaarMasked }}</p>
                                <p style="font-size: 13px;line-height: 0.5;">Roll No.: {{ $rollNo }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="clear: both;"></div>

                <br>

                <div style="display:flex;width: 100%;justify-content: space-between;margin-top: 40px;">
                    <div style="max-width:30%;float: left;">
                        <div style="text-align: center;">
                            <img src="{{ $signature1 }}" style="width:120px;">
                        </div>
                        <div style="text-align: center;margin-top: -30px;">
                            <p style="font-weight: 600;line-height: 0.5;margin-bottom: 0;">Prof. Nirmal Singh</p>
                            <p style="font-size:12px;line-height: 0.5;margin-bottom: 0;">Controller of Examination, SVSU
                            </p>
                        </div>
                    </div>
                    <div style="max-width:30%;float: left;margin-left: 180px;">
                        <div style="text-align: center;">
                            <img src="{{ $signature2 }}" style="width:120px;">
                        </div>
                        <div style="text-align: center;margin-top: -30px;">
                            <p style="font-weight: 600;line-height: 0.5;margin-bottom: 0;">Krishan Kumar, H.C.S.</p>
                            <p style="font-size:12px;line-height: 0.3;margin-bottom: 0;">Secretary</p>
                            <p style="font-size:12px;line-height: 0.3;margin-bottom: 0;">Board of School Education,
                                Haryana, Bhiwani </p>
                        </div>
                    </div>
                    <div style="max-width:30%;float: left;margin-left: 210px;">
                        <div style="text-align: center;margin-top: -20px;">
                            <img src="{{ $qrDataUri }}" style="width:110px;">
                        </div>
                        <div style="text-align: center;margin-top: -15px;">
                            <p style="font-weight: 600;line-height: 0.5;margin-bottom: 0;">{{ $certificate_no }}</p>
                        </div>
                    </div>
                </div>

                <div style="clear: both;"></div>


            </div>

        </div>
    </section>

</body>

</html>
