<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Image
 * @package App\Models
 */
class Image extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'images';

    /**
     * @var array
     */
    protected $fillable = [
        'image_url','image_type','image_size','created_by','updated_by'
    ];

    protected $hidden = array();
}
