<?php

namespace App\Http\Controllers;

use App\Models\EventCategory;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EventCategoryController extends Controller
{

    public function __construct()
    {
    }

    public function get($id = 0)
    {
        try {
            if (empty($id)) {
                $result = EventCategory::all();
            } else {
                $result = EventCategory::findOrFail($id);
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
                'id_category' => 'required|integer',
            ]);

            $event = new EventCategory();
            $event->id_event = $request->id_event;
            $event->id_category = $request->id_category;


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

    public function update($id = 0)
    {
        try {
            $request = request();
            $request->validate([
                'id_event' => 'required|integer',
                'id_category' => 'required|integer',
            ]);

            $event = EventCategory::findOrFail($id);
            $event->id_event = $request->id_event;
            $event->id_category = $request->id_category;


            $event->update();

            return response()->json(['message' => 'update create', 'data' => $event], 200);
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

    public function delete($id = 0)
    {
        try {
            $eventCategory = EventCategory::findOrFail($id);

            $eventCategory->delete();

            return response()->json(
                [
                    'message' => 'Delete Success',
                    'data' => $eventCategory,
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
