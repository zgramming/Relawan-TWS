<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Intervention\Image\ImageManagerStatic as Image;

class UserController extends Controller
{

    public function get($id = 0)
    {

        try {
            if (empty($id)) {
                $user  = User::all();
            } else {
                $user = User::findOrFail($id);
            }

            return response()->json(['message' => 'Success get data', 'data' => $user]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'code' => $e->getCode()]);
        }
    }

    public function login()
    {
        try {
            $request = request();

            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $user = User::where('email', $request->email)->first();

            if (empty($user)) {
                throw new Exception("Email " . $request->email . " tidak terdaftar", 404);
            }

            $isPasswordMatching = Hash::check($request->password, $user->password);

            if (!$isPasswordMatching) {
                throw new Exception('Password tidak valid', 400);
            }

            return response()->json(['message' => 'Login Success', 'data' => $user], 200);
        } catch (ValidationException $e) {
            /// Get first error with [current] function
            return response()->json(['validation_error' => $e->errors()], 400);
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

            $rules = [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed',
                'type' => 'required'
            ];

            if ($request->type == "organisasi") {
                $rules['id_type_organization'] = "required|integer";
                $rules['address'] = "required";
            }

            $request->validate($rules);

            $user = new User();
            $user->name  = $request->name;
            $user->email  = $request->email;
            $user->password  = Hash::make($request->password);
            $user->type  = $request->type;
            $user->picture_profile = $request->picture_profile;

            if ($request->type == "organisasi") {
                $user->id_type_organization = $request->id_type_organization;
                $user->address = $request->address;
            }
            $user->save();

            $user = User::find($user->id);

            return response()->json(['message' => 'Create Success', 'data' => $user]);
        } catch (ValidationException $e) {
            /// Get first error with [current] function
            return response()->json(['validation_error' => $e->errors()], 400);
        } catch (QueryException $e) {
            return response()->json(['sql_code' => $e->getSql(), 'message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            $code = $e->getCode();
            $message = $e->getMessage();
            return response()->json(['message' => $message], $code);
        }
    }

    public function update($id = 0)
    {
        try {
            $request  = request();

            $request->validate([
                'name' => 'required',
            ]);

            $user = User::findOrFail($id);
            $user->name = $request->name;

            if ($user->type == "relawan") {
                ///? Relawan
                $user->birth_date = $request->birth_date;
                $user->phone = $request->phone;

                if ($request->hasFile('picture_profile')) {
                    $image = $request->file('picture_profile');
                    $filename = ($user->picture_profile == null || empty($user->picture_profile)) ? uniqid() . "." . $image->getClientOriginalExtension() : $user->picture_profile;
                    $path = public_path() . '/user/image/';

                    if (!File::exists($path)) {
                        File::makeDirectory($path, 0777, true);
                    }

                    Image::make($image)->resize(1000, 1000)->save($path . $filename);
                    $user->picture_profile = $filename;
                }
            } else {
                ///? Organisasi
                $user->address = $request->address;
                $user->website = $request->website;
                $user->whatsapp_contact = $request->whatsapp_contact;
                $user->email_contact = $request->email_contact;
                $user->instagram_contact = $request->instagram_contact;

                if ($request->hasFile('logo')) {
                    $image = $request->file('logo');
                    $filename = ($user->logo == null || empty($user->logo)) ? uniqid() . "." . $image->getClientOriginalExtension() : $user->logo;
                    $path = public_path() . '/user/image/';

                    if (!File::exists($path)) {
                        File::makeDirectory($path, 0777, true);
                    }

                    Image::make($image)->resize(1000, 1000)->save($path . $filename);
                    $user->logo = $filename;
                }
            }

            $user->update();

            $user = User::find($user->id);

            return response()->json(['message' => 'Update Success', 'data' => $user], 200);
        } catch (ValidationException $e) {
            /// Get first error with [current] function
            return response()->json(['validation_error' => $e->errors()], 400);
        } catch (QueryException $e) {
            return response()->json(['sql_code' => $e->getSql(), 'message' => $e->getMessage()], 400);
        } catch (\Exception $e) {

            $code = $e->getCode() ?: 400;
            $message = $e->getMessage();
            return response()->json(['message' => $message], $code);
        }
    }

    public function delete($id = 0)
    {
        try {
            $user = User::findOrFail($id);

            $user->delete();

            return response()->json(
                [
                    'message' => 'Delete Success',
                    'data' => $user,
                ],
                200,
            );
        } catch (ValidationException $e) {
            /// Get first error with [current] function
            return response()->json(['validation_error' => $e->errors()], 400);
        } catch (QueryException $e) {
            return response()->json(['sql_code' => $e->getSql(), 'message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            $code = $e->getCode() ?: 400;
            $message = $e->getMessage();
            return response()->json(['message' => $message], $code);
        }
    }

    private function updateOrganisasi()
    {
    }

    private function updateRelawan()
    {
    }
}
