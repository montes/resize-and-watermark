<?php

namespace Montesjmm\ResizeAndWatermark\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class RwPicture extends Model
{

	protected $table = 'rw_pictures';

	protected $fillable = array('user_id', 'filename', 'token');

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function url($size = 'big')
	{
		$year  = date('Y', strtotime($this->created_at));
		$month = date('m', strtotime($this->created_at));

		$url = '/uploads/' . $year . '/' . $month . '/' . rawurlencode($this->filename) . '_' . $size . '.jpg';

		return URL::to($url);
	}

	public function html($size = 'big', $alt = false)
	{
		$url = $this->url($size);

		if ($alt) {
			return '<img src="' . $url . '" alt="' . $alt . '">';
		} else {
			return '<img src="' . $url . '">';
		}
	}
}
