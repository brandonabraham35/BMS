<?php

namespace App\Core\Services;

use App\Core\Repositories\BaseRepository;

abstract class BaseService
{
    protected $repository;

    public function __construct(BaseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->all();
    }

    public function findById(string $id)
    {
        return $this->repository->find($id);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update(string $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete(string $id)
    {
        return $this->repository->delete($id);
    }
}
