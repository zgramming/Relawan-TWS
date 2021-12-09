<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    protected $organizationController;

    public function __construct(OrganizationController $organizationController)
    {
        $this->organizationController = $organizationController;
    }

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
        DB::beginTransaction();
        try {
            $request = request();
            $request->validate(
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required',
                    'type' => 'required'
                ]
            );
            $user = new User();
            $user->name  = $request->name;
            $user->email  = $request->email;
            $user->password  = Hash::make($request->password);
            $user->type  = $request->type;
            $user->birth_date = $request->birth_date;
            $user->picture_profile = $request->picture_profile;
            $user->save();

            if ($request->type == 'organization') {
                $this->organizationController->create($user->id);
            }

            DB::commit();
            return response()->json(['message' => 'Create Success', 'data' => $user]);
        } catch (ValidationException $e) {
            DB::rollBack();
            /// Get first error with [current] function
            return response()->json(['validation_error' => $e->errors()], 400);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['sql_code' => $e->getSql(), 'message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            DB::rollBack();
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
                'birth_date' => 'required|date'
            ]);

            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->birth_date = $request->birth_date;

            $user->update();

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
}
