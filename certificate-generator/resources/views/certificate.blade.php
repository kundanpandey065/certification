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
    <section style="padding-top: 10px;">
        <div style="max-width: 100%;margin:0 auto;border: 12px solid #FDC100;padding: 7px;margin: 10px;">
            <div style="border: 3px solid #FDC100;padding: 15px;">
                <div style="display: flex;justify-content: space-between;display: inline-block;">
                    <div style="margin-top: 15px;max-width: 200px;float: left;">
                        <img src="{{ $logoLeft }}" style="width:140px;">
                    </div>
                    <div
                        style="text-align:center;text-transform: uppercase;max-width: 400px;float: left;margin-left: 230px;">
                        <img src="{{ $logoCenter }}" style="width:255px;margin-top: -10px;">
                        <div style="text-align: center;text-transform: uppercase;">
                            <!-- <p class="text-uppercase"
                                style="margin-bottom: 0;font-size: 10px;text-transform: uppercase;line-height: 0.5;font-weight: 600;">
                                Government of India</p>
                            <p class="text-uppercase"
                                style="margin-bottom: 0;font-size: 10px;text-transform: uppercase;line-height: 0.5;font-weight: 600;">
                                ministry of skill development <br> & entrepreneurship</p> -->
                            <!-- <p class="text-uppercase" style="margin-bottom: 0;font-size: 10px;text-transform: uppercase;line-height: 0.5;font-weight: 600;"> -->
                            <h4 style="letter-spacing: 2px;margin-bottom: 0;font-size: 25px;letter-spacing: 3px;">
                                Certificate</h4>
                        </div>
                    </div>
                    <div>
                        <img src="{{ $logoRight }}" style="width:120px;margin-left: 215px;">
                    </div>
                </div>
                <div style="clear: both;"></div>




                <div style="text-align:center;max-width: 81%;margin: 0 auto;margin-top: 15px;height: 130px;">
                    <p style="font-size:18px;'line-height: 1.5">This is to Certify that <strong>{{ $candidateName }}
                            {{ strtolower($gender) === 'female' ? 'D/o' : 'S/o' }} {{ $fatherName }}</strong> has
                        successfully <br> cleared the
                        assessment for the job role of {{ $jobRole }} in the <strong> {{ $ssc_name }} </strong>
                        Sector
                        conforming to NSQF <span style="white-space: nowrap;">Level {{ $nsqfLevel }}.</span></p>
                </div>
                <div style="clear: both;"></div>


                <div style="max-width:100%;text-align: right;margin-top: 0px;">
                    <div style="display:flex;justify-content: flex-end;float: right;">
                        <div style="width:540px;">
                            <div style="float: left;">
                                <img src="{{ $hbselogo }}" style="width:150px;border-radius: 50%;">
                            </div>
                            <div style="text-align:left;margin-top: 32px;margin-left: 270px;">
                                <p style="font-size: 16px;line-height: 0.7;">Date of Issuance:
                                    {{ $issueDate }}</p>
                                <p style="font-size: 16px;line-height: 0.7;">Aadhar No.: {{ $aadhaarMasked }}</p>
                                <p style="font-size: 16px;line-height: 0.7;">Roll No.: {{ $rollNo }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="clear: both;"></div>

                <br>

                <div style="display:flex;width: 100%;justify-content: space-between;margin-top: 30px;">
                    <div style="max-width:30%;float: left;margin-top: 15px;">
                        <div style="text-align: center;">
                            <img src="{{ $signature1 }}" style="width:120px;">
                        </div>
                        <div style="text-align: center;margin-top: -10px;">
                            <p style="font-weight: 600;line-height: 0.5;margin-bottom: 0;font-size: 17px;"> Prof. Dr. Rajesh Kumar</p>
                            <p style="font-size:16px;line-height: 0.3;margin-bottom: 0;font-weight: 600;">Controllers of
                                Examination</p>
                            <p style="font-size:12px;line-height: 0.3;margin-bottom: 0;font-weight: 600;">Shri Vishwakarma Skill
                                University, Palwal</p>
                        </div>
                    </div>
                    <div style="max-width:30%;float: left;margin-left: 170px;">
                        <div style="text-align: center;">
                            <img src="{{ $signature2 }}" style="width:120px;">
                        </div>
                        <div style="text-align: center;margin-top: -10px;">
                            <p style="font-weight: 600;line-height: 0.5;margin-bottom: 0;font-size: 17px;">Munish Sharma, IAS  </p>
                            <p style="font-size:16px;line-height: 0.3;margin-bottom: 0;font-weight: 600;">Secretary</p>
                            <p style="font-size:12px;line-height: 0.3;margin-bottom: 0;font-weight: 600;">Board of School Education,
                                Haryana, Bhiwani </p>
                        </div>
                    </div>
                    <div style="max-width:30%;float: left;margin-left: 180px;">
                        <div style="text-align: center;margin-top: -20px;">
                            <img src="{{ $qrDataUri }}" style="width:130px;">
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
