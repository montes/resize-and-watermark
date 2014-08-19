<?php namespace Montesjmm\ResizeAndWatermark\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class RWPicture extends Model {

	protected $table = 'rwpictures';

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function url($size = 'big')
	{
		$year  = date('Y', strtotime($this->created_at));
		$month = date('m', strtotime($this->created_at));

		$url = '/uploads/' . $year . '/' . $month . '/' . $this->filename . '_' . $size . '.jpg';

		return URL::to($url);
	}

	public function html($size = 'big')
	{
		$url = $this->url($size);

		return '<img class="img-thumbnail" src="' . $url . '">';
	}

}
