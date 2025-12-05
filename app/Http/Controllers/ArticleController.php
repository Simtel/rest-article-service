<?php

namespace App\Http\Controllers;

use App\Contracts\ArticleRepositoryInterface;
use App\Dto\ArticleFilterDto;
use App\Models\Article;
use App\Models\Tag;
use App\Services\Validation\ArticleValidationService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class ArticleController extends Controller
{
    public function __construct(
        private readonly ArticleValidationService $validationService,
        private readonly ArticleRepositoryInterface $articleRepository
    ) {
    }

    /**
     * Display the specified article with its tags.
     */
    public function show(int $id): JsonResponse
    {
        $article = Article::whereId($id)->with('tags')->get();
        return response()->json($article);
    }

    /**
     * Create a new article with optional tags.
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $validatedData = $this->validationService->validateCreate($request);

            $article = Article::create([
                'name' => $validatedData['name']
            ]);

            // Attach tags if provided
            if (!empty($validatedData['tags']) && is_array($validatedData['tags'])) {
                $this->attachTagsToArticle($article, $validatedData['tags']);
            }

            // Load the article with its tags for response
            $article->load('tags');

            return response()->json([
                'id' => $article->id,
                'name' => $article->name,
                'tags' => $article->tags
            ]);
        } catch (ValidationException $e) {
            return response()->json($e->errors(), 422);
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified article.
     */
    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $validatedData = $this->validationService->validateUpdate($request);

            $article = Article::findOrFail($id);
            $article->update([
                'name' => $validatedData['name']
            ]);

            // Update tags if provided
            if (isset($validatedData['tags']) && is_array($validatedData['tags'])) {
                $article->tags()->detach(); // Remove existing tags
                $this->attachTagsToArticle($article, $validatedData['tags']);
            }

            $article->load('tags');

            return response()->json($article);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Article not found'], 404);
        } catch (ValidationException $e) {
            return response()->json($e->errors(), 422);
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified article.
     */
    public function delete(int $id): JsonResponse
    {
        try {
            $article = Article::findOrFail($id);
            $article->delete();

            return response()->json(['success' => true]);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Article not found'], 404);
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display a list of articles with optional filtering.
     */
    public function showlist(Request $request): JsonResponse
    {
        try {
            $validatedData = $this->validationService->validateList($request);

            $filterDto = new ArticleFilterDto();

            // Set tag filter if provided
            if (!empty($validatedData['tags']) && is_array($validatedData['tags'])) {
                // Extract IDs from array of objects with 'id' key
                /** @var int[] $tagIds */
                $tagIds = array_column($validatedData['tags'], 'id');
                // Ensure we have an array of integers
                $tagIds = array_filter(array_map(fn ($id): int => (int)$id, $tagIds));
                if (!empty($tagIds)) {
                    $filterDto->setTagsIds($tagIds);
                }
            }

            // Set name filter if provided
            if (!empty($validatedData['name']) && is_string($validatedData['name'])) {
                $trimmedName = trim($validatedData['name']);
                if ($trimmedName !== '') {
                    $filterDto->setName($trimmedName);
                }
            }

            $articles = $this->articleRepository->findByFilter($filterDto);

            return response()->json($articles);
        } catch (ValidationException $e) {
            return response()->json($e->errors(), 422);
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Attach tags to an article.
     *
     * @param Article $article
     * @param mixed[] $tags
     * @return void
     */
    private function attachTagsToArticle(Article $article, array $tags): void
    {
        foreach ($tags as $tagData) {
            if (is_array($tagData) && isset($tagData['name']) && is_string($tagData['name'])) {
                $tag = Tag::firstOrCreate(['name' => $tagData['name']]);
                $article->tags()->save($tag);
            }
        }
    }
}
