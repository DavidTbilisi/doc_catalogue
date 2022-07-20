<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public static function permList($group_id)
    {

        $permissions = self::find($group_id)->permissions->toArray();

        return array_map(
            function($p) {
                return $p['const_name'];
            },
            $permissions
        );

    }
}
