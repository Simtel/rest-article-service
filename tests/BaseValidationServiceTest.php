<?php

use App\Services\Validation\BaseValidationService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BaseValidationServiceTest extends TestCase
{
    private TestValidationService $validationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validationService = new TestValidationService(
            $this->app->make(\Illuminate\Validation\Factory::class)
        );
    }

    /**
     * @return void
     */
    public function testValidateSuccess(): void
    {
        $request = new Request([
            'name' => 'Test Name',
            'email' => 'test@example.com'
        ]);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email'
        ];

        $result = $this->validationService->testValidate($request, $rules);

        $this->assertEquals('Test Name', $result['name']);
        $this->assertEquals('test@example.com', $result['email']);
    }

    /**
     * @return void
     */
    public function testValidateFailsWithRequiredField(): void
    {
        $request = new Request([
            'email' => 'test@example.com'
        ]);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email'
        ];

        $this->expectException(ValidationException::class);
        $this->validationService->testValidate($request, $rules);
    }

    /**
     * @return void
     */
    public function testValidateFailsWithInvalidEmail(): void
    {
        $request = new Request([
            'name' => 'Test Name',
            'email' => 'invalid-email'
        ]);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email'
        ];

        $this->expectException(ValidationException::class);
        $this->validationService->testValidate($request, $rules);
    }

    /**
     * @return void
     */
    public function testValidateFailsWithExceedsMaxLength(): void
    {
        $request = new Request([
            'name' => str_repeat('a', 256),
            'email' => 'test@example.com'
        ]);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email'
        ];

        $this->expectException(ValidationException::class);
        $this->validationService->testValidate($request, $rules);
    }

    /**
     * @return void
     */
    public function testValidateWithCustomMessages(): void
    {
        $request = new Request([]);

        $rules = [
            'name' => 'required'
        ];

        $messages = [
            'name.required' => 'Custom required message'
        ];

        $exception = null;
        try {
            $this->validationService->testValidate($request, $rules, $messages);
        } catch (ValidationException $e) {
            $exception = $e;
        }

        $this->assertNotNull($exception);
        $this->assertArrayHasKey('name', $exception->errors());
        $this->assertContains('Custom required message', $exception->errors()['name']);
    }

    /**
     * @return void
     */
    public function testValidateWithNumericRules(): void
    {
        $request = new Request([
            'age' => '25',
            'price' => '99.99'
        ]);

        $rules = [
            'age' => 'required|integer|min:18|max:100',
            'price' => 'required|numeric|min:0'
        ];

        $result = $this->validationService->testValidate($request, $rules);

        $this->assertEquals(25, $result['age']);
        $this->assertEquals('99.99', $result['price']);
    }

    /**
     * @return void
     */
    public function testValidateWithArrayRules(): void
    {
        $request = new Request([
            'tags' => ['tag1', 'tag2', 'tag3'],
            'options' => [
                ['name' => 'option1'],
                ['name' => 'option2']
            ]
        ]);

        $rules = [
            'tags' => 'required|array|min:1',
            'tags.*' => 'string|max:50',
            'options' => 'required|array',
            'options.*.name' => 'required|string|max:100'
        ];

        $result = $this->validationService->testValidate($request, $rules);

        $this->assertCount(3, $result['tags']);
        $this->assertCount(2, $result['options']);
        $this->assertEquals('tag1', $result['tags'][0]);
        $this->assertEquals('option1', $result['options'][0]['name']);
    }

    /**
     * @return void
     */
    public function testValidateWithBooleanRules(): void
    {
        $request = new Request([
            'is_active' => true,
            'is_published' => '1'
        ]);

        $rules = [
            'is_active' => 'required|boolean',
            'is_published' => 'required|boolean'
        ];

        $result = $this->validationService->testValidate($request, $rules);

        $this->assertTrue($result['is_active']);
        $this->assertTrue((bool)$result['is_published']);
    }

    /**
     * @return void
     */
    public function testValidateWithOptionalFields(): void
    {
        $request = new Request([
            'name' => 'Test Name'
        ]);

        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'sometimes|string|max:1000',
            'tags' => 'sometimes|array'
        ];

        $result = $this->validationService->testValidate($request, $rules);

        $this->assertEquals('Test Name', $result['name']);
        $this->assertArrayNotHasKey('description', $result);
        $this->assertArrayNotHasKey('tags', $result);
    }

    /**
     * @return void
     */
    public function testValidateEmptyRequest(): void
    {
        $request = new Request([]);
        $rules = [];

        $result = $this->validationService->testValidate($request, $rules);

        $this->assertEmpty($result);
    }
}

/**
 * Test implementation of BaseValidationService for testing purposes
 */
class TestValidationService extends BaseValidationService
{
    /**
     * @param Request $request
     * @param array<string, string> $rules
     * @param array<string, string> $messages
     * @return array<string, mixed>
     * @throws ValidationException
     */
    public function testValidate(Request $request, array $rules, array $messages = []): array
    {
        return $this->validate($request, $rules, $messages);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     * @throws ValidationException
     */
    public function validateCreate(Request $request): array
    {
        return $this->validate($request, ['name' => 'required|string']);
    }

    /**
     * @param Request $request
     * @param mixed ...$args
     * @return array<string, mixed>
     * @throws ValidationException
     */
    public function validateUpdate(Request $request, mixed ...$args): array
    {
        return $this->validate($request, ['name' => 'required|string']);
    }
}
