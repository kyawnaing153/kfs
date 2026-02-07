<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function findById(int $id);
    /**
     * @param array $filters
     * @param string $orderBy
     * @param string $orderDir
     * @return mixed
     */
    public function findAll(array $filters = [], string $orderBy = 'id', string $orderDir = 'desc');
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}