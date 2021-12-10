<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Intervention\Image\ImageManagerStatic as Image;


class OrganizationController extends Controller
{
    public function get($id = 0)
    {
        try {

            if (empty($id)) {
                $result = Organization::all();
            } else {
                $result = Organization::findOrFail($id);
            }

            return response()->json(['message' => 'Success get', 'data' => $result]);
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

    public function create(int $idUser = 0)
    {
        try {

            $request = request();

            $validator = Validator::make($request->all(), [
                'id_type_organization' => 'required|integer',
                'name' => 'required',
                'date_establishment' => 'required|date',
                'address' => 'required'
            ]);

            if ($validator->fails()) {
            }

            $organization = new Organization();
            $organization->id_user = $idUser;
            $organization->id_type_organization = $request->id_type_organization;
            $organization->name = $request->name;
            $organization->date_establishment = $request->date_establishment;
            $organization->address = $request->address;
            $organization->phone = $request->phone;
            $organization->website = $request->website;

            if ($request->hasFile('logo')) {
                $image = $request->file('logo');
                $filename = uniqid() . "." . $image->getClientOriginalExtension();
                $path = public_path() . '/organization/image/';

                if (!File::exists($path)) {
                    File::makeDirectory($path, 0777, true);
                }

                Image::make($image)->resize(400, 400)->save($path . $filename);
                $organization->logo = $filename;
            }

            $organization->save();

            // return true;
            return true;
        } catch (ValidationException $e) {
            /// Get first error with [current] function
            // return response()->json(['error' => current($e->errors())], 400);
            throw new Exception(current($e->errors()), 400);
        } catch (QueryException $e) {
            // return response()->json(['sql_code' => $e->getSql(), 'message' => $e->getMessage()], 400);
            throw new Exception($e->getSql(), 400);
        } catch (\Exception $e) {
            $code = $e->getCode() ?: 400;
            $message = $e->getMessage();
            // return response()->json(['message' => $message], $code);
            throw new Exception($message, $code);
        }
    }

    public function update()
    {
        try {
            $request = request();
            $request->merge(['_method' => 'PUT']);

            $request->validate([
                'id_user' => 'required|integer',
                'id_type_organization' => 'required|integer',
                'name' => 'required',
                'date_establishment' => 'required|date',
                'address' => 'required'
            ]);

            $organization = Organization::findOrFail($request->id);
            $organization->id_user = $request->id_user;
            $organization->id_type_organization = $request->id_type_organization;
            $organization->name = $request->name;
            $organization->date_establishment = $request->date_establishment;
            $organization->address = $request->address;
            $organization->phone = $request->phone;
            $organization->website = $request->website;

            if ($request->hasFile('logo')) {
                $image = $request->file('logo');
                $filename = $organization->logo;
                $path = public_path() . '/organization/image/';

                if (!File::exists($path)) {
                    File::makeDirectory($path, 0777, true);
                }

                Image::make($image)->resize(400, 400)->save($path . $filename);
                $organization->logo = $filename;
            }

            $organization->update();

            // return true;
            return true;
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
    }
}
