<?php

namespace App\Services\Validation;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ArticleValidationService extends BaseValidationService
{
    private const CREATE_RULES = [
        'name' => 'required|string|max:255',
        'tags' => 'sometimes|array',
        'tags.*.name' => 'required|string|max:255'
    ];

    private const UPDATE_RULES = [
        'name' => 'required|string|max:255',
        'tags' => 'sometimes|array',
        'tags.*.name' => 'required|string|max:255'
    ];

    private const LIST_RULES = [
        'tags' => 'sometimes|array',
        'tags.*.id' => 'required|integer',
        'name' => 'sometimes|string|max:255'
    ];

    /**
     * @param Request $request
     * @return array<string, mixed>
     * @throws ValidationException
     */
    public function validateCreate(Request $request): array
    {
        /** @var array<string, mixed> */
        return $this->validate($request, self::CREATE_RULES);
    }

    /**
     * @param Request $request
     * @param mixed ...$args
     * @return array<string, mixed>
     * @throws ValidationException
     */
    public function validateUpdate(Request $request, mixed ...$args): array
    {
        /** @var array<string, mixed> */
        return $this->validate($request, self::UPDATE_RULES);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     * @throws ValidationException
     */
    public function validateList(Request $request): array
    {
        /** @var array<string, mixed> */
        return $this->validate($request, self::LIST_RULES);
    }
}
