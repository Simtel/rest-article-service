<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\ArticleRepositoryInterface;
use App\Dto\ArticleFilterDto;
use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function findByFilter(ArticleFilterDto $dto): Collection
    {
        $articles = Article::where('id', '>', 0);
        $articles->with('tags');

        $tagsIds = $dto->getTagsIds();
        if ($tagsIds !== null) {
            $articles->whereHas('tags', function (Builder $query) use ($tagsIds) {
                $query->whereIn('tags.id', $tagsIds);
            });
        }

        $name = $dto->getName();
        if ($name !== null) {
            $articles->where('name', 'LIKE', '%' . $name . '%');
        }

        return $articles->get();
    }
}
