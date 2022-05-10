<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class IoGroupsPermissions extends Model
{
    use HasFactory;
    protected $table = "io_groups_permissions";


    public static function  getPermisssions($io_id)
    {

        return self::where("io_id", $io_id)->get();
    }

    public function io(){
        return $this->hasMany(Io::class);
    }
}
