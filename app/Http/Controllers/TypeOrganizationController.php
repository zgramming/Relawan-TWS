<?php

namespace App\Http\Controllers;

use App\Models\TypeOrganization;

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
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'code' => $e->getCode()]);
        }
    }

    public function create()
    {
        try {
            $request = request();

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
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'code' => $e->getCode()]);
        }
    }

    public function update()
    {
        try {
            $request = request();
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
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'code' => $e->getCode()]);
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
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'code' => $e->getCode()]);
        }
    }
}
