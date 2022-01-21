<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Io_types_translation extends Model
{
    use HasFactory;
    protected $table = "humanreadable_type_fields"; // override table name
    protected $fillable = [
        'io_type_id', 'fields'
    ];

    public function type()
    {
        return $this->belongsTo(Io_type::class, 'io_type_id');
    }
}
