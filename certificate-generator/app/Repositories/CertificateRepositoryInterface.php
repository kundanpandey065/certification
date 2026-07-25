<?php

namespace App\Repositories;

use App\Models\Certificate;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

interface CertificateRepositoryInterface
{
  public function create(array $data): Certificate;
  public function all(): LengthAwarePaginator;

  public function paginate(int $perPage = 100): LengthAwarePaginator;

  public function find(int $id): Certificate;

  public function filterQuery(array $f): Builder;

  public function delete(int $id): bool;
  public function deleteAll(): int;
}
