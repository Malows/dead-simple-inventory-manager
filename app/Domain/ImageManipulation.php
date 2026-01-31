<?php

namespace App\Domain;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Laravel\Facades\Image;

class ImageManipulation
{
    /**
     * Resize an image to the given width and height.
     */
    public function processProductImage(UploadedFile $file): string
    {
        $image = Image::read($file->getPathname());

        $smallerSide = min($image->width(), $image->height());
        $size = min($smallerSide, 480);

        return $image
            ->cover($size, $size, 'center')
            ->scale($size, $size)
            // ->crop($smallerSide, $smallerSide, 0, 0, 'ffffff', 'center')
            // ->resize($size, $size)
            ->toWebp(80)
            ->toString();
    }

    /**
     * Generate a filename for a product image.
     *
     * The filename is generated using a unique ID and returned as a WebP file name.
     * The `$options` array can be used to customize the filename prefix and the
     * directory path prepended to the generated name.
     *
     * @param array{
     *     prefix?: string,
     *     path?: string
     * } $options Optional configuration for the generated filename:
     *     - `prefix`: String prefix used when generating the unique ID. Defaults to `"product_"`.
     *     - `path`: Directory path prepended to the filename. Defaults to `"products/"`.
     *
     * @return string The generated product image filename (including path), ending with the `.webp` extension.
     */
    public function getProductImageName($options = []): string
    {
        $prefix = $options['prefix'] ?? 'product_';
        $path = $options['path'] ?? 'products/';

        return $path . uniqid($prefix) . '.webp';
    }
}
