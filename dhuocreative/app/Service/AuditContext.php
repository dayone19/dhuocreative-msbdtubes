<?php

namespace App\Services;

use Closure;
use Illuminate\Support\Facades\DB;

class AuditContext
{
    public static function run(Closure $callback): mixed
    {
        $user = auth()->user();
        $idUser = $user?->id_user;

        $connection = DB::connection();

        return $connection->transaction(function () use (
            $connection,
            $idUser,
            $callback
        ) {
            $connection->select(
                "SELECT set_config('app.user_id', ?, true)",
                [$idUser ? (string) $idUser : '']
            );

            return $callback();
        });
    }
}
