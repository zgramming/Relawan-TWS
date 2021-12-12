<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Traits\EventJoinedTrait;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use Intervention\Image\ImageManagerStatic as Image;

class EventController extends Controller
{
    use EventJoinedTrait;

    public function get($idEvent = 0, $idUser = 0)
    {
        try {
            if (empty($idEvent)) {
                $result = Event::all();
            } else {

                $joinedEvent = $this->getJoinedEvent($idEvent);
                $totalJoined = $this->getTotalJoinedEvent($idEvent);
                $isAlreadyJoin = $this->isUserAlreadyJoinEvent($idUser, $idEvent) ?? false;

                $result = DB::table(TABLE_EVENT . " as t1")
                    ->select(
                        [
                            't1.id',
                            't1.title',
                            't1.type',
                            't1.description',
                            't1.start_date',
                            't1.end_date',
                            't1.location',
                            't1.quota',
                            't1.image',
                            't1.updated_at',
                            't2.name as nama_category',
                            't3.name as nama_organisasi',
                            't3.website as website_organisasi',
                            't3.whatsapp_contact as whatsapp_organisasi',
                            't3.email_contact as email_organisasi',
                            't3.instagram_contact as instagram_organisasi',
                        ]
                    )
                    ->join(TABLE_CATEGORY . " AS t2", "t1.id_category", "=", "t2.id")
                    ->join(TABLE_USERS . " AS t3", "t1.id_organization", "=", "t3.id")
                    ->where("t1.id", "=", $idEvent)
                    ->first();

                if ($result == null) {
                    throw new Exception("Event dengan id $idEvent tidak ditemukan, event sudah dihapus ", 404);
                }

                $result->total_joined_event = $totalJoined;
                $result->joined_event = $joinedEvent;
                $result->is_already_join_event = (!$isAlreadyJoin) ? false : true;
            }

            return response()->json(['message' => 'Success get', 'data' => $result]);
        } catch (QueryException $e) {
            return response()->json(['sql_code' => $e->getSql(), 'message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            $code = $e->getCode() ?: 400;
            $message = $e->getMessage();
            return response()->json(['message' => $message], $code);
        }
    }

    public function nearestDate()
    {
        try {
            $result = DB::table(TABLE_EVENT . " AS t1")
                ->select(
                    [
                        't1.id',
                        't1.title',
                        't1.start_date',
                        't1.end_date',
                        't1.type',
                        't1.quota',
                        't1.image',
                        "t2.name as nama_category",
                        "t3.name as nama_organisasi"
                    ]
                )
                ->join(TABLE_CATEGORY . " AS t2", "t1.id_category", "=", "t2.id")
                ->join(TABLE_USERS . " AS t3", "t1.id_organization", "=", "t3.id")
                ->where("t1.start_date", ">=", date('Y-m-d H:i:s'))
                ->orderBy('t1.start_date')
                ->limit(5)
                ->get();
            return response()->json(['message' => 'Success get', 'data' => $result]);
        } catch (QueryException $e) {
            return response()->json(['sql_code' => $e->getSql(), 'message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            $code = $e->getCode() ?: 400;
            $message = $e->getMessage();
            return response()->json(['message' => $message], $code);
        }
    }

    public function forYou()
    {
        try {
            $result = DB::table(TABLE_EVENT . " AS t1")
                ->select(
                    [
                        't1.id',
                        't1.title',
                        't1.start_date',
                        't1.end_date',
                        't1.type',
                        't1.quota',
                        't1.image',
                        "t2.name as nama_category",
                        "t3.name as nama_organisasi"
                    ]
                )
                ->join(TABLE_CATEGORY . " AS t2", "t1.id_category", "=", "t2.id")
                ->join(TABLE_USERS . " AS t3", "t1.id_organization", "=", "t3.id")
                ->where("t1.start_date", ">=", date('Y-m-d H:i:s'))
                ->orderBy('t1.start_date')
                ->get();

            return response()->json(['message' => 'Success get', 'data' => $result]);
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
                'id_category' => 'required|integer',
                'title' => 'required',
                'description' => 'required',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'location' => 'required',
                'type' => 'required',
                'quota' => 'required|integer',
                'image' => 'image'

            ]);

            $event = new Event();
            $event->id_organization = $request->id_organization;
            $event->id_category = $request->id_category;
            $event->title = $request->title;
            $event->description = $request->description;
            $event->start_date = $request->start_date;
            $event->end_date = $request->end_date;
            $event->location = $request->location;
            $event->latitude = $request->latitude;
            $event->longitude = $request->longitude;
            $event->type = $request->type;
            $event->quota = $request->quota;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = uniqid() . "." . $image->getClientOriginalExtension();
                $path = public_path() . '/event/image/';

                if (!File::exists($path)) {
                    File::makeDirectory($path, 0777, true);
                }

                Image::make($image)->resize(1000, 1000)->save($path . $filename);
                $event->image = $filename;
            }

            $event->save();
            $event = Event::find($event->id);

            return response()->json(['message' => 'success create', 'data' => $event], 201);
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

    public function update($id = 0)
    {
        try {
            $request = request();

            $request->validate([
                'id_organization' => 'required|integer',
                'id_category' => 'required|integer',
                'title' => 'required',
                'description' => 'required',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'location' => 'required',
                'type' => 'required',
                'quota' => 'required|integer',
                'image' => 'required',
            ]);

            $event = Event::findOrFail($id);
            $event->id_organization = $request->id_organization;
            $event->title = $request->title;
            $event->description = $request->description;
            $event->start_date = $request->start_date;
            $event->end_date = $request->end_date;
            $event->location = $request->location;
            $event->latitude = $request->latitude;
            $event->longitude = $request->longitude;
            $event->type = $request->type;
            $event->quota = $request->quota;

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
            $event = Event::findOrFail($id);
            $event->delete();
            return response()->json(['message' => 'Berhasil menghapus event'], 200);
        } catch (QueryException $e) {
            return response()->json(['sql_code' => $e->getSql(), 'message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            $code = $e->getCode() ?: 400;
            $message = $e->getMessage();
            return response()->json(['message' => $message], $code);
        }
    }
}
