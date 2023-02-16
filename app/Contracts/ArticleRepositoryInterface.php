<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Dto\ArticleFilterDto;
use Illuminate\Database\Eloquent\Collection;


interface ArticleRepositoryInterface
{

    /**
     * @param ArticleFilterDto $dto
     */
    public function findByFilter(ArticleFilterDto $dto): Collection;
}
