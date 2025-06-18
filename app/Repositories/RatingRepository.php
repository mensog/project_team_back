<?php

namespace App\Repositories;

use App\Models\Rating;
use App\Repositories\Interfaces\RatingRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class RatingRepository implements RatingRepositoryInterface
{
    protected $model;

    public function __construct(Rating $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function getLeaderboard(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->orderBy('rating', 'desc')
            ->paginate($perPage);
    }

    public function find(int $id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $rating = $this->find($id);
        if ($rating) {
            $rating->update($data);
        }
        return $rating;
    }

    public function delete(int $id)
    {
        $rating = $this->find($id);
        if ($rating) {
            $rating->delete();
        }
        return true;
    }
}
