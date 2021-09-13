<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

Route::get('dropall', function () {
    Artisan::call('db:wipe', ['--force' => true]);
    return DB::select('SHOW TABLES');
});

Route::get('loaddump', function () {
    return DB::unprepared(file_get_contents('/var/www/vhosts/web66.syntos-medien.de/httpdocs/order/database/schema/mysql-schema.dump'));
});

Route::get('truncate', function () {
    Schema::disableForeignKeyConstraints();
    foreach (DB::select('SHOW TABLES') as $table) {
        DB::table($table->Tables_in_order_66)->truncate();
    }
    Schema::enableForeignKeyConstraints();
    return DB::select('SHOW TABLES');
});

Route::get('migrate', function () {
    Artisan::call('migrate', ['--force' => true]);
    dd(Artisan::output());
});

Route::get('migrate.fresh', function () {
    set_time_limit(0);
    try {
        Artisan::call('migrate:fresh', ['--force' => true]);
    } catch (Exception $ex) {
        dd(Artisan::output());
    }
});

Route::get('migrations', function () {
    return DB::table('migrations')->select('*')->get();
});

Route::get('tables', function () {
    return DB::select('SHOW TABLES');
});

Route::get('migrate.status', function () {
    Artisan::call('migrate:status', []);
    dd(Artisan::output());
});

Route::get('seed', function () {
    Artisan::call('db:seed', ['--force' => true]);
    dd(Artisan::output());
});

Route::get('config', function () {
    Artisan::call('config:clear');
    dd(Artisan::output());
});
