<?php

namespace App\Repositories\Interfaces;

interface BaseRepositoryInterface
{
    /**
     * Get all models
     */
    public function all(array $columns = ['*'], array $relations = []);

    /**
     * Get all models by condition
     */
    public function allBy(array $criteria, array $columns = ['*'], array $relations = []);

    /**
     * Find model by id
     */
    public function findById(int $modelId, array $columns = ['*'], array $relations = [], array $appends = []);

    /**
     * Find model by criteria
     */
    public function findBy(array $criteria, array $columns = ['*'], array $relations = []);

    /**
     * Find or fail by id
     */
    public function findOrFail(int $modelId, array $columns = ['*'], array $relations = []);

    /**
     * Create a model
     */
    public function create(array $payload);

    /**
     * Update existing model
     */
    public function update(int $modelId, array $payload);

    /**
     * Delete model by id
     */
    public function deleteById(int $modelId);

    /**
     * Restore soft deleted model
     */
    public function restoreById(int $modelId);

    /**
     * Permanently delete model
     */
    public function permanentlyDeleteById(int $modelId);
}