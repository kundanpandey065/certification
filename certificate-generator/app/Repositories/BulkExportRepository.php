<?php


namespace App\Repositories;

use App\Models\BulkExport;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BulkExportRepository implements BulkExportRepositoryInterface
{
    public function create(array $data): BulkExport
    {
        return BulkExport::create($data);
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return BulkExport::orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function find(int $id): BulkExport
    {
        return BulkExport::findOrFail($id);
    }

    public function delete(int $id): bool
    {
        return (bool) BulkExport::destroy($id);
    }

    public function deleteAll(): int
    {
        return BulkExport::query()->delete();
    }
}
