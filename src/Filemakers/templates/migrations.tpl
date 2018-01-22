<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class {{$className}} extends Migration
{
    public function up()
    {
        $sql = "{{$create_sql}}";
        $pdo = DB::connection()->getPdo();
        $pdo->exec($sql);
    }

    public function down()
    {
        $sql = "{{$drop_sql}}";
        $pdo = DB::connection()->getPdo();
        $pdo->exec($sql);
    }
}
