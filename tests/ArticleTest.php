<?php

use App\Models\Article;

class ArticleTest extends TestCase
{
    public function testCreateArticleValidateFail(): void
    {
        $this->post(route('article.create'), ['fixed' => 123])
            ->seeJson(
                [
                    'name' => ['The name field is required.']
                ]
            );
    }

    /**
     * @dataProvider articleProvider
     *
     * @param string $name
     *
     * @return void
     */
    public function testCreateArticleWithoutTags(string $name): void
    {
        $this->post(route('article.create'), ['name' => $name])
            ->seeJson([
                'name' => $name
            ]);
    }

    /**
     * @dataProvider articleWithTagsProvider
     *
     * @param string $name
     * @param string[] $tags
     *
     * @return void
     */
    public function testCreateArticleWithTags(string $name, array $tags): void
    {
        $this->post(route('article.create'), ['name' => $name, 'tags' => $tags])
            ->seeJsonStructure(
                [
                    'name',
                    'tags' => [
                        '*' => [
                            'id',
                            'name'
                        ]
                    ]
                ]
            );
    }

    /**
     * @return void
     */
    public function testEditArticle(): void
    {
        $faker = Faker\Factory::create();
        $name = $faker->sentence(3).'-new';

        Article::factory()->count(1)->create();

        $this->put(
            route(
                'article.update',
                [
                    'id' => (new App\Models\Article())->first()?->id,
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
    public function testDeleteArticleFail(): void
    {
        $this->delete(route('article.delete', ['id' => 9999]))
            ->seeStatusCode(404);
    }

    /**
     * @return void
     */
    public function testDeleteArticle(): void
    {
        Article::factory()->count(3)->create();

        $this->delete(route('article.delete', ['id' => (new App\Models\Article())->first()?->id]))
            ->seeStatusCode(200)
            ->seeJson(['success' => true]);

        $this->assertCount(2, Article::all());
    }

    /**
     * @return void
     */
    public function testShowArticle(): void
    {
        Article::factory()->count(3)->create();

        $article = (new App\Models\Article())->first();

        $this->get(route('article', ['id' => $article?->id]))
            ->seeStatusCode(200)
            ->seeJson(['name' => $article?->name]);
    }

    /**
     * @return void
     */
    public function testListArticles(): void
    {
        $this->post(route('article.lists'))
            ->seeStatusCode(200)
            ->seeJsonStructure(
                [
                    '*' => [
                        'id',
                        'name',
                        'tags'
                    ]
                ]
            );
    }

    /**
     * @return void
     */
    public function testListArticleWithFilterByTag(): void
    {
        /** @phpstan-ignore-next-line */
        Article::factory()->count(5)->hasTags(2)->create();

        $article = Article::with('tags')->first();
        $tags = [];
        $article?->tags->each(static function ($item, $key) use (&$tags) {
            $tags[]['id'] = $item->id;
        });
        $this->post(route('article.lists'), ['tags' => $tags])
            ->seeStatusCode(200)
            ->seeJsonEquals([$article?->toArray()]);
    }

    /**
     * @return void
     */
    public function testListArticleWithFilterByName(): void
    {
        Article::factory()->count(5)->create();

        $article = Article::with('tags')->first();
        $tags = [];
        $article?->tags->each(static function ($item, $key) use (&$tags) {
            $tags[]['id'] = $item->id;
        });
        $this->post(route('article.lists'), ['name' => $article?->name])
            ->seeStatusCode(200)
            ->seeJsonEquals([$article?->toArray()]);
    }

    /**
     * @return string[][]
     */
    public function articleProvider(): array
    {
        $faker = Faker\Factory::create();
        return [
            [$faker->sentence(3)],
            [$faker->sentence(3)],

        ];
    }

    /**
     * @return array
     */
    public function articleWithTagsProvider(): array
    {
        $faker = Faker\Factory::create();
        return [
            [$faker->sentence(3), [['name' => $faker->word()], ['name' => $faker->word()]]],
            [$faker->sentence(3), [['name' => $faker->word()], ['name' => $faker->word()]]]
        ];
    }
}
