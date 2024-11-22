<?php

namespace App\Http\Controllers;

use App\Contracts\ArticleRepositoryInterface;
use App\Dto\ArticleFilterDto;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ArticleController extends Controller
{
    /**
     * @var array|string[]
     */
    private array $rules = [
        'name' => 'required|max:255',
        'tags' => 'filled|array',
        'tags.*.name' => 'required|max:255'
    ];


    public function show(int $id): JsonResponse
    {
        $article = Article::whereId($id)->with('tags')->get();

        return response()->json($article);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request, $this->rules);

        $article = Article::create(['name' => $request->get('name')]);

        $tags = $request->get('tags');
        if (is_array($tags)) {
            foreach ($tags as $tag) {
                $tag = Tag::firstOrNew(['name' => $tag['name']]);
                $article->tags()->save($tag);
            }
        }

        $article->save();
        return response()->json(['id' => $article->id, 'name' => $article->name, 'tags' => $article->tags]);
    }

    /**
     * @param int $id
     * @param Request $request
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(int $id, Request $request): JsonResponse
    {
        $this->validate($request, $this->rules);
        /** @var array{name:string} $payload */
        $payload = $request->toArray();
        $article = Article::findOrFail($id);
        $article->name = $payload['name'];
        $article->save();

        return response()->json($article);
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $article = (new Article())->findOrFail($id);
        try {
            $article->deleteOrFail();
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }

        return response()->json(['success' => true]);
    }

    /**
     * @param Request $request
     * @param ArticleRepositoryInterface $articleRepository
     * @return JsonResponse
     * @throws ValidationException
     */
    public function showlist(Request $request, ArticleRepositoryInterface $articleRepository): JsonResponse
    {
        $this->validate(
            $request,
            [
                'tags.*' => 'exists:tags,id',
            ]
        );

        $tags = $request->get('tags');
        $name = $request->get('name');

        $filterDto = new ArticleFilterDto();

        if (is_array($tags)) {
            $filterDto->setTagsIds(array_column($tags, 'id'));
        }

        if (is_string($name) && trim($name) !== '') {
            $filterDto->setName($name);
        }

        return response()->json($articleRepository->findByFilter($filterDto));
    }
}
