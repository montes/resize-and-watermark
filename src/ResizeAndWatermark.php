<?php

namespace Montesjmm\ResizeAndWatermark;

use Illuminate\Support\Facades\User;

use Montesjmm\ResizeAndWatermark\Models\RwPictureSize;
use Montesjmm\ResizeAndWatermark\Models\RwPicture;
use Montesjmm\ResizeAndWatermark\File as RwFile;

use Imagine\Gd\Imagine;
use Imagine\Image\ImageInterface;
use Imagine\Image\Box;
use Imagine\Image\Point;

class ResizeAndWatermark
{
    protected $file;

    protected $transparentWatermarkFile;

    protected $noTransparentWatermarkFile;

    public function __construct()
    {
        $watermarks                       = $this->setupWatermarksFiles();
        $this->transparentWatermarkFile   = $watermarks['transparentWatermarkFile'];
        $this->noTransparentWatermarkFile = $watermarks['noTransparentWatermarkFile'];
    }

    public function setupWatermarksFiles()
    {
        if (config('resize-and-watermark::transparentWatermarkFile')) {
            $transparentWatermarkFile =
                base_path() . config('resize-and-watermark::transparentWatermarkFile');
        } else {
            $transparentWatermarkFile = false;
        }

        if (config('resize-and-watermark::noTransparentWatermarkFile')) {
            $noTransparentWatermarkFile =
                base_path() . config('resize-and-watermark::noTransparentWatermarkFile');
        } else {
            $noTransparentWatermarkFile = false;
        }

        return ['transparentWatermarkFile' => $transparentWatermarkFile,
            'noTransparentWatermarkFile' => $noTransparentWatermarkFile];
    }

    public function store($inputFile, $user = null, $slug = 'picture')
    {
        $this->file = new RwFile($inputFile, $slug);

        if ($this->generateSizes(true)) {
            $picture           = new RwPicture;
            $picture->filename = $this->file->getFilenameWithoutExtension();

            if ($user) {
                $picture->user_id = $user->id;
            }

            $picture->save();

            return $picture;
        }
    }

    protected function generateSizes($applyWatermark = false)
    {
        $filenameWithoutExtension = $this->file->getFilenameWithoutExtension();
        $privateFullFilename      = $this->file->getPrivateFullFilename();

        $imagine = new Imagine;

        $picturesSizes = RwPictureSize::all();

        foreach ($picturesSizes as $pictureSize) {
            switch ($pictureSize->mode) {
                case 'inset':
                    $mode = ImageInterface::THUMBNAIL_INSET;
                    break;
                case 'outbound':
                    $mode = ImageInterface::THUMBNAIL_OUTBOUND;
                    break;
                default:
                    $mode = ImageInterface::THUMBNAIL_INSET;
            }

            $image     = $imagine->open($privateFullFilename);
            $imageSize = new Box($pictureSize->width, $pictureSize->height);
            $image     = $image->thumbnail($imageSize, $mode);
            $imageSize = $image->getSize();

            if ($applyWatermark) {
                $this->applyWatermarks($image, $imageSize);
            }

            $image->save($this->file->getPaths()['public'] .
                $filenameWithoutExtension . '_' . $pictureSize->slug . '.jpg');
        }

        return empty($image) ? false : $image;
    }

    protected function applyWatermarks($image, $imageSize)
    {
        if ($imageSize->getWidth() > 200 &&
            $imageSize->getHeight() > 200) {
            if ($this->transparentWatermarkFile) {
                $this->applyTransparentWatermark($image, $imageSize);
            }

            if ($this->noTransparentWatermarkFile) {
                $this->applyNoTransparentWatermark($image, $imageSize);
            }
        }
    }

    protected function applyTransparentWatermark($image, $imageSize)
    {
        $imagine = new Imagine;

        $watermark     = $imagine->open($this->transparentWatermarkFile);
        $watermarkSize = $watermark->getSize();

        $desiredWatermarkWidth = $imageSize->getWidth() / 2;
        if ($watermarkSize->getWidth() > $desiredWatermarkWidth) {
            $desiredWatermarkHeight = 57 / 246 * $desiredWatermarkWidth;
            $watermark = $watermark->thumbnail(new Box($desiredWatermarkWidth, $desiredWatermarkHeight));
            $watermarkSize = $watermark->getSize();
        }

        $x = round(($imageSize->getWidth()  - $watermarkSize->getWidth()) / 2);
        $y = round(($imageSize->getHeight() - $watermarkSize->getHeight()) / 2);

        $center = new Point($x, $y);

        $image->paste($watermark, $center);
    }

    protected function applyNoTransparentWatermark($image, $imageSize)
    {
        $imagine = new Imagine;

        $watermark     = $imagine->open($this->noTransparentWatermarkFile);
        $watermarkSize = $watermark->getSize();

        $desiredWatermarkWidth = $imageSize->getWidth() / 5;
        if ($watermarkSize->getWidth() > $desiredWatermarkWidth) {
            $desiredWatermarkHeight = 57 / 246 * $desiredWatermarkWidth;
            $watermark = $watermark->thumbnail(new Box($desiredWatermarkWidth, $desiredWatermarkHeight));
            $watermarkSize = $watermark->getSize();
        }

        $x = $imageSize->getWidth() - $watermarkSize->getWidth();
        $y = $imageSize->getHeight() - $watermarkSize->getHeight();

        $center = new Point($x, $y);

        $image->paste($watermark, $center);
    }
}
