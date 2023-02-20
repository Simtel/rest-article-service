<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\ArticleRepositoryInterface;
use App\Dto\ArticleFilterDto;
use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function findByFilter(ArticleFilterDto $dto): Collection
    {
        $tagsIds = $dto->getTagsIds();
        if ($tagsIds !== null) {
            $articles = Article::withAllTags($tagsIds)->with('tags');
        } else {
            $articles = Article::with('tags');
        }

        return $articles->get();
    }
}
