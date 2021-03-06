<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Io extends Model
{
    use HasFactory;

    protected $table = "io";
    protected $fillable = [
        'suffix',
        'io_type_id',
        'identifier',
        'prefix',
        'reference',
        'level',
        'data_id',
        'parent_id',
    ];
//    use SoftDeletes;

    public function type()
    {
        return $this->belongsTo(Io_type::class, 'io_type_id');
    }

    public function children()
    {
        return $this->hasMany(Io::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Io::class, 'parent_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function permissions()
    {
        return $this->hasMany(IoGroupsPermissions::class);
    }

}
