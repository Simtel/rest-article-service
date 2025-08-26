<?php

namespace App\Services\Validation;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Factory as Validator;

abstract class BaseValidationService
{
    public function __construct(
        protected Validator $validator
    ) {
    }

    /**
     * @param Request $request
     * @param array<string, string> $rules
     * @param array<string, string> $messages
     * @return array<string, mixed>
     * @throws ValidationException
     */
    protected function validate(Request $request, array $rules, array $messages = []): array
    {
        /** @var array<string, mixed> $rules */
        $rules = $this->validator->make($request->all(), $rules, $messages)->validate();
        return $rules;
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     * @throws ValidationException
     */
    abstract public function validateCreate(Request $request): array;

    /**
     * @param Request $request
     * @param mixed ...$args
     * @return array<string, mixed>
     * @throws ValidationException
     */
    abstract public function validateUpdate(Request $request, mixed ...$args): array;
}
