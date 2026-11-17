<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SetDatabaseConnection
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        $connections = [
            'admin' => 'pgsql_admin',
            'operator' => 'pgsql_operator',
            'tentor' => 'pgsql_tentor',
            'siswa' => 'pgsql_siswa',
        ];

        if (!$user || !isset($connections[$user->role])) {
            abort(403, 'Role pengguna tidak valid.');
        }

        $connection = $connections[$user->role];

        DB::purge($connection);
        DB::setDefaultConnection($connection);

        try {
            return DB::connection($connection)->transaction(
                function () use ($request, $next, $user, $connection) {

                    DB::connection($connection)->select(
                        "SELECT set_config('app.user_id', ?, true)",
                        [(string) $user->id_user]
                    );

                    return $next($request);
                }
            );
        } finally {
            DB::setDefaultConnection(config('database.default'));
        }
    }
}
