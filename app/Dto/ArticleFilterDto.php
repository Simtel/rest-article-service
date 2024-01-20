<?php

declare(strict_types=1);

namespace App\Dto;

class ArticleFilterDto
{
    private ?string $name = null;
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
     * @param int[] $tagsIds
     */
    public function setTagsIds(array $tagsIds): void
    {
        $this->tagsIds = $tagsIds;
    }


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}
