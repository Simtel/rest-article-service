<?php

use App\Models\Article;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;

class ModelTest extends TestCase
{
    /**
     * Test Article model basic functionality
     * @return void
     */
    public function testArticleCreation(): void
    {
        $article = Article::factory()->create([
            'name' => 'Test Article'
        ]);

        $this->assertInstanceOf(Article::class, $article);
        $this->assertEquals('Test Article', $article->name);
        $this->assertNotNull($article->created_at);
        $this->assertNotNull($article->updated_at);
    }

    /**
     * Test Article fillable attributes
     * @return void
     */
    public function testArticleFillableAttributes(): void
    {
        $article = new Article();
        $fillable = $article->getFillable();

        $this->assertEquals(['name'], $fillable);
    }

    /**
     * Test Article hidden attributes
     * @return void
     */
    public function testArticleHiddenAttributes(): void
    {
        $article = Article::factory()->create();
        $array = $article->toArray();

        $this->assertArrayNotHasKey('created_at', $array);
        $this->assertArrayNotHasKey('updated_at', $array);
        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('name', $array);
    }

    /**
     * Test Article-Tag relationship
     * @return void
     */
    public function testArticleTagsRelationship(): void
    {
        /** @var Article $article */
        $article = Article::factory()->create();
        $tags = Tag::factory()->count(3)->create();

        $article->tags()->attach($tags->pluck('id'));

        $this->assertInstanceOf(Collection::class, $article->tags);
        $this->assertCount(3, $article->tags);

        /** @var Tag $tag */
        foreach ($tags as $tag) {
            $this->assertTrue($article->tags->contains('id', $tag->id));
        }
    }

    /**
     * Test Article can be created without tags
     * @return void
     */
    public function testArticleWithoutTags(): void
    {
        /** @var Article $article */
        $article = Article::factory()->create();

        $this->assertCount(0, $article->tags);
        $this->assertInstanceOf(Collection::class, $article->tags);
    }

    /**
     * Test Article withAllTags scope
     * @return void
     */
    public function testArticleWithAllTagsScope(): void
    {
        // Create tags
        /** @var Tag $tag1 */
        $tag1 = Tag::factory()->create(['name' => 'PHP']);
        /** @var Tag $tag2 */
        $tag2 = Tag::factory()->create(['name' => 'Laravel']);
        /** @var Tag $tag3 */
        $tag3 = Tag::factory()->create(['name' => 'JavaScript']);

        // Create articles with different tag combinations
        /** @var Article $article1 */
        $article1 = Article::factory()->create(['name' => 'Article 1']);
        $article1->tags()->attach([$tag1->id, $tag2->id]);
        /** @var Article $article2 */
        $article2 = Article::factory()->create(['name' => 'Article 2']);
        $article2->tags()->attach([$tag1->id]);
        /** @var Article $article3 */
        $article3 = Article::factory()->create(['name' => 'Article 3']);
        $article3->tags()->attach([$tag3->id]);

        // Test scope with multiple tags (should find articles that have ALL specified tags)
        $results = Article::withAllTags([$tag1->id, $tag2->id])->get();

        $this->assertCount(1, $results);
        $this->assertTrue($results->contains('name', 'Article 1'));
    }

    /**
     * Test Article withAllTags scope with single tag
     * @return void
     */
    public function testArticleWithAllTagsScopeSingleTag(): void
    {
        /** @var Tag $tag1 */
        $tag1 = Tag::factory()->create(['name' => 'PHP']);
        /** @var Tag $tag2 */
        $tag2 = Tag::factory()->create(['name' => 'Laravel']);

        /** @var Article $article1 */
        $article1 = Article::factory()->create(['name' => 'Article 1']);
        $article1->tags()->attach([$tag1->id]);
        /** @var Article $article2 */
        $article2 = Article::factory()->create(['name' => 'Article 2']);
        $article2->tags()->attach([$tag2->id]);

        $results = Article::withAllTags([$tag1->id])->get();

        $this->assertCount(1, $results);
        $this->assertTrue($results->contains('name', 'Article 1'));
    }

    /**
     * Test Article withAllTags scope with non-existent tags
     * @return void
     */
    public function testArticleWithAllTagsScopeNonExistentTags(): void
    {
        Article::factory()->count(3)->create();

        $results = Article::withAllTags([9999])->get();

        $this->assertCount(0, $results);
    }

    /**
     * Test Article withAllTags scope with empty array
     * @return void
     */
    public function testArticleWithAllTagsScopeEmptyArray(): void
    {
        $articles = Article::factory()->count(3)->create();

        $results = Article::withAllTags([])->get();

        // Empty array should return all articles (no filtering)
        $this->assertCount(3, $results);
    }

    /**
     * Test Tag model basic functionality
     * @return void
     */
    public function testTagCreation(): void
    {
        /** @var Tag $tag */
        $tag = Tag::factory()->create([
            'name' => 'Test Tag'
        ]);

        $this->assertInstanceOf(Tag::class, $tag);
        $this->assertEquals('Test Tag', $tag->name);
        $this->assertNotNull($tag->created_at);
        $this->assertNotNull($tag->updated_at);
    }

    /**
     * Test Tag fillable attributes
     * @return void
     */
    public function testTagFillableAttributes(): void
    {
        $tag = new Tag();
        $fillable = $tag->getFillable();

        $this->assertEquals(['name'], $fillable);
    }

    /**
     * Test Tag hidden attributes
     * @return void
     */
    public function testTagHiddenAttributes(): void
    {
        /** @var Tag $tag */
        $tag = Tag::factory()->create();
        $array = $tag->toArray();

        $this->assertArrayNotHasKey('created_at', $array);
        $this->assertArrayNotHasKey('updated_at', $array);
        $this->assertArrayNotHasKey('pivot', $array);
        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('name', $array);
    }

    /**
     * Test Tag-Article relationship
     * @return void
     */
    public function testTagArticlesRelationship(): void
    {
        /** @var Tag $tag */
        $tag = Tag::factory()->create();
        $articles = Article::factory()->count(3)->create();

        $tag->articles()->attach($articles->pluck('id'));

        $this->assertInstanceOf(Collection::class, $tag->articles);
        $this->assertCount(3, $tag->articles);

        /** @var Article $article */
        foreach ($articles as $article) {
            $this->assertTrue($tag->articles->contains('id', $article->id));
        }
    }

    /**
     * Test Tag can exist without articles
     * @return void
     */
    public function testTagWithoutArticles(): void
    {
        /** @var Tag $tag */
        $tag = Tag::factory()->create();

        $this->assertCount(0, $tag->articles);
        $this->assertInstanceOf(Collection::class, $tag->articles);
    }

    /**
     * Test many-to-many relationship consistency
     * @return void
     */
    public function testManyToManyRelationshipConsistency(): void
    {
        /** @var Article $article */
        $article = Article::factory()->create();
        /** @var Tag $tag */
        $tag = Tag::factory()->create();

        // Attach from article side
        $article->tags()->attach($tag->id);

        /** @var Tag $tag */
        $tag = $tag->fresh();
        $this->assertTrue($article->tags->contains('id', $tag->id));
        $this->assertTrue($tag->articles->contains('id', $article->id));
    }

    /**
     * Test detaching relationships
     * @return void
     */
    public function testDetachingRelationships(): void
    {
        /** @var Article $article */
        $article = Article::factory()->create();
        /** @var Collection<int, Tag> $tags */
        $tags = Tag::factory()->count(3)->create();

        $article->tags()->attach($tags->pluck('id'));
        $this->assertCount(3, $article->tags);

        /** @var Tag $firstTag */
        $firstTag = $tags->first();

        $article->tags()->detach($firstTag->id);
        /** @var Article $article */
        $article = $article->fresh();
        $this->assertCount(2, $article->tags);

        // Detach all tags
        $article->tags()->detach();
        /** @var Article $article */
        $article = $article->fresh();
        $this->assertCount(0, $article->tags);
    }

    /**
     * Test syncing relationships
     * @return void
     */
    public function testSyncingRelationships(): void
    {
        /** @var Article $article */
        $article = Article::factory()->create();
        /** @var Collection<int, Tag> $tags */
        $tags = Tag::factory()->count(5)->create();

        // Initial sync
        $article->tags()->sync($tags->take(3)->pluck('id'));
        /** @var Article $article */
        $article = $article->fresh();
        $this->assertCount(3, $article->tags);

        // Sync with different tags
        $article->tags()->sync($tags->skip(2)->take(3)->pluck('id'));
        /** @var Article $article */
        $article = $article->fresh();
        $this->assertCount(3, $article->tags);

        // Verify the correct tags are attached
        /** @var Article $article */
        $article = $article->fresh();
        $syncedTagIds = $article->tags->pluck('id')->toArray();
        $expectedTagIds = $tags->skip(2)->take(3)->pluck('id')->toArray();

        $this->assertEquals(sort($expectedTagIds), sort($syncedTagIds));
    }

    /**
     * Test updating model attributes
     * @return void
     */
    public function testUpdatingModelAttributes(): void
    {
        /** @var Article $article */
        $article = Article::factory()->create(['name' => 'Original Name']);
        /** @var Tag $tag */
        $tag = Tag::factory()->create(['name' => 'Original Tag']);

        $article->update(['name' => 'Updated Name']);
        $tag->update(['name' => 'Updated Tag']);
        /** @var Article $article */
        $article = $article->fresh();
        /** @var Tag $tag */
        $tag = $tag->fresh();
        $this->assertEquals('Updated Name', $article->name);

        $this->assertEquals('Updated Tag', $tag->name);
    }

    /**
     * Test model deletion
     * @return void
     */
    public function testModelDeletion(): void
    {
        /** @var Article $article */
        $article = Article::factory()->create();
        /** @var Tag $tag */
        $tag = Tag::factory()->create();

        $article->tags()->attach($tag->id);

        $articleId = $article->id;
        $tagId = $tag->id;

        // Delete article - should not delete tag
        $article->delete();

        $this->assertNull(Article::find($articleId));
        $this->assertNotNull(Tag::find($tagId));

        // Verify tag still exists
        $this->assertEquals($tag->name, Tag::find($tagId)->name);
    }

    /**
     * Test pivot table functionality
     * @return void
     */
    public function testPivotTableFunctionality(): void
    {
        /** @var Article $article */
        $article = Article::factory()->create();
        /** @var Tag $tag */
        $tag = Tag::factory()->create();

        $article->tags()->attach($tag->id);

        // Test that the relationship exists in pivot table
        $this->assertTrue(
            \DB::table('tags_articles')
                ->where('article_id', $article->id)
                ->where('tag_id', $tag->id)
                ->exists()
        );

        $article->tags()->detach($tag->id);

        // Test that the relationship is removed from pivot table
        $this->assertFalse(
            \DB::table('tags_articles')
                ->where('article_id', $article->id)
                ->where('tag_id', $tag->id)
                ->exists()
        );
    }
}
