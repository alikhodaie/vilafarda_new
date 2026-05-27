<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\HeicToRasterConverter;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Laravel\Facades\Image;

class CompressImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $path;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $path = Storage::path($this->path);

        if (! File::exists($path) || File::size($path) === 0) {
            return;
        }

        $readPath = $path;
        $cleanup = [];

        if (HeicToRasterConverter::looksLikeHeicPath($path)) {
            try {
                $converted = HeicToRasterConverter::convertToTempJpeg($path);
                $readPath = $converted['path'];
                $cleanup = $converted['cleanup'];
            } catch (\Throwable $e) {
                return;
            }
        }

        try {
            $image = Image::read($readPath);
        } catch (\Throwable $e) {
            return;
        } finally {
            foreach ($cleanup as $temp) {
                @unlink($temp);
            }
        }

        if ($image->width() > 500 || $image->height() > 500) {
            $scale = $this->getScale($image);

            $image->resize($scale[0], $scale[1])
                ->toJpeg(80)
                ->save($path);
        }
    }

    private function getScale(ImageInterface $image, int $times = 1): array
    {
        $width = $image->width() / $times;
        $height = $image->height() / $times;

        if ($width < 1000 && $height < 1000)
        {
            return [$width, $height];
        }

        return $this->getScale($image, ++$times);
    }
}
