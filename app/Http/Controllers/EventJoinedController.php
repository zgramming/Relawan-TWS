<?php

namespace App\Http\Controllers;

use App\Models\EventJoined;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EventJoinedController extends Controller
{
    public function get($id = 0)
    {
        try {
            if (empty($id)) {
                $result = EventJoined::all();
            } else {
                $result = EventJoined::findOrFail($id);
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

    public function create()
    {
        try {
            $request = request();
            $request->validate([
                'id_event' => 'required|integer',
                'id_user' => 'required|integer',
                'joined_date' => 'date|required'
            ]);

            $event = new EventJoined();
            $event->id_event = $request->id_event;
            $event->id_user = $request->id_user;
            $event->joined_date = $request->joined_date;

            $event->save();

            return response()->json(['message' => 'success create', 'data' => $event], 201);
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

            $eventJoined = EventJoined::findOrFail($request->id);

            $eventJoined->delete();

            return response()->json(
                [
                    'message' => 'Delete Success',
                    'data' => $eventJoined,
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'code' => $e->getCode()]);
        }
    }
}
