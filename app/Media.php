<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;

class Media extends Image
{
  protected $fillable = ['id',	'model_type',	'model_id',	'collection_name',	'name',	'file_name',	'mime_type',	'disk',	'size',	'manipulations',	'custom_properties',	'responsive_images',	'order_column'];

  public static function uploadImage($images){
    $image = $images;
    // public function uploadImage($image, $type, $request = false, $action = false){
    $data = str_replace('data:image/png;base64,', '', $image);
    $data = str_replace(' ', '+', $data);
    $data = base64_decode($data);
    $file_name = rand() . '.png';
    $path = base_path().'/'.'images/'.$file_name;

    $success = file_put_contents($path, $data);
    return $path;
  }

  public static function formatBytes($size, $precision = 2){
      $base = log($size, 1024);
      $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');

      return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
  }

  public static function ImageUpload($imageInput){
    return self::make($imageInput);
  }

  public static function VideoUpload($videoInput){
    return self::make($videoInput);
  }

  public static function test($imageInput){
    dd(self::ImageUpload($imageInput));
  }

  public static function image64($image) {
    return file_get_contents($image);
 }
}
