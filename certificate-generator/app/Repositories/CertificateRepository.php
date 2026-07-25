<?php

namespace App\Repositories;

use App\Models\Certificate;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class CertificateRepository implements CertificateRepositoryInterface
{
    public function create(array $data): Certificate
    {
        return Certificate::create($data);
    }

    public function all(): LengthAwarePaginator
    {
        return Certificate::orderBy('id', 'desc')->paginate(10);
    }

    public function paginate(int $perPage = 100): LengthAwarePaginator
    {
        return Certificate::orderBy('id', 'desc')
            ->paginate($perPage);
    }
    public function find(int $id): Certificate
    {
        return Certificate::findOrFail($id);
    }
    public function filterQuery(array $f): Builder
    {
        $q = Certificate::query();
        if (!empty($f['sector']))   $q->where('ssc_name',          $f['sector']);
        if (!empty($f['district'])) $q->where('district',          $f['district']);
        if (!empty($f['school']))          $q->where('school_code',    $f['school']);
        if (!empty($f['class_standard']))  $q->where('class_standard', $f['class_standard']);
        return $q->orderBy('id', 'desc');
    }

    public function delete(int $id): bool
    {
        return (bool) Certificate::destroy($id);
    }

    public function deleteAll(): int
    {
        return Certificate::query()->delete();
    }
}
