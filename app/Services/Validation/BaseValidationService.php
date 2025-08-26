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
     * @throws ValidationException
     */
    protected function validate(Request $request, array $rules, array $messages = []): array
    {
        return $this->validator->make($request->all(), $rules, $messages)->validate();
    }

    /**
     * @throws ValidationException
     */
    abstract public function validateCreate(Request $request): array;

    /**
     * @throws ValidationException
     */
    abstract public function validateUpdate(Request $request, mixed ...$args): array;
}
