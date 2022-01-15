<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ArticleController extends Controller
{

    private array $rules = [
        'name' => 'required|max:255'
    ];

    /**
     * @param  int  $id
     *
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        Log::info('ArticleShow:',['id' => $id]);

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

        return response()->json($article);
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
        Log::info('ArticleDelete:',['id' => $id]);
        $article = Article::findOrFail($id);
        try {
            $article->deleteOrFail();
        } catch (\Throwable $e) {
            Log::info('ArticleDeleteError', [$e->getMessage()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }

        return response()->json(['success' => true]);
    }

}
