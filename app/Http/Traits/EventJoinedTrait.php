<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\DB;

trait EventJoinedTrait
{
    /**
     * @param int $idEvent
     *
     * @return int
     */
    public function getTotalJoinedEvent($idEvent = 0): int
    {
        try {
            $result = DB::table(TABLE_EVENT_JOINED . " AS t1")
                ->select(
                    [
                        "t1.id",
                        "t1.joined_date",
                        "t2.name as nama_relawan",
                        "t2.email as email_relawan",
                        "t2.picture_profile as profile_relawan"
                    ]
                )
                ->join(TABLE_USERS . " AS t2", "t1.id_user", "=", "t2.id")
                ->where("t1.id_event", "=", $idEvent)
                ->where('t1.status', "=", "join")
                ->count('t1.id');

            return $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param int $idUser
     * @param int $idEvent
     *
     * @return object|null
     */
    public function isUserAlreadyJoinEvent(int $idUser = 0, int $idEvent = 0): ?object
    {
        try {
            $obj = DB::table(TABLE_EVENT_JOINED)
                ->where("id_user", "=", $idUser)
                ->where("id_event", "=", $idEvent)
                ->where('status', "=", "join")
                ->first();

            return $obj;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param int $idEvent
     *
     * @return array
     */
    public function getJoinedEvent(int $idEvent): array
    {

        try {
            $result = DB::table(TABLE_EVENT_JOINED . " AS t1")
                ->select(
                    [
                        "t1.id",
                        "t1.joined_date",
                        "t2.name as nama_relawan",
                        "t2.email as email_relawan",
                        "t2.picture_profile as profile_relawan"
                    ]
                )
                ->join(TABLE_USERS . " AS t2", "t1.id_user", "=", "t2.id")
                ->where("t1.id_event", "=", $idEvent)
                ->where('t1.status', "=", "join")
                ->limit(10)
                ->get()
                ->toArray();

            return $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
