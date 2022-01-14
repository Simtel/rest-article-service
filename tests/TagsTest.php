<?php

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
        $oldTag = (new App\Models\Tag)->first();
        $this->put(
            route('tag.update',
                [
                    'id' => $oldTag->id,
                    'name' => 'Новый тег'
                ]
            )
        )
            ->seeJson([
                'name' => 'Новый тег'
            ]);
    }

    /**
     * @return string[][]
     */
    public function tagsProvider(): array
    {
        return [
            ['4 тег'],
            ['5 тег'],
            ['6 тег']
        ];
    }


}
