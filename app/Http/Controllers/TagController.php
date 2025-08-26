<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Services\Validation\TagValidationService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class TagController extends Controller
{
    public function __construct(
        private readonly TagValidationService $validationService
    ) {
    }

    /**
     * Create a new tag.
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $validatedData = $this->validationService->validateCreate($request);

            $tag = Tag::create([
                'name' => $validatedData['name']
            ]);

            return response()->json($tag);
        } catch (ValidationException $e) {
            return response()->json($e->errors(), 422);
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified tag.
     */
    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $validatedData = $this->validationService->validateUpdate($request, $id);

            $tag = Tag::findOrFail($id);
            $tag->update([
                'name' => $validatedData['name']
            ]);

            return response()->json($tag);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Tag not found'], 404);
        } catch (ValidationException $e) {
            return response()->json($e->errors(), 422);
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified tag.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $tag = Tag::findOrFail($id);
            return response()->json($tag);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Tag not found'], 404);
        }
    }

    /**
     * Display a list of all tags.
     */
    public function index(): JsonResponse
    {
        try {
            $tags = Tag::all();
            return response()->json($tags);
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified tag.
     */
    public function delete(int $id): JsonResponse
    {
        try {
            $tag = Tag::findOrFail($id);
            $tag->delete();

            return response()->json(['success' => true]);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Tag not found'], 404);
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
