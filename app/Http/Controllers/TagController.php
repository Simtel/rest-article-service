<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TagController extends Controller
{
    /**
     * @var array|string[]
     */
    private array $rules = [
        'name' => 'required|max:255|unique:tags',
    ];

    public function create(Request $request): JsonResponse
    {
        $this->validate($request, $this->rules);

        $tag = Tag::create(['name' => $request->get('name')]);

        return response()->json($tag);
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

        $tag = Tag::findOrFail($id);

        $tag->name = $request->get('name');
        $tag->save();

        return response()->json($tag);
    }



}
