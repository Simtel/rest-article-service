<?php

use App\Services\Validation\ArticleValidationService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\DataProvider;

class ArticleValidationServiceTest extends TestCase
{
    private ArticleValidationService $validationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validationService = new ArticleValidationService(
            $this->app->make(\Illuminate\Validation\Factory::class)
        );
    }

    /**
     * @return void
     */
    public function testValidateCreateSuccess(): void
    {
        $request = new Request([
            'name' => 'Test Article',
            'tags' => [
                ['name' => 'tag1'],
                ['name' => 'tag2']
            ]
        ]);

        /** @var array{name: string, tags: array<array{name: string}>} $result */
        $result = $this->validationService->validateCreate($request);

        $this->assertEquals('Test Article', $result['name']);
        $this->assertCount(2, $result['tags']);
        $this->assertEquals('tag1', $result['tags'][0]['name']);
        $this->assertEquals('tag2', $result['tags'][1]['name']);
    }

    /**
     * @return void
     */
    public function testValidateCreateWithoutTags(): void
    {
        $request = new Request([
            'name' => 'Test Article'
        ]);

        /** @var array{name: string} $result */
        $result = $this->validationService->validateCreate($request);

        $this->assertEquals('Test Article', $result['name']);
        $this->assertArrayNotHasKey('tags', $result);
    }

    /**
     * @return void
     */
    public function testValidateCreateFailsWithoutName(): void
    {
        $request = new Request([
            'tags' => [['name' => 'tag1']]
        ]);

        $this->expectException(ValidationException::class);
        $this->validationService->validateCreate($request);
    }

    /**
     * @return void
     */
    public function testValidateCreateFailsWithEmptyName(): void
    {
        $request = new Request([
            'name' => '',
            'tags' => [['name' => 'tag1']]
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
            'name' => str_repeat('a', 256), // Over 255 char limit
            'tags' => [['name' => 'tag1']]
        ]);

        $this->expectException(ValidationException::class);
        $this->validationService->validateCreate($request);
    }

    /**
     * @return void
     */
    public function testValidateCreateFailsWithInvalidTags(): void
    {
        $request = new Request([
            'name' => 'Test Article',
            'tags' => 'invalid'
        ]);

        $this->expectException(ValidationException::class);
        $this->validationService->validateCreate($request);
    }

    /**
     * @return void
     */
    public function testValidateCreateFailsWithTagsWithoutName(): void
    {
        $request = new Request([
            'name' => 'Test Article',
            'tags' => [
                ['invalid' => 'data']
            ]
        ]);

        $this->expectException(ValidationException::class);
        $this->validationService->validateCreate($request);
    }

    /**
     * @return void
     */
    public function testValidateCreateFailsWithEmptyTagName(): void
    {
        $request = new Request([
            'name' => 'Test Article',
            'tags' => [
                ['name' => '']
            ]
        ]);

        $this->expectException(ValidationException::class);
        $this->validationService->validateCreate($request);
    }

    /**
     * @return void
     */
    public function testValidateCreateFailsWithLongTagName(): void
    {
        $request = new Request([
            'name' => 'Test Article',
            'tags' => [
                ['name' => str_repeat('a', 256)]
            ]
        ]);

        $this->expectException(ValidationException::class);
        $this->validationService->validateCreate($request);
    }

    /**
     * @return void
     */
    public function testValidateUpdateSuccess(): void
    {
        $request = new Request([
            'name' => 'Updated Article',
            'tags' => [
                ['name' => 'updated-tag']
            ]
        ]);

        /** @var array{name: string, tags: array<array{name: string}>} $result */
        $result = $this->validationService->validateUpdate($request);

        $this->assertEquals('Updated Article', $result['name']);
        $this->assertCount(1, $result['tags']);
        $this->assertEquals('updated-tag', $result['tags'][0]['name']);
    }

    /**
     * @return void
     */
    public function testValidateUpdateWithoutTags(): void
    {
        $request = new Request([
            'name' => 'Updated Article'
        ]);

        /** @var array{name: string} $result */
        $result = $this->validationService->validateUpdate($request);

        $this->assertEquals('Updated Article', $result['name']);
        $this->assertArrayNotHasKey('tags', $result);
    }

    /**
     * @return void
     */
    public function testValidateUpdateFailsWithoutName(): void
    {
        $request = new Request([
            'tags' => [['name' => 'tag1']]
        ]);

        $this->expectException(ValidationException::class);
        $this->validationService->validateUpdate($request);
    }

    /**
     * @return void
     */
    public function testValidateListSuccess(): void
    {
        $request = new Request([
            'tags' => [
                ['id' => 1],
                ['id' => 2]
            ],
            'name' => 'search term'
        ]);

        /** @var array{tags: array<array{id: int}>, name: string} $result */
        $result = $this->validationService->validateList($request);

        $this->assertCount(2, $result['tags']);
        $this->assertEquals(1, $result['tags'][0]['id']);
        $this->assertEquals(2, $result['tags'][1]['id']);
        $this->assertEquals('search term', $result['name']);
    }

    /**
     * @return void
     */
    public function testValidateListWithoutFilters(): void
    {
        $request = new Request([]);

        /** @var array{} $result */
        $result = $this->validationService->validateList($request);

        $this->assertArrayNotHasKey('tags', $result);
        $this->assertArrayNotHasKey('name', $result);
    }

    /**
     * @return void
     */
    public function testValidateListFailsWithInvalidTagIds(): void
    {
        $request = new Request([
            'tags' => [
                ['id' => 'invalid']
            ]
        ]);

        $this->expectException(ValidationException::class);
        $this->validationService->validateList($request);
    }

    /**
     * @return void
     */
    public function testValidateListFailsWithTagsWithoutId(): void
    {
        $request = new Request([
            'tags' => [
                ['invalid' => 'data']
            ]
        ]);

        $this->expectException(ValidationException::class);
        $this->validationService->validateList($request);
    }

    /**
     * @return void
     */
    public function testValidateListFailsWithInvalidTagsFormat(): void
    {
        $request = new Request([
            'tags' => 'invalid'
        ]);

        $this->expectException(ValidationException::class);
        $this->validationService->validateList($request);
    }

    /**
     * @return void
     */
    public function testValidateListFailsWithLongNameFilter(): void
    {
        $request = new Request([
            'name' => str_repeat('a', 256)
        ]);

        $this->expectException(ValidationException::class);
        $this->validationService->validateList($request);
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
