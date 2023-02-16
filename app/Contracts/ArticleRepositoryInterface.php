<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Dto\ArticleFilterDto;
use App\Models\Article;

interface ArticleRepositoryInterface
{

    /**
     * @param ArticleFilterDto $dto
     * @return Article[]
     */
    public function findByFilter(ArticleFilterDto $dto): array;
}
