<?php

namespace App\Repositories;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all models
     */
    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)->get($columns);
    }

    /**
     * Get all models by condition
     */
    public function allBy(array $criteria, array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)
            ->where($criteria)
            ->get($columns);
    }

    /**
     * Find model by id
     */
    public function findById(int $modelId, array $columns = ['*'], array $relations = [], array $appends = []): ?Model
    {
        $model = $this->model->with($relations)
            ->select($columns)
            ->find($modelId);

        if ($model && $appends) {
            $model->append($appends);
        }

        return $model;
    }

    /**
     * Find model by criteria
     */
    public function findBy(array $criteria, array $columns = ['*'], array $relations = []): ?Model
    {
        return $this->model->with($relations)
            ->where($criteria)
            ->first($columns);
    }

    /**
     * Find or fail by id
     */
    public function findOrFail(int $modelId, array $columns = ['*'], array $relations = []): Model
    {
        return $this->model->with($relations)
            ->select($columns)
            ->findOrFail($modelId);
    }

    /**
     * Create a model
     */
    public function create(array $payload): Model
    {
        $model = $this->model->create($payload);
        return $model->fresh();
    }

    /**
     * Update existing model
     */
    public function update(int $modelId, array $payload): bool
    {
        $model = $this->findById($modelId);
        return $model->update($payload);
    }

    /**
     * Delete model by id
     */
    public function deleteById(int $modelId): bool
    {
        return $this->findById($modelId)->delete();
    }

    /**
     * Restore soft deleted model
     */
    public function restoreById(int $modelId): bool
    {
        return $this->model->withTrashed()->find($modelId)->restore();
    }

    /**
     * Permanently delete model
     */
    public function permanentlyDeleteById(int $modelId): bool
    {
        return $this->model->withTrashed()->find($modelId)->forceDelete();
    }

    /**
     * Get query builder instance
     */
    protected function query(): Builder
    {
        return $this->model->newQuery();
    }
}