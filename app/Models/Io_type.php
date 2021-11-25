<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use mysql_xdevapi\Exception;

class Io_type extends Model
{
    use HasFactory;

    public function ios()
    {
        $this->belongsTo(IO::class);
    }


    public static function getColumns($table)
    {
        $sql = DB::raw('SHOW COLUMNS FROM '.$table);

        try{
            $columns = Db::select($sql);
            $columns = array_filter($columns, function($element) {
                return $element->Field != "id"
                    && !Str::endsWith($element->Field, "_at")
                    && !Str::endsWith($element->Field, "_id")
                    && $element->Field != "reference"
                    ;
            });

            return \response( )->json([
                "message"=>"found",
                "data" => $columns
            ]);

        } catch (\Exception $exception) {

            return \response( )->json([
                "message"=>"nothing found",
                "data" => null
            ], 404);
        }

    }
}
