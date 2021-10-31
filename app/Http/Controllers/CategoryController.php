<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{

    public function get($id = 0)
    {
        try {
            if (empty($id)) {

                $result = Category::all();
            } else {
                $result = Category::findOrFail($id);
            }

            return response()->json(['message' => 'Success Get Data', 'data' => $result], 200);
        } catch (ValidationException $e) {
            /// Get first error with [current] function
            return response()->json(['error' => current($e->errors())], 400);
        } catch (QueryException $e) {
            return response()->json(['sql_code' => $e->getSql(), 'message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            $code = $e->getCode() ?: 400;
            $message = $e->getMessage();
            return response()->json(['message' => $message], $code);
        }
    }

    public function create()
    {
        try {
            $request = request();

            $request->validate([
                'name' => 'required',
                'description' => 'required',
            ]);

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
        } catch (ValidationException $e) {
            /// Get first error with [current] function
            return response()->json(['error' => current($e->errors())], 400);
        } catch (QueryException $e) {
            return response()->json(['sql_code' => $e->getSql(), 'message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            $code = $e->getCode() ?: 400;
            $message = $e->getMessage();
            return response()->json(['message' => $message], $code);
        }
    }

    public function update()
    {
        try {
            $request = request();

            $request->validate([
                'name' => 'required',
                'description' => 'required',
            ]);

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
        } catch (ValidationException $e) {
            /// Get first error with [current] function
            return response()->json(['error' => current($e->errors())], 400);
        } catch (QueryException $e) {
            return response()->json(['sql_code' => $e->getSql(), 'message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            $code = $e->getCode() ?: 400;
            $message = $e->getMessage();
            return response()->json(['message' => $message], $code);
        }
    }

    public function delete($id)
    {
        try {
            $request = request();
            $category = Category::findOrFail($id);

            $category->delete();

            return response()->json(
                [
                    'message' => 'Delete Success',
                    'data' => $category,
                ],
                200,
            );
        } catch (ValidationException $e) {
            /// Get first error with [current] function
            return response()->json(['error' => current($e->errors())], 400);
        } catch (QueryException $e) {
            return response()->json(['sql_code' => $e->getSql(), 'message' => $e->getMessage()], 400);
        } catch (\Exception $e) {

            $code = $e->getCode() ?: 400;
            $message = $e->getMessage();
            return response()->json(['message' => $message], $code);
        }
    }
}
