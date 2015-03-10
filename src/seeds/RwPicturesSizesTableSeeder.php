<?php

use Illuminate\Database\Seeder;
use Montesjmm\ResizeAndWatermark\Models\RwPictureSize;

class RwPicturesSizesTableSeeder extends Seeder
{

    public function run()
    {
        RwPictureSize::truncate();

        RwPictureSize::create([
            'name'   => 'thumb',
            'slug'   => 'thumb',
            'width'  => 60,
            'height' => 60,
            'mode'   => 'outbound',
        ]);

        RwPictureSize::create([
            'name'   => 'tiny',
            'slug'   => 'tiny',
            'width'  => 150,
            'height' => 100,
            'mode'   => 'outbound',
        ]);

        RwPictureSize::create([
            'name'   => 'small',
            'slug'   => 'small',
            'width'  => 450,
            'height' => 300,
            'mode'   => 'outbound',
        ]);

        RwPictureSize::create([
            'name'   => 'medium',
            'slug'   => 'medium',
            'width'  => 500,
            'height' => 500,
        ]);

        RwPictureSize::create([
            'name'   => 'medbig',
            'slug'   => 'medbig',
            'width'  => 800,
            'height' => 600,
        ]);

        RwPictureSize::create([
            'name'   => 'big',
            'slug'   => 'big',
            'width'  => 1024,
            'height' => 768,
        ]);

        RwPictureSize::create([
            'name'   => 'bigger',
            'slug'   => 'bigger',
            'width'  => 1280,
            'height' => 1024,
        ]);

        $this->command->info('rw_pictures_sizes table seeded');
    }
}
