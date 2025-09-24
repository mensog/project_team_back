<?php

namespace App\Services;

use App\Models\News;
use App\Repositories\Interfaces\NewsRepositoryInterface;
use App\Services\Interfaces\NewsServiceInterface;
use App\Services\Concerns\HandlesUploads;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;

class NewsService implements NewsServiceInterface
{
    use HandlesUploads;

    protected NewsRepositoryInterface $newsRepository;

    public function __construct(NewsRepositoryInterface $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    public function all(int $perPage = 10): LengthAwarePaginator
    {
        Gate::authorize('viewAny', News::class);
        return $this->newsRepository->paginate($perPage);
    }

    public function allPublic(int $perPage = 10): LengthAwarePaginator
    {
        return $this->newsRepository->paginate($perPage);
    }

    public function find(int $id): News
    {
        $news = $this->newsRepository->find($id);
        Gate::authorize('view', $news);
        return $news;
    }

    public function findPublic(int $id): News
    {
        return $this->newsRepository->find($id);
    }

    public function create(array $data): News
    {
        Gate::authorize('create', News::class);
        $this->syncUpload($data, 'preview_image', null, 'news_previews');

        return $this->newsRepository->create($data);
    }

    public function update(int $id, array $data): News
    {
        $news = $this->newsRepository->find($id);
        Gate::authorize('update', $news);
        $this->syncUpload($data, 'preview_image', $news->preview_image, 'news_previews');
        return $this->newsRepository->update($id, $data);
    }

    public function delete(int $id): void
    {
        $news = $this->newsRepository->find($id);
        Gate::authorize('delete', $news);
        $this->deletePublicFile($news->preview_image);
        $this->newsRepository->delete($id);
    }

    public function byStatus(string $status, int $perPage = 10): LengthAwarePaginator
    {
        return $this->newsRepository->byStatus($status, $perPage);
    }
}
