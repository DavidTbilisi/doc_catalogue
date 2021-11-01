<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Authroutes extends Model
{
    use HasFactory;


    public function users()
    {
        return $this->belongsToMany(User::class, 'user_authroutes');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_authroutes');

    }
}
