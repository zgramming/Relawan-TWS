<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;

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

    public function getUserJoinedEvent(int $idUser, int $year, int $month)
    {

        try {
            $query = DB::table(TABLE_EVENT_JOINED . " as t1")
                ->select([
                    't2.id',
                    't2.title',
                    't2.start_date',
                    't2.end_date',
                    't2.type',
                    't2.quota',
                    't2.image',
                    "t3.name as nama_organisasi",
                    "t4.name as nama_category",
                ])
                ->join(TABLE_EVENT . " AS t2", "t1.id_event", "=", "t2.id")
                ->join(TABLE_USERS . " AS t3", "t1.id_user", "=", "t3.id")
                ->join(TABLE_CATEGORY . " AS t4", "t2.id_category", "=", "t4.id")
                ->where('t1.id_user', '=', $idUser)
                ->where('t1.status', "=", "join")
                ->whereRaw("YEAR(t2.start_date) = ?", [$year])
                ->whereRaw("MONTH(t2.start_date) = ?", [$month]);


            return $query->get()->toArray();
        } catch (\Exception $e) {
            throw $e;
        }
    }


    public function debugSQL($query): string
    {
        $sql = $query->toSql();
        $binding = $query->getBindings();
        $sqlWithBinding = Str::replaceArray('?', $binding, $sql);
        return $sqlWithBinding;
    }
}
