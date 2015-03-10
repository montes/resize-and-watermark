[![Build Status](https://travis-ci.org/montes/resize-and-watermark.svg)](https://travis-ci.org/montes/resize-and-watermark)

#Resize And Watermark

**Unfinished documentation and poorly tested! Use at your own risk!**

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
$resizer = new \Montesjmm\ResizeAndWatermark\ResizeAndWatermark();
$file = \Input::file()['file'];

$picture = $resizer->store($file);
```
This will copy original file to "laravel/private-uploads" and generate sizes (with watermark if configured) to 
"laravel/public/uploads/YYYY/MM/"


