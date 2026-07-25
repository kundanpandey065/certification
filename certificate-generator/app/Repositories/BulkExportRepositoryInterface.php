<?php

namespace App\Repositories;

use App\Models\BulkExport;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BulkExportRepositoryInterface
{
    public function create(array $data): BulkExport;
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function find(int $id): BulkExport;
    public function delete(int $id): bool;
    public function deleteAll(): int;
}
