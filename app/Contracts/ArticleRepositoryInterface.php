<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Dto\ArticleFilterDto;
use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

interface ArticleRepositoryInterface
{
    /**
     * @param ArticleFilterDto $dto
     * @return Collection<int, Article>
     */
    public function findByFilter(ArticleFilterDto $dto): Collection;
}
