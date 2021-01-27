<?php
namespace ThaoHR;

use Illuminate\Database\Eloquent\Model;

class File extends  Model
{
    const TYPE_CITY = 1;
    const TYPE_TREASURE = 2;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'files';
    
    protected $fillable = ['name', 'source_size','small_size', 'medium_size', 'large_size', 'source_path', 'small_path', 'medium_path', 'large_path', 'foreign_hash', 'type', 'status'];
}