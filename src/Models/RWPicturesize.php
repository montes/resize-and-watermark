<?php

namespace Montesjmm\ResizeAndWatermark\Models;

use Illuminate\Database\Eloquent\Model;

class RwPictureSize extends Model
{

    protected $table = 'rw_pictures_sizes';

    protected $fillable = array('name', 'slug', 'width', 'height', 'mode');
}
