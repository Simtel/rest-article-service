<?php

use App\Models\Article;
use Illuminate\Support\Str;

class ArticleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
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
     * @param $name
     *
     * @return void
     */
    public function testCreateArticleWithoutTags($name): void
    {
        $this->post(route('article.create'), ['name' => $name])
            ->seeJson([
                'name' => $name
            ]);
    }

    /**
     * @dataProvider articleWithTagsProvider
     *
     * @param $name
     * @param $tags
     *
     * @return void
     */
    public function testCreateArticleWithTags($name, $tags): void
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
        $name = Str::random(5).'edit';

        $this->put(
            route('article.update',
                [
                    'id' => Article::first()->id,
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
        $this->delete(route('article.delete', ['id' => Article::first()->id]))
            ->seeStatusCode(200)
            ->seeJson(['success' => true]);
    }

    /**
     * @return void
     */
    public function testShowArticle(): void
    {
        $article = Article::first();
        $this->get(route('article', ['id' => $article->id]))
            ->seeStatusCode(200)
            ->seeJson(['name' => $article->name]);
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
    public function testListArticleWithFilter(): void
    {
        $article = Article::with('tags')->first();
        $tags = [];
        $article->tags->each(static function ($item, $key) use (&$tags) {
            $tags[]['id'] = $item->id;
        });
        $this->post(route('article.lists'), ['tags' => $tags])
            ->seeStatusCode(200)
            ->seeJsonEquals([$article->toArray()]);
    }

    /**
     * @return string[][]
     */
    public function articleProvider(): array
    {
        return [
            [Str::random(5)],
            [Str::random(5)],

        ];
    }

    /**
     * @return array
     */
    public function articleWithTagsProvider(): array
    {
        return [
            [Str::random(6), [['name' => Str::random(3)], ['name' => Str::random(3)]]],
            [Str::random(6), [['name' => Str::random(3)], ['name' => Str::random(8)]]]
        ];
    }

}
