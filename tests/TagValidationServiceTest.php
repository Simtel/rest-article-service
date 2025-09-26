<?php

use App\Models\Tag;
use App\Services\Validation\TagValidationService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\DataProvider;

class TagValidationServiceTest extends TestCase
{
    private TagValidationService $validationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validationService = new TagValidationService(
            $this->app->make(\Illuminate\Validation\Factory::class)
        );
    }

    /**
     * @return void
     */
    public function testValidateCreateSuccess(): void
    {
        $request = new Request([
            'name' => 'unique-tag-name'
        ]);

        $result = $this->validationService->validateCreate($request);

        $this->assertEquals('unique-tag-name', $result['name']);
    }

    /**
     * @return void
     */
    public function testValidateCreateFailsWithoutName(): void
    {
        $request = new Request([]);

        $this->expectException(ValidationException::class);
        $this->validationService->validateCreate($request);
    }

    /**
     * @return void
     */
    public function testValidateCreateFailsWithEmptyName(): void
    {
        $request = new Request([
            'name' => ''
        ]);

        $this->expectException(ValidationException::class);
        $this->validationService->validateCreate($request);
    }

    /**
     * @return void
     */
    public function testValidateCreateFailsWithLongName(): void
    {
        $request = new Request([
            'name' => str_repeat('a', 256) // Over 255 char limit
        ]);

        $this->expectException(ValidationException::class);
        $this->validationService->validateCreate($request);
    }

    /**
     * @param mixed $name
     * @return void
     */
    #[DataProvider('invalidNameProvider')]
    public function testValidateCreateWithInvalidNames(mixed $name): void
    {
        $request = new Request(['name' => $name]);

        $this->expectException(ValidationException::class);
        $this->validationService->validateCreate($request);
    }

    /**
     * @return void
     */
    public function testValidateCreateFailsWithExistingName(): void
    {
        // Create a tag with existing name
        Tag::factory()->create(['name' => 'existing-tag']);

        $request = new Request([
            'name' => 'existing-tag'
        ]);

        $this->expectException(ValidationException::class);
        $this->validationService->validateCreate($request);
    }

    /**
     * @return void
     */
    public function testValidateUpdateSuccess(): void
    {
        /** @var Tag $tag */
        $tag = Tag::factory()->create(['name' => 'original-name']);

        $request = new Request([
            'name' => 'updated-name'
        ]);

        $result = $this->validationService->validateUpdate($request, $tag->id);

        $this->assertEquals('updated-name', $result['name']);
    }

    /**
     * @return void
     */
    public function testValidateUpdateSuccessWithSameName(): void
    {
        /** @var Tag $tag */
        $tag = Tag::factory()->create(['name' => 'same-name']);

        $request = new Request([
            'name' => 'same-name'
        ]);

        $result = $this->validationService->validateUpdate($request, $tag->id);

        $this->assertEquals('same-name', $result['name']);
    }

    /**
     * @return void
     */
    public function testValidateUpdateFailsWithoutName(): void
    {
        /** @var Tag $tag */
        $tag = Tag::factory()->create();

        $request = new Request([]);

        $this->expectException(ValidationException::class);
        $this->validationService->validateUpdate($request, $tag->id);
    }

    /**
     * @return void
     */
    public function testValidateUpdateFailsWithEmptyName(): void
    {
        /** @var Tag $tag */
        $tag = Tag::factory()->create();

        $request = new Request([
            'name' => ''
        ]);

        $this->expectException(ValidationException::class);
        $this->validationService->validateUpdate($request, $tag->id);
    }

    /**
     * @return void
     */
    public function testValidateUpdateFailsWithLongName(): void
    {
        /** @var Tag $tag */
        $tag = Tag::factory()->create();

        $request = new Request([
            'name' => str_repeat('a', 256)
        ]);

        $this->expectException(ValidationException::class);
        $this->validationService->validateUpdate($request, $tag->id);
    }

    /**
     * @return void
     */
    public function testValidateUpdateFailsWithExistingName(): void
    {
        /** @var Tag $tag1 */
        $tag1 = Tag::factory()->create(['name' => 'tag-one']);
        /** @var Tag $tag2 */
        $tag2 = Tag::factory()->create(['name' => 'tag-two']);

        $request = new Request([
            'name' => 'tag-one' // Try to update tag2 with tag1's name
        ]);

        $this->expectException(ValidationException::class);
        $this->validationService->validateUpdate($request, $tag2->id);
    }

    /**
     * @return void
     */
    public function testValidateUpdateWithoutTagId(): void
    {
        Tag::factory()->create(['name' => 'existing-tag']);

        $request = new Request([
            'name' => 'existing-tag'
        ]);

        $this->expectException(ValidationException::class);
        $this->validationService->validateUpdate($request);
    }

    /**
     * @return void
     */
    public function testValidateUpdateWithNullTagId(): void
    {
        Tag::factory()->create(['name' => 'existing-tag']);

        $request = new Request([
            'name' => 'existing-tag'
        ]);

        $this->expectException(ValidationException::class);
        $this->validationService->validateUpdate($request, null);
    }

    /**
     * @return void
     */
    public function testValidateUpdateWithInvalidTagId(): void
    {
        Tag::factory()->create(['name' => 'existing-tag']);

        $request = new Request([
            'name' => 'new-unique-name'
        ]);

        $result = $this->validationService->validateUpdate($request, 9999);

        $this->assertEquals('new-unique-name', $result['name']);
    }

    /**
     * @param mixed $name
     * @return void
     */
    #[DataProvider('invalidNameProvider')]
    public function testValidateUpdateWithInvalidNames(mixed $name): void
    {
        /** @var Tag $tag */
        $tag = Tag::factory()->create();

        $request = new Request(['name' => $name]);

        $this->expectException(ValidationException::class);
        $this->validationService->validateUpdate($request, $tag->id);
    }

    /**
     * @return mixed[][]
     */
    public static function invalidNameProvider(): array
    {
        return [
            [null],
            [123],
            [[]],
            [true],
            [false]
        ];
    }
}
