<?php

namespace Lib;

class Upload
{

  function orderImageArray($files)
  {
    $output = [];
    foreach ($files as $attrName => $valuesArray) {
      foreach ($valuesArray as $key => $value) {
        $output[$key][$attrName] = $value;
      }
    }
    return $output;
  }

  function uploadImage(
    $file = array(),
    $dir = null,
    $name = null,
    $thumb = false,
    $thumb_prefix = "_thumb",
    $thumb_size = 250
  ) {

    if (!$file['tmp_name'] || !$file['name'] || $file['error'] || !$dir) {
      return false;
    }

    if (!is_dir($dir)) return false;

    $return = [];
    $finfo = pathinfo($file['name']);
    $name = isset($name) && !empty($name) ? $name : md5(time());
    $ext = $finfo['extension'];
    $final = "{$name}.{$ext}";

    $return['file'] = $final;
    $return['file_thumb'] = null;
    $return['dir'] = $dir;
    $return['full_path'] = $dir . $final;

    /**
     * Upload file
     */
    $muf = move_uploaded_file($file['tmp_name'], $dir . $final);

    if (!$muf) return false;

    /**
     * If thumb, ok
     */
    if ($muf && $thumb) {
      $return['file_thumb'] = ($name . $thumb_prefix . "." . $ext);
      $return['file_thumb_size'] = $thumb_size;
      $thumb = $this->createThumb($dir . $final, $thumb_size, $thumb_prefix);

      if (!$thumb) return false;
    }

    /**
     * All ok
     */
    return $return;
  }

  function uploadImageMultiple(
    $file = array(),
    $dir = null,
    $name = null,
    $thumb = false,
    $thumb_prefix = "_thumb",
    $thumb_size = 250
  ) {
    /**
     * Set return
     */
    $return = [];

    if (!is_dir($dir)) {
      return false;
    }

    if (!isset($file['tmp_name'][0]) || $file['name'][0] == '') {
      return false;
    }

    /**
     * Get file count
     */
    $fileCount = count($file['tmp_name']);

    for ($i = 0; $i < $fileCount; $i++) {
      if ($file['tmp_name'][$i] && $file['name'][$i] != '' && $file['error'][$i] == 0) {
        $finfo = pathinfo($file['name'][$i]);
        $name = isset($name) && !empty($name) ? $name . $i : md5(time()) . $i;
        $ext = $finfo['extension'];
        $final = "{$name}.{$ext}";

        $return[$i]['file'] = $final;
        $return[$i]['file_thumb'] = null;
        $return[$i]['dir'] = $dir;
        $return[$i]['full_path'] = $dir . $final;

        $muf = move_uploaded_file($file['tmp_name'][$i], $dir . $final);
        if (!$muf) return false;

        if ($muf && $thumb) {
          $return[$i]['file_thumb'] = ($name . $thumb_prefix . "." . $ext);
          $return[$i]['file_thumb_size'] = $thumb_size;
          $thumb = $this->createThumb($dir . $final, $thumb_size, $thumb_prefix);

          if (!$thumb) return false;
        } # if upload and has thumb
      } # check file if errors
    } # for

    return $return;
  }

  function createThumb($file, $thumbWidth = 260, $thumbPrefix = '_thumb')
  {
    if (!file_exists($file)) {
      return false;
    }

    $fileInfo = pathinfo($file);
    $info = getimagesize($file);
    $mime = $info['mime'];

    switch ($mime) {
      case 'image/jpeg':
        $image_create_func = 'imagecreatefromjpeg';
        $image_save_func = 'imagejpeg';
        $new_image_ext = 'jpg';
        break;

      case 'image/png':
        $image_create_func = 'imagecreatefrompng';
        $image_save_func = 'imagepng';
        $new_image_ext = 'png';
        break;

      case 'image/gif':
        $image_create_func = 'imagecreatefromgif';
        $image_save_func = 'imagegif';
        $new_image_ext = 'gif';
        break;
      default:
        throw new \Exception('Unknown image type.');
    }

    $img = $image_create_func($file);
    list($width, $height) = getimagesize($file);

    $thumbHeight = ($height / $width) * $thumbWidth;
    $tmp = imagecreatetruecolor($thumbWidth, $thumbHeight);

    // If png, prevent black background
    if ($new_image_ext == 'png') {
      imagealphablending($tmp, FALSE);
      imagesavealpha($tmp, TRUE);
    }

    /**
     * Copy image
     */
    imagecopyresampled($tmp, $img, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, $height);

    $dir = $fileInfo['dirname'];
    $fileName = $fileInfo['filename'] . $thumbPrefix . '.' . $new_image_ext;
    $fileNameFinal  = $dir . DIRECTORY_SEPARATOR . $fileName;

    /**
     * If error
     */
    if (!$image_save_func($tmp, $fileNameFinal)) {
      return array(
        'status' => false,
        'final_path' => null,
        'final_name' => null,
      );
    }

    /**
     * If all ok
     */
    return array(
      'status' => true,
      'final_path' => $dir,
      'final_name' => $fileName,
    );
  }
}
