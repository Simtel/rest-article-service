<?php

namespace App\Services\Validation;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TagValidationService extends BaseValidationService
{
    private const array CREATE_RULES = [
        'name' => 'required|string|max:255'
    ];

    private const array UPDATE_RULES = [
        'name' => 'required|string|max:255'
    ];

    /**
     * @param Request $request
     * @return array<string, mixed>
     * @throws ValidationException
     */
    public function validateCreate(Request $request): array
    {
        /** @var array<string, mixed> */
        $data = $this->validate($request, self::CREATE_RULES);

        // Manual unique check for creation
        if (Tag::where('name', $data['name'])->exists()) {
            throw ValidationException::withMessages([
                'name' => ['The name has already been taken.']
            ]);
        }

        return $data;
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
        $data = $this->validate($request, self::UPDATE_RULES);
        $tagId = $args[0] ?? null;

        // Manual unique check for update (excluding current tag)
        $query = Tag::where('name', $data['name']);
        if ($tagId) {
            $query->where('id', '!=', $tagId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'name' => ['The name has already been taken.']
            ]);
        }

        return $data;
    }
}
