use App\Dto\ArticleFilterDto;
use App\Models\Article;
use App\Models\Tag;
use App\Repositories\ArticleRepository;
use Illuminate\Database\Eloquent\Collection;

class ArticleRepositoryTest extends TestCase
{
    private ArticleRepository $repository;

    /**
     * @var \App\Repositories\ArticleRepository
     */

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ArticleRepository();
    }

    /**
     * @return void
     */
    public function testFindByFilterWithNoFilters(): void
    {
        // Create test data
        Article::factory()->count(3)->create();

        $dto = new ArticleFilterDto();
        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Article> $result */
        $result = $this->repository->findByFilter($dto);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(3, $result);

        // Ensure tags are loaded
        $this->assertTrue($result->first()->relationLoaded('tags'));
    }

    /**
     * @return void
     */
    public function testFindByFilterWithNameFilter(): void
    {
        // Create test articles
        Article::factory()->create(['name' => 'Test Article One']);
        Article::factory()->create(['name' => 'Another Article']);
        Article::factory()->create(['name' => 'Test Article Two']);

        $dto = new ArticleFilterDto();
        $dto->setName('Test Article');

        $result = $this->repository->findByFilter($dto);

        $this->assertCount(2, $result);
        $this->assertTrue($result->contains('name', 'Test Article One'));
        $this->assertTrue($result->contains('name', 'Test Article Two'));
        $this->assertFalse($result->contains('name', 'Another Article'));
    }

    /**
     * @return void
     */
    public function testFindByFilterWithPartialNameMatch(): void
    {
        // Create test articles
        Article::factory()->create(['name' => 'Programming with PHP']);
        Article::factory()->create(['name' => 'Learning JavaScript']);
        Article::factory()->create(['name' => 'Advanced PHP Techniques']);

        $dto = new ArticleFilterDto();
        $dto->setName('PHP');

        $result = $this->repository->findByFilter($dto);

        $this->assertCount(2, $result);
        $this->assertTrue($result->contains('name', 'Programming with PHP'));
        $this->assertTrue($result->contains('name', 'Advanced PHP Techniques'));
        $this->assertFalse($result->contains('name', 'Learning JavaScript'));
    }

    /**
     * @return void
     */
    public function testFindByFilterWithCaseInsensitiveNameSearch(): void
    {
        Article::factory()->create(['name' => 'Laravel Framework']);
        Article::factory()->create(['name' => 'Vue.js Tutorial']);

        $dto = new ArticleFilterDto();
        $dto->setName('laravel');

        $result = $this->repository->findByFilter($dto);

        $this->assertCount(1, $result);
        $this->assertTrue($result->contains('name', 'Laravel Framework'));
    }

    /**
     * @return void
     */
    public function testFindByFilterWithSingleTagFilter(): void
    {
        // Create tags
        $phpTag = Tag::factory()->create(['name' => 'PHP']);
        /** @var \App\Models\Tag $phpTag */
        $jsTag = Tag::factory()->create(['name' => 'JavaScript']);
        /** @var \App\Models\Tag $jsTag */

        // Create articles with tags
        $article1 = Article::factory()->create(['name' => 'Article One']);
        /** @var \App\Models\Article $article1 */
        $article1->tags()->attach($phpTag->id);

        $article2 = Article::factory()->create(['name' => 'Article Two']);
        /** @var \App\Models\Article $article2 */
        $article2->tags()->attach($jsTag->id);

        $article3 = Article::factory()->create(['name' => 'Article Three']);
        /** @var \App\Models\Article $article3 */
        $article3->tags()->attach([$phpTag->id, $jsTag->id]);

        $dto = new ArticleFilterDto();
        $dto->setTagsIds([$phpTag->id]);

        $result = $this->repository->findByFilter($dto);

        $this->assertCount(2, $result);
        $this->assertTrue($result->contains('name', 'Article One'));
        $this->assertTrue($result->contains('name', 'Article Three'));
        $this->assertFalse($result->contains('name', 'Article Two'));
    }

    /**
     * @return void
     */
    public function testFindByFilterWithMultipleTagFilter(): void
    {
        // Create tags
        $phpTag = Tag::factory()->create(['name' => 'PHP']);
        /** @var \App\Models\Tag $phpTag */
        $jsTag = Tag::factory()->create(['name' => 'JavaScript']);
        /** @var \App\Models\Tag $jsTag */
        $laravelTag = Tag::factory()->create(['name' => 'Laravel']);
        /** @var \App\Models\Tag $laravelTag */

        // Create articles with tags
        $article1 = Article::factory()->create(['name' => 'Article One']);
        /** @var \App\Models\Article $article1 */
        $article1->tags()->attach($phpTag->id);

        $article2 = Article::factory()->create(['name' => 'Article Two']);
        /** @var \App\Models\Article $article2 */
        $article2->tags()->attach([$phpTag->id, $laravelTag->id]);

        $article3 = Article::factory()->create(['name' => 'Article Three']);
        /** @var \App\Models\Article $article3 */
        $article3->tags()->attach($jsTag->id);

        $dto = new ArticleFilterDto();
        $dto->setTagsIds([$phpTag->id, $laravelTag->id]);

        $result = $this->repository->findByFilter($dto);

        // Should find articles that have ANY of the specified tags
        $this->assertCount(2, $result);
        $this->assertTrue($result->contains('name', 'Article One'));
        $this->assertTrue($result->contains('name', 'Article Two'));
        $this->assertFalse($result->contains('name', 'Article Three'));
    }

    /**
     * @return void
     */
    public function testFindByFilterWithNonExistentTag(): void
    {
        Article::factory()->count(3)->create();

        $dto = new ArticleFilterDto();
        $dto->setTagsIds([9999]); // Non-existent tag ID

        $result = $this->repository->findByFilter($dto);

        $this->assertCount(0, $result);
    }

    /**
     * @return void
     */
    public function testFindByFilterWithEmptyTagsArray(): void
    {
        Article::factory()->count(3)->create();

        $dto = new ArticleFilterDto();
        $dto->setTagsIds([]); // Empty array should be treated as no filter

        $result = $this->repository->findByFilter($dto);

        // Empty array should be treated as null filter (no tag filtering)
        $this->assertCount(3, $result);
    }

    /**
     * @return void
     */
    public function testFindByFilterWithCombinedFilters(): void
    {
        // Create tags
        $phpTag = Tag::factory()->create(['name' => 'PHP']);
        /** @var \App\Models\Tag $phpTag */
        $jsTag = Tag::factory()->create(['name' => 'JavaScript']);
        /** @var \App\Models\Tag $jsTag */

        // Create articles
        $article1 = Article::factory()->create(['name' => 'PHP Tutorial for Beginners']);
        /** @var \App\Models\Article $article1 */
        $article1->tags()->attach($phpTag->id);

        $article2 = Article::factory()->create(['name' => 'Advanced PHP Techniques']);
        /** @var \App\Models\Article $article2 */
        $article2->tags()->attach($phpTag->id);

        $article3 = Article::factory()->create(['name' => 'JavaScript Fundamentals']);
        /** @var \App\Models\Article $article3 */
        $article3->tags()->attach($jsTag->id);

        $article4 = Article::factory()->create(['name' => 'PHP and JavaScript Together']);
        /** @var \App\Models\Article $article4 */
        $article4->tags()->attach([$phpTag->id, $jsTag->id]);

        $dto = new ArticleFilterDto();
        $dto->setName('PHP');
        $dto->setTagsIds([$phpTag->id]);

        $result = $this->repository->findByFilter($dto);

        // Should find articles that match BOTH name pattern AND have the specified tag
        $this->assertCount(3, $result);
        $this->assertTrue($result->contains('name', 'PHP Tutorial for Beginners'));
        $this->assertTrue($result->contains('name', 'Advanced PHP Techniques'));
        $this->assertTrue($result->contains('name', 'PHP and JavaScript Together'));
        $this->assertFalse($result->contains('name', 'JavaScript Fundamentals'));
    }

    /**
     * @return void
     */
    public function testFindByFilterWithNonExistentName(): void
    {
        Article::factory()->count(3)->create();

        $dto = new ArticleFilterDto();
        $dto->setName('NonExistentArticleName');

        $result = $this->repository->findByFilter($dto);

        $this->assertCount(0, $result);
    }

    /**
     * @return void
     */
    public function testFindByFilterWithEmptyName(): void
    {
        Article::factory()->count(3)->create();

        $dto = new ArticleFilterDto();
        $dto->setName('');

        $result = $this->repository->findByFilter($dto);

        // Empty string should still filter (find articles with empty name parts)
        $this->assertCount(3, $result);
    }

    /**
     * @return void
     */
    public function testFindByFilterWithWhitespaceOnlyName(): void
    {
        Article::factory()->count(3)->create();

        $dto = new ArticleFilterDto();
        $dto->setName('   ');

        $result = $this->repository->findByFilter($dto);

        // Whitespace should be handled by the service layer, but repository should still process it
        $this->assertInstanceOf(Collection::class, $result);
    }

    /**
     * @return void
     */
    public function testFindByFilterEnsuresTagsAreLoaded(): void
    {
        $tag = Tag::factory()->create();
        /** @var \App\Models\Tag $tag */
        $article = Article::factory()->create();
        /** @var \App\Models\Article $article */
        $article->tags()->attach($tag->id);

        $dto = new ArticleFilterDto();
        $result = $this->repository->findByFilter($dto);

        $this->assertCount(1, $result);
        $retrievedArticle = $result->first();

        // Verify tags relationship is loaded
        $this->assertTrue($retrievedArticle->relationLoaded('tags'));
        $this->assertCount(1, $retrievedArticle->tags);
        $this->assertEquals($tag->name, $retrievedArticle->tags->first()->name);
    }

    /**
     * @return void
     */
    public function testFindByFilterReturnsCorrectFormat(): void
    {
        Article::factory()->count(2)->create();

        $dto = new ArticleFilterDto();
        $result = $this->repository->findByFilter($dto);

        $this->assertInstanceOf(Collection::class, $result);

        if ($result->isNotEmpty()) {
            /** @var \App\Models\Article $article */
            $article = $result->first();
            $this->assertInstanceOf(Article::class, $article);
            $this->assertNotNull($article->id);
            $this->assertNotNull($article->name);
            $this->assertTrue($article->relationLoaded('tags'));
        }
    }
}