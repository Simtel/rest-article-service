<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{

    public function show(int $id): JsonResponse
    {
        $article = Article::find($id)->with('tags')->get();
        //dd($article);
        if ($article === null) {
            return response()->json([], 404);
        }
        return response()->json($article);
    }

}
