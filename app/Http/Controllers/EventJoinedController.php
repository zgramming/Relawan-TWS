<?php

namespace App\Http\Controllers;

use App\Http\Traits\EventJoinedTrait;

use App\Models\EventJoined;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EventJoinedController extends Controller
{
    use EventJoinedTrait;

    public function create()
    {
        try {
            $request = request();
            $request->validate([
                'id_event' => 'required|integer',
                'id_user' => 'required|integer',
            ]);

            $isAlreadyJoinEvent = $this->isUserAlreadyJoinEvent($request->id_user, $request->id_event) ?? false;

            $attributes = [
                'id_event' => $request->id_event,
                'id_user' => $request->id_user,
            ];

            $values = [
                'id_event' => $request->id_event,
                'id_user' => $request->id_user,
            ];

            /// If user already join, then call it API again
            /// Then we should change status to cancel, it means user cancel join the event

            if ($isAlreadyJoinEvent) {
                $values['status'] = "cancel";
                $values['cancel_date'] = date('Y-m-d H:i:s');
                $message = "Berhasil membatalkan ikut event";
            } else {
                $values['status'] = "join";
                $values['joined_date'] = date('Y-m-d H:i:s');
                $message = "Berhasil mengikuti event";
            }

            DB::table(TABLE_EVENT_JOINED)->updateOrInsert($attributes, $values);

            return response()->json(['message' => $message], 201);
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
            $request = request();

            $eventJoined = EventJoined::findOrFail($id);

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
