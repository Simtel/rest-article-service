<?php

declare(strict_types=1);

namespace App\Dto;

class ArticleFilterDto
{
    /**
     * @var int[]|null
     */
    private ?array $tagsIds = null;

    /**
     * @return int[]|null
     */
    public function getTagsIds(): ?array
    {
        return $this->tagsIds;
    }

    /**
     * @param int[]|null $tagsIds
     */
    public function setTagsIds(array $tagsIds): void
    {
        $this->tagsIds = $tagsIds;
    }
}
