<?php

namespace App\Services;

use App\Models\News;
use App\Repositories\Interfaces\NewsRepositoryInterface;
use App\Services\Interfaces\NewsServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;

class NewsService implements NewsServiceInterface
{
    protected $newsRepository;

    public function __construct(NewsRepositoryInterface $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    public function all(): Collection
    {
        Gate::authorize('viewAny', News::class);
        return $this->newsRepository->all();
    }

    public function find(int $id): News
    {
        $news = $this->newsRepository->find($id);
        Gate::authorize('view', $news);
        return $news;
    }

    public function create(array $data): News
    {
        Gate::authorize('create', News::class);
        return $this->newsRepository->create($data);
    }

    public function update(int $id, array $data): News
    {
        $news = $this->newsRepository->find($id);
        Gate::authorize('update', $news);
        return $this->newsRepository->update($id, $data);
    }

    public function delete(int $id): void
    {
        $news = $this->newsRepository->find($id);
        Gate::authorize('delete', $news);
        $this->newsRepository->delete($id);
    }

    public function byStatus(string $status): Collection
    {
        Gate::authorize('viewAny', News::class);
        return $this->newsRepository->all()->where('status', $status);
    }
}
