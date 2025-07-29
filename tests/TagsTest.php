<?php

use Illuminate\Support\Str;
use App\Models\Tag;

class TagsTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateTagValidateFail(): void
    {
        $this->post(route('tag.create'), ['fixed' => 123])
            ->seeJson(
                [
                    'name' => ['The name field is required.']
                ]
            );
    }

    /**
     * @dataProvider tagsProvider
     */
    public function testCreateTag(string $name): void
    {
        $this->post(route('tag.create'), ['name' => $name])
            ->seeJson([
                'name' => $name
            ]);
    }

    /**
     * @return void
     */
    public function testEditTag(): void
    {
        Tag::factory()->count(3)->create();

        $name = Str::random(5);
        $oldTag = new Tag()->first();
        $this->put(
            route(
                'tag.update',
                [
                    'id' => $oldTag?->id,
                    'name' => $name
                ]
            )
        )
            ->seeJson([
                'name' => $name
            ]);
    }

    /**
     * @return string[][]
     */
    public static function tagsProvider(): array
    {
        return [
            [Str::random(5)],
            [Str::random(5)],
            [Str::random(5)]
        ];
    }
}
