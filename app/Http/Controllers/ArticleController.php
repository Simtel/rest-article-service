<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ArticleController extends Controller
{

    private array $rules = [
        'name' => 'required|max:255',
        'tags' => 'filled|array',
        'tags.*.name' => 'required|max:255'
    ];

    /**
     * @param  int  $id
     *
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $article = Article::whereId($id)->with('tags')->get();

        return response()->json($article);
    }

    /**
     * @param  Request  $request
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request, $this->rules);

        $article = Article::create(['name' => $request->get('name')]);

        if (!empty($request->get('tags'))) {
            foreach ($request->get('tags') as $tag) {
                $tag = Tag::firstOrNew(['name' => $tag['name']]);
                $article->tags()->save($tag);
            }
        }

        $article->save();
        return response()->json(['id' => $article->id, 'name' => $article->name, 'tags' => $article->tags]);
    }

    /**
     * @param  int  $id
     * @param  Request  $request
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(int $id, Request $request): JsonResponse
    {
        $this->validate($request, $this->rules);

        $article = Article::findOrFail($id);
        $article->name = $request->get('name');
        $article->save();

        return response()->json($article);
    }

    /**
     * @param  int  $id
     *
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $article = Article::findOrFail($id);
        try {
            $article->deleteOrFail();
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }

        return response()->json(['success' => true]);
    }

    /**
     * @param  Request  $request
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function showlist(Request $request)
    {
        $this->validate($request,
            [
                'tags.*' => 'exists:tags,id',
            ]
        );

        if (!empty($request->get('tags'))) {
            $articles = Article::withAllTags(array_column($request->get('tags'), 'id'))->with('tags');
        } else {
            $articles = Article::with('tags');
        }

        return response()->json($articles->get());
    }

}
