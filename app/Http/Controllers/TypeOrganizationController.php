<?php

namespace App\Http\Controllers;

use App\Models\TypeOrganization;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class TypeOrganizationController extends Controller
{
    public function get($id = 0)
    {
        try {
            if (empty($id)) {

                $result = TypeOrganization::all();
            } else {
                $result = TypeOrganization::findOrFail($id);
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

            $typeOrganization = new TypeOrganization();
            $typeOrganization->name = $request->name;
            $typeOrganization->description = $request->description;
            $typeOrganization->save();

            return response()->json(
                [
                    'message' => 'Create Success',
                    'data' => $typeOrganization,

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

            $typeOrganization = TypeOrganization::findOrFail($request->id);

            $typeOrganization->name = $request->name;
            $typeOrganization->description = $request->description;
            $typeOrganization->update();
            return response()->json(
                [
                    'message' => 'Update Success',
                    'data' => $typeOrganization,
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

    public function delete()
    {
        try {
            $request = request();
            $typeOrganization = TypeOrganization::findOrFail($request->id);

            $typeOrganization->delete();

            return response()->json(
                [
                    'message' => 'Delete Success',
                    'data' => $typeOrganization,
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
