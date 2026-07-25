<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'sl_no',
        'state_ut',
        'district',
        'school_name',
        'candidate_name',
        'gender',
        'class_standard',
        'aadhaar_number',
        'school_code',
        'alternate_id',
        'father_name',
        'ssc_name',
        'job_role',
        'level',
        'pass_fail',
        'training_type',
        'candidate_organization',
        'candidate_id',
        'certificate_no',
        'issuing_authority',
        'date_of_issue',
        'created_at',
        'updated_at',
    ];
    public function setAadhaarNumberAttribute($value): void
    {
        // remove spaces, tabs, line‑breaks
        $normalized = preg_replace('/\s+/', '', (string) $value);
        $this->attributes['aadhaar_number'] = $normalized;
    }
}
