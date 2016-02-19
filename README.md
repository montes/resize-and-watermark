[![Build Status](https://travis-ci.org/montes/resize-and-watermark.svg)](https://travis-ci.org/montes/resize-and-watermark)

#Resize And Watermark (images)
Easily automate picture **upload**, generating of multiple **sizes** and **watermarking** if needed.

##Laravel 5.2 Installation

1. Add to your composer require: "montesjmm/resize-and-watermark": "~0.3"
2. Add service provider to your config/app.php: Montesjmm\ResizeAndWatermark\ResizeAndWatermarkServiceProvider::class,
3. Add alias to your config/app.php aliases: 'ResizeAndWatermark' => Montesjmm\ResizeAndWatermark\ResizeAndWatermark::class,
4. composer update
5. php artisan vendor:publish
6. php artisan migrate
7. composer dump-autoload -o
8. php artisan db:seed --class=RwPicturesSizesTableSeeder

##Examples

####Download and generate sizes from image url
```php
$resizer = new ResizeAndWatermark;
$picture = $resizer->store('http://example.com/image.jpg');

echo $picture->html('small'); // <img src="http://mysite.com/uploads/2015/03/20150327-picture_small.jpg">

echo $picture->html('big'); // <img src="http://laravel5.app/uploads/2015/03/20150327-picture_big.jpg">
```

####Generate sizes of form uploaded image
```php
$resizer = new ResizeAndWatermark;
$file = Input::file()['file'];
$picture = $resizer->store($file);

echo $picture->html('small'); // <img src="http://mysite.com/uploads/2015/03/20150327-picture_small.jpg">
```

## Example App

####app/Http/routes.php
```php
Route::get('/', 'WelcomeController@index');
Route::post('/', 'WelcomeController@index');
```
####app/Http/controllers/WelcomeController.php
```php
<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;
use Montesjmm\ResizeAndWatermark\ResizeAndWatermark;

class WelcomeController extends Controller {

	public function index()
	{
		if (Request::isMethod('post')) {
			$resizer = new ResizeAndWatermark;

			$file = Input::file()['file'];

			$picture = $resizer->store($file);

			return '<img src="' . $picture->url('small') . '">';
		}

		return view('welcome');
	}
}
```
####resources/views/welcome.blade.php
```html
<html>
	<head>
		<title>Resize And Watermark Test</title>
	</head>
	<body>
		<form method="post" enctype="multipart/form-data">
			<input type="hidden" name="_token" value="{{{ csrf_token() }}}">
			<input type="file" name="file">
			<input type="submit" value="send">
		</form>
	</body>
</html>
```

When you upload a picture this will be the result in disk:
```bash
private-uploads/2015/03/20150326-picture_orig.jpg
public/uploads/2015/03/20150326-picture_bigger.jpg
public/uploads/2015/03/20150326-picture_big.jpg
public/uploads/2015/03/20150326-picture_medbig.jpg
public/uploads/2015/03/20150326-picture_medium.jpg
public/uploads/2015/03/20150326-picture_small.jpg
public/uploads/2015/03/20150326-picture_thumb.jpg
public/uploads/2015/03/20150326-picture_tiny.jpg
```

**"laravel/private-uploads" and "laravel/public/uploads" must be writable.**


## Config
at _config/resize-and-watermark.php_ you can setup the routes for the watermarking files if you want watermarking.

