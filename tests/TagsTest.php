<?php

use Illuminate\Support\Str;
use App\Models\Tag;
use PHPUnit\Framework\Attributes\DataProvider;

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

    #[DataProvider('tagsProvider')]
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
        $oldTag = Tag::first();
        /** @var \App\Models\Tag $oldTag */
        $this->put(
            route(
                'tag.update',
                [
                    'id' => $oldTag->id,
                    'name' => $name
                ]
            )
        )
            ->seeJson([
                'name' => $name
            ]);
    }

    /**
     * @return void
     */
    public function testShowTag(): void
    {
        Tag::factory()->count(3)->create();

        $tag = Tag::first();
        /** @var \App\Models\Tag $tag */
        $this->get(route('tag.show', ['id' => $tag->id]))
            ->seeStatusCode(200)
            ->seeJson([
                'id' => $tag->id,
                'name' => $tag->name
            ]);
    }

    /**
     * @return void
     */
    public function testShowTagNotFound(): void
    {
        $this->get(route('tag.show', ['id' => 9999]))
            ->seeStatusCode(404)
            ->seeJson(['error' => 'Tag not found']);
    }

    /**
     * @return void
     */
    public function testIndexTags(): void
    {
        Tag::factory()->count(3)->create();

        $this->get(route('tag.index'))
            ->seeStatusCode(200)
            ->seeJsonStructure([
                '*' => [
                    'id',
                    'name'
                ]
            ]);
    }

    /**
     * @return void
     */
    public function testDeleteTag(): void
    {
        Tag::factory()->count(3)->create();

        $tag = Tag::first();
        /** @var \App\Models\Tag $tag */
        $this->delete(route('tag.delete', ['id' => $tag->id]))
            ->seeStatusCode(200)
            ->seeJson(['success' => true]);

        $this->assertCount(2, Tag::all());
    }

    /**
     * @return void
     */
    public function testDeleteTagNotFound(): void
    {
        $this->delete(route('tag.delete', ['id' => 9999]))
            ->seeStatusCode(404)
            ->seeJson(['error' => 'Tag not found']);
    }

    /**
     * @return void
     */
    public function testEditTagNotFound(): void
    {
        $name = Str::random(5);
        $this->put(route('tag.update', ['id' => 9999]), ['name' => $name])
            ->seeStatusCode(404)
            ->seeJson(['error' => 'Tag not found']);
    }

    /**
     * @return void
     */
    public function testCreateTagDuplicate(): void
    {
        $tagName = 'duplicate-tag';
        Tag::factory()->create(['name' => $tagName]);

        $this->post(route('tag.create'), ['name' => $tagName])
            ->seeStatusCode(422)
            ->seeJson([
                'name' => ['The name has already been taken.']
            ]);
    }

    /**
     * @return void
     */
    public function testEditTagDuplicate(): void
    {
        $tagName1 = 'tag-one';
        $tagName2 = 'tag-two';
        /** @var Tag $tag1 */
        $tag1 = Tag::factory()->create(['name' => $tagName1]);
        /** @var Tag $tag2 */
        $tag2 = Tag::factory()->create(['name' => $tagName2]);

        // Try to update tag2 with tag1's name
        $this->put(route('tag.update', ['id' => $tag2->id]), ['name' => $tagName1])
            ->seeStatusCode(422)
            ->seeJson([
                'name' => ['The name has already been taken.']
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
