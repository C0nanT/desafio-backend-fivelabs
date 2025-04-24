<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tags;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    /**
     * Create a new TagController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the Tags.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (auth()->user()->is_admin) {
            $tags = Tags::all();
        } else {
            $tags = Tags::where('created_by', auth()->id())->get();
        }

        if ($tags->isEmpty()) {
            return response()->json([
                'message' => 'No Tags found'
            ], 404);
        }

        return response()->json([
            'data' => $tags
        ]);
    }

    /**
     * Store a newly created Tag in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try{

            $request->validate([
                'name' => 'required|string|max:255|unique:tags,name',
                'slug' => 'required|string|max:255|unique:tags,slug',
                'description' => 'nullable|string|max:255',
                'color' => 'string|max:255',
            ]);
        }catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->validator->errors()
            ], 422);
        }

        $tag = Tags::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'color' => $request->color,
            'created_by' => auth()->id(),
        ]);

        return response()->json([
            'message' => 'Tag created successfully',
            'data' => $tag
        ], 201);
    }

    /**
     * Display the specified Tag.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $tag = Tags::find($id);

        if (!$tag) {
            return response()->json([
                'message' => 'Tag not found'
            ], 404);
        }

        return response()->json([
            'data' => $tag
        ]);
    }

    /**
     * Update the specified Tag in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try{
            $request->validate([
                'name' => 'string|max:255|unique:tags,name,' . $id,
                'slug' => 'string|max:255|unique:tags,slug,' . $id,
                'description' => 'string|max:255',
                'color' => 'string|max:255',
            ]);
        }catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->validator->errors()
            ], 422);
        }

        if(auth()->user()->is_admin) {
            $tag = Tags::find($id);
        } else {
            $tag = Tags::where('created_by', auth()->id())->find($id);
        }

        if (!$tag) {
            return response()->json([
                'message' => 'Tag not found'
            ], 404);
        }

        $tag->update($request->all());

        return response()->json([
            'message' => 'Tag updated successfully',
            'data' => $tag
        ]);
    }

    /**
     * Remove the specified Tag from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {

        if(auth()->user()->is_admin) {
            $tag = Tags::find($id);
        } else {
            $tag = Tags::where('created_by', auth()->id())->find($id);
        }

        if (!$tag) {
            return response()->json([
                'message' => 'Tag not found'
            ], 404);
        }

        $tag->delete();

        return response()->json([
            'message' => 'Tag deleted successfully'
        ]);
    }
}