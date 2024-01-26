<?php

namespace App\Repositories;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    private Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data, $id)
    {
        $model = $this->model->findOrFail($id);
        $model->update($data);
        return $model;
    }

    public function delete($id)
    {
        return $this->model->find($id)->delete();
    }

    public function show($id)
    {
        return $this->model->findOrFail($id);
    }
    // Get the associated model
    public function getModel(): Model
    {
        return $this->model;
    }

    // Set the associated model
    public function setModel($model): BaseRepository
    {
        $this->model = $model;
        return $this;
    }

    // Eager load database relationships
    public function with($relations): Builder
    {
        return $this->model->with($relations);
    }

    public function find($booking_id)
    {
        return $this->model->findOrFail($booking_id);
    }
}
