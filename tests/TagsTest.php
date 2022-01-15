<?php

use Illuminate\Support\Str;

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
     *
     * @param $name
     *
     * @return void
     */
    public function testCreateTag($name): void
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
        $name = Str::random(5);
        $oldTag = (new App\Models\Tag)->first();
        $this->put(
            route('tag.update',
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
     * @return string[][]
     */
    public function tagsProvider(): array
    {
        return [
            [Str::random(5)],
            [Str::random(5)],
            [Str::random(5)]
        ];
    }


}
