<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use Intervention\Image\ImageManagerStatic as Image;

class EventController extends Controller
{
    public function get($id = 0)
    {
        try {
            if (empty($id)) {
                $result = Event::all();
            } else {
                $result = Event::findOrFail($id);
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
                'id_organization' => 'required|integer',
                'title' => 'required',
                'description' => 'required',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'location' => 'required',
                'type' => 'required',
                'quota' => 'required|integer',
                'is_unlimited' => 'required',
                'image' => 'required'
            ]);

            $event = new Event();
            $event->id_organization = $request->id_organization;
            $event->title = $request->title;
            $event->description = $request->description;
            $event->start_date = $request->start_date;
            $event->end_date = $request->end_date;
            $event->location = $request->location;
            $event->type = $request->type;
            $event->quota = $request->quota;
            $event->is_unlimited = $request->is_unlimited;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = uniqid() . "." . $image->getClientOriginalExtension();
                $path = public_path() . '/event/image/';

                if (!File::exists($path)) {
                    File::makeDirectory($path, 0777, true);
                }

                Image::make($image)->resize(400, 400)->save($path . $filename);
                $event->image = $filename;
            }

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
                'id_organization' => 'required|integer',
                'title' => 'required',
                'description' => 'required',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'location' => 'required',
                'type' => 'required',
                'quota' => 'required|integer',
                'is_unlimited' => 'required',
                'image' => 'required',
            ]);

            $event = Event::findOrFail($id);
            $event->id_organization = $request->id_organization;
            $event->title = $request->title;
            $event->description = $request->description;
            $event->start_date = $request->start_date;
            $event->end_date = $request->end_date;
            $event->location = $request->location;
            $event->type = $request->type;
            $event->quota = $request->quota;
            $event->is_unlimited = $request->is_unlimited;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = $event->image;
                $path = public_path() . '/event/image/';

                if (!File::exists($path)) {
                    File::makeDirectory($path, 0777, true);
                }

                Image::make($image)->resize(400, 400)->save($path . $filename);
                $event->image = $filename;
            }

            $event->update();

            return response()->json(['message' => 'success update', 'data' => $event], 201);
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
