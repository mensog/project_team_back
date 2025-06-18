<?php

namespace App\Services;

use App\Repositories\Interfaces\RatingRepositoryInterface;
use App\Services\Interfaces\RatingServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class RatingService implements RatingServiceInterface
{
    protected $ratingRepository;

    public function __construct(RatingRepositoryInterface $ratingRepository)
    {
        $this->ratingRepository = $ratingRepository;
    }

    public function all()
    {
        return $this->ratingRepository->all();
    }

    public function getLeaderboard(int $perPage = 10): LengthAwarePaginator
    {
        return $this->ratingRepository->getLeaderboard($perPage);
    }

    public function find(int $id)
    {
        return $this->ratingRepository->find($id);
    }

    public function create(array $data)
    {
        return $this->ratingRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->ratingRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->ratingRepository->delete($id);
    }
}
