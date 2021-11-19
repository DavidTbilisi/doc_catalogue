<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

        $columns = Db::select($sql);

        $columns = array_filter($columns, function($element) {

            return $element->Field != "id"
                && $element->Field != "created_at"
                && $element->Field != "updated_at"
                && $element->Field != "deleted_at"
                && $element->Field != "parent_id"
                && $element->Field != "io_type_id"
                && $element->Field != "reference"
                ;
        });
        return $columns;
    }
}
