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
    public function testCreateArticle($name): void
    {
        $this->post(route('article.create'), ['name' => $name])
            ->seeJson([
                'name' => $name
            ]);
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
        $this->get(route('article',['id' => $article->id]))
            ->seeStatusCode(200)
            ->seeJson(['name' => $article->name]);
    }

    /**
     * @return string[][]
     */
    public function articleProvider(): array
    {
        return [
            [Str::random(5)],
        ];
    }


}
