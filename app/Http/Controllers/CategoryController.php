<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function create()
    {
        try {
            $request = request();

            $category = new Category();
            $category->name = $request->name;
            $category->description = $request->description;
            $category->save();

            return response()->json(
                [
                    'message' => 'Create Success',
                    'data' => $category,

                ],
                201,
            );
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'code' => $e->getCode()]);
        }
    }

    public function update()
    {
        try {
            $request = request();
            $category = Category::findOrFail($request->id);

            $category->name = $request->name;
            $category->description = $request->description;
            $category->update();
            return response()->json(
                [
                    'message' => 'Update Success',
                    'data' => $category,
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'code' => $e->getCode()]);
        }
    }

    public function delete()
    {
        try {
            $request = request();
            $category = Category::findOrFail($request->id);

            $category->delete();

            return response()->json(
                [
                    'message' => 'Delete Success',
                    'data' => $category,
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'code' => $e->getCode()]);
        }
    }
}
