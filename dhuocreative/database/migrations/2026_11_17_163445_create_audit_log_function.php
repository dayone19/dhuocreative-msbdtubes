
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            CREATE OR REPLACE FUNCTION public.audit_log_aktivitas()
            RETURNS TRIGGER
            LANGUAGE plpgsql
            SECURITY DEFINER
            SET search_path = pg_catalog, public
            AS \$\$
            DECLARE
                v_id_user INTEGER;
                v_data_lama JSONB;
                v_data_baru JSONB;
            BEGIN
                v_id_user := NULLIF(
                    current_setting('app.user_id', true),
                    ''
                )::INTEGER;

                IF TG_OP = 'INSERT' THEN
                    v_data_baru := to_jsonb(NEW);

                ELSIF TG_OP = 'UPDATE' THEN
                    v_data_lama := to_jsonb(OLD);
                    v_data_baru := to_jsonb(NEW);

                ELSIF TG_OP = 'DELETE' THEN
                    v_data_lama := to_jsonb(OLD);
                END IF;

                IF TG_TABLE_NAME = 'users' THEN
                    v_data_lama := v_data_lama - ARRAY[
                        'password',
                        'remember_token',
                        'password_reset_token',
                        'otp',
                        'otp_secret',
                        'two_factor_secret',
                        'two_factor_recovery_codes'
                    ];

                    v_data_baru := v_data_baru - ARRAY[
                        'password',
                        'remember_token',
                        'password_reset_token',
                        'otp',
                        'otp_secret',
                        'two_factor_secret',
                        'two_factor_recovery_codes'
                    ];
                END IF;

                INSERT INTO public.log_aktivitas (
                    id_user,
                    db_user,
                    aktivitas,
                    nama_tabel,
                    data_lama,
                    data_baru
                )
                VALUES (
                    v_id_user,
                    session_user,
                    TG_OP,
                    TG_TABLE_NAME,
                    v_data_lama,
                    v_data_baru
                );

                IF TG_OP = 'DELETE' THEN
                    RETURN OLD;
                END IF;

                RETURN NEW;
            END;
            \$\$;
        ");
    }

    public function down(): void
    {
        DB::unprepared("
            DROP FUNCTION IF EXISTS public.audit_log_aktivitas();
        ");
    }
};
