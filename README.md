[![Build Status](https://travis-ci.org/montes/resize-and-watermark.svg)](https://travis-ci.org/montes/resize-and-watermark)

#Resize And Watermark

##Laravel 5 Installation

1. Add to your composer require: "montesjmm/resize-and-watermark": "~0.2"
2. Add service provider to your config/app.php: 'Montesjmm\ResizeAndWatermark\ResizeAndWatermarkServiceProvider',
3. composer update
4. php artisan vendor:publish
5. php artisan migrate
6. composer dump-autoload -o
7. php artisan db:seed --class=RwPicturesSizesTableSeeder

## Use

```php
<?php namespace App\Http\Controllers;

use Illuminate\Config\Repository as Config;

class WelcomeController extends Controller {

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function index()
    {
        $resizer = new \Montesjmm\ResizeAndWatermark\ResizeAndWatermark(new Config);
        $file = \Input::file()['file'];

        $picture = $resizer->store($file);

        dd($picture);
    }

}
```

This will copy uploaded picture to "laravel/private-uploads" and generate sizes to "laravel/public/uploads/YYYY/MM/" and
add picture data to "rw_pictures" database table.

"laravel/private-uploads" and "laravel/public/uploads" must be writable.

## Test!

You can test it with [postman](https://chrome.google.com/webstore/detail/postman-rest-client/fdmmgilgnpjigdojojpjoooidkmcomcm):

### 1. add post method to welcome route:<br>
```php 
Route::post('/', 'WelcomeController@index'); 
```

### 2. disable "VerifyCsrfToken", commenting this line at "app/Http/Kernel.php"<br>
```php 
//		'App\Http\Middleware\VerifyCsrfToken', 
```

### 3. test with [postman](https://chrome.google.com/webstore/detail/postman-rest-client/fdmmgilgnpjigdojojpjoooidkmcomcm)
![Postman test](http://montesjmm.com/wp-content/uploads/2015/03/Screen-Shot-2015-03-25-at-23.54.45.png)


