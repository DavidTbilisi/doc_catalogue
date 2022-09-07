<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $fillable = [
        "filename",
        "filepath",
        "mimetype",
        "io_id",
    ];
    /**
     * @var mixed
     */
    private $mimetype;

    public function io()
    {
        return $this->belongsTo(Io::class);
    }


    public function tileFolder()
    {
        return pathinfo($this->filepath, PATHINFO_FILENAME);
    }
}
