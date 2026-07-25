<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BulkExport extends Model
{

    public $timestamps = true;
    protected $fillable = [
        'sector',
        'district',
        'school_code',
        'class_standard',
        'record_count',
        'file_name',
        'file_path',
        'created_at',
        'updated_at',
    ];
}
