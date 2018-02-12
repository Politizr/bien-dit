<?php
namespace Politizr\FrontBundle\Lib;

/*
 * File: SimpleImage.php
 * Author: Simon Jarvis
 * Copyright: 2006 Simon Jarvis
 * Date: 08/11/06
 * Link: http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php
 * 
 * This program is free software; you can redistribute it and/or 
 * modify it under the terms of the GNU General Public License 
 * as published by the Free Software Foundation; either version 2 
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
 * GNU General Public License for more details: 
 * http://www.gnu.org/licenses/gpl.html
 *
 */
class SimpleImage
{
    private $image;
    private $imageType;

    private $filename;

    public function load($filename)
    {
        $this->filename = $filename;
        $image_info = getimagesize($filename);
        $this->imageType = $image_info[2];
        if ($this->imageType == IMAGETYPE_JPEG) {
            $this->image = imagecreatefromjpeg($filename);
        } elseif ($this->imageType == IMAGETYPE_GIF) {
            $this->image = imagecreatefromgif($filename);
        } elseif ($this->imageType == IMAGETYPE_PNG) {
            $this->image = imagecreatefrompng($filename);
        }
    }

    /**
     * get image raw / binary
     *
     * @return string
     */
    public function raw()
    {
        $rawImg = null;
        
        if ($this->imageType == IMAGETYPE_JPEG) {
            // start buffering
            ob_start();
            imagejpeg($this->image);
            $rawImg =  ob_get_contents();
            ob_end_clean();
        }

        return $rawImg;
    }

    public function save($filename, $imageType = IMAGETYPE_JPEG, $compression = 90, $permissions = null)
    {
        $this->filename = $filename;
        if ($imageType == IMAGETYPE_JPEG) {
            imagejpeg($this->image, $filename, $compression);
        } elseif ($imageType == IMAGETYPE_GIF) {
            imagegif($this->image, $filename);
        } elseif ($imageType == IMAGETYPE_PNG) {
            imagepng($this->image, $filename);
        }
        if ($permissions !== null) {
            chmod($filename, $permissions);
        }
    }
    
    public function saveWithDefaultImagetype($filename, $compression = 90, $permissions = null)
    {
        $this->filename = $filename;
        if ($this->imageType == IMAGETYPE_JPEG) {
            imagejpeg($this->image, $filename, $compression);
        } elseif ($this->imageType == IMAGETYPE_GIF) {
            imagegif($this->image, $filename);
        } elseif ($this->imageType == IMAGETYPE_PNG) {
            imagepng($this->image, $filename);
        }
        if ($permissions !== null) {
            chmod($filename, $permissions);
        }
    }
    
    public function output($imageType = IMAGETYPE_JPEG)
    {
        if ($imageType == IMAGETYPE_JPEG) {
            imagejpeg($this->image);
        } elseif ($imageType == IMAGETYPE_GIF) {
            imagegif($this->image);
        } elseif ($imageType == IMAGETYPE_PNG) {
            imagepng($this->image);
        }
    }

    public function getWidth()
    {
        return imagesx($this->image);
    }

    public function getHeight()
    {
        return imagesy($this->image);
    }

    public function getSize()
    {
        return filesize($this->filename);
    }

    public function getBasename()
    {
        $pathInfo = pathinfo($this->filename);
        if ($pathInfo['basename']) {
            return $pathInfo['basename'];
        }
        return null;
    }

    public function getPath()
    {
        $pathInfo = pathinfo($this->filename);
        if ($pathInfo['dirname']) {
            return $pathInfo['dirname'];
        }
        return null;
    }

    public function getExtension()
    {
        $pathInfo = pathinfo($this->filename);
        if ($pathInfo['extension']) {
            return $pathInfo['extension'];
        }
        return null;
    }

    public function resizeToHeight($height, $upscale = false)
    {
        if ($height > $this->getHeight() && $upscale === false) {
            return array('w' => $this->getWidth(), 'h' => $this->getHeight());
        }
        $ratio = $height / $this->getHeight();
        $width = round($this->getWidth() * $ratio);
        $this->resize($width, $height);
        return array('w' => $width, 'h' => $height);
    }

    public function resizeToWidth($width, $upscale = false)
    {
        //no resize
        if ($width > $this->getWidth() && $upscale === false) {
            return array('w' => $this->getWidth(), 'h' => $this->getHeight());
        }
        $ratio = $width / $this->getWidth();
        $height = round($this->getHeight() * $ratio);
        $this->resize($width, $height);
        return array('w' => $width, 'h' => $height);
    }

    public function resizeToFit($maxwidth, $maxheight)
    {
        if ($this->getWidth() > $this->getHeight()) {
            $this->resizeToWidth($maxwidth);
        } else {
            $this->resizeToHeight($maxheight);
        }
    }

    public function scale($scale)
    {
        $width = $this->getWidth() * $scale / 100;
        $height = $this->getheight() * $scale / 100;
        $this->resize($width, $height);
    }

    public function crop($x, $y, $w, $h)
    {
        $new_image = imagecreatetruecolor($w, $h);
        //echo $this->getHeight();
        imagecopyresampled($new_image, $this->image, 0, 0, $x, $y, $w, $h, $w, $h);
        $this->image = $new_image;
    }

    public function resizeToWidthWaterMark($width)
    {
        $list_watermark = array('w1.png', 'w2.png', 'w3.png');
        $index = rand(1, count($list_watermark));
        $watermark = App::$config['template'] . "static/images/watermark/" . $list_watermark[$index - 1];
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resizeAndAddWaterMark($this->image, $watermark, $width, $height);
    }

    public function resizeAndAddWaterMark($sourcefile, $insertfile, $new_width, $new_height, $transition = 100)
    {
        $new_image_resized = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($new_image_resized, $sourcefile, 0, 0, 0, 0, $new_width, $new_height, $this->getWidth(), $this->getHeight());

        $water_mark_file = imagecreatefrompng($insertfile);
        $water_mark_file_width = imagesx($water_mark_file);
        $water_mark_file_height = imagesy($water_mark_file);

        $final_image = imagecreatetruecolor($new_width, $new_height + $water_mark_file_height);
        $final_image_width = imagesx($final_image);
        $final_image_height = imagesy($final_image);
        //echo $new_width."<br/>".$new_height."<br/>".$final_image_height."<br/>".$final_image_width;die;
        imagecopy($final_image, $new_image_resized, 0, 0, 0, 0, $new_width, $new_height);
        imagecopy($final_image, $water_mark_file, 0, $final_image_height - $water_mark_file_height, 0, 0, $water_mark_file_width, $water_mark_file_height);
        $this->image = $final_image;
    }

    /* public function resize($width, $height) {
      $new_image = imagecreatetruecolor($width, $height);
      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;
      } */

    public function resize($width, $height, $forcesize = 'n')
    {

        /* optional. if file is smaller, do not resize. */
        if ($forcesize == 'n') {
            if ($width > $this->getWidth() && $height > $this->getHeight()) {
                $width = $this->getWidth();
                $height = $this->getHeight();
            }
        }

        $new_image = imagecreatetruecolor($width, $height);
        if ($this->imageType == IMAGETYPE_GIF || $this->imageType == IMAGETYPE_PNG) {
            $current_transparent = imagecolortransparent($this->image);
            if ($current_transparent != -1) {
                $transparent_color = imagecolorsforindex($this->image, $current_transparent);
                $current_transparent = imagecolorallocate($new_image, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
                imagefill($new_image, 0, 0, $current_transparent);
                imagecolortransparent($new_image, $current_transparent);
            } elseif ($this->imageType == IMAGETYPE_PNG) {
                imagealphablending($new_image, false);
                $color = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
                imagefill($new_image, 0, 0, $color);
                imagesavealpha($new_image, true);
            }
        }
        imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $new_image;
    }

    public function resizeToThumb($thumbw, $thumbh, $align = "center")
    {
        $w = $this->getWidth();
        $h = $this->getHeight();
        $nw = $nh = 0;
        $thumb_ratio = $thumbw / $thumbh;
        $pix_radio = $w / $h;
        if ($thumb_ratio <= $pix_radio) {
            //echo 'hoz';
            $this->resizeToThumbHorizontal($thumbw, $thumbh, $align);
        } else {
            $this->resizeToThumbVertical($thumbw, $thumbh, $align);
        }
        return array('w' => $thumbw, 'h' => $thumbh);
    }

    public function resizeToThumbVertical($thumbw, $thumbh, $align = "center")
    {
        $nw = $this->getWidth();
        $nh = intval(($thumbh * $nw) / $thumbw);
        if ($align == "center") {
            $src_y = intval(($this->getHeight() - $nh) / 2);
        } else {
            $src_y = 0; // align top
        }
        $new_image = imagecreatetruecolor($thumbw, $thumbh);
        /* Check if this image is PNG or GIF, then set if Transparent */
        if (($this->imageType == IMAGETYPE_GIF) || ($this->imageType == IMAGETYPE_PNG)) {
            imagealphablending($new_image, false);
            imagesavealpha($new_image, true);
            $transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
            imagefilledrectangle($new_image, 0, 0, $thumbw, $thumbh, $transparent);
        }
        imagecopyresampled($new_image, $this->image, 0, 0, 0, $src_y, $thumbw, $thumbh, $nw, $nh);
        $this->image = $new_image;
    }

    public function resizeToThumbHorizontal($thumbw, $thumbh, $align = "center")
    {
        $nh = $this->getHeight();
        $nw = floor(($thumbw * $nh) / $thumbh);
        if ($align == "center") {
            $src_x = floor(($this->getWidth() - $nw) / 2);
        } else {
            $src_x = 0;
        }
        $new_image = imagecreatetruecolor($thumbw, $thumbh);
        /* Check if this image is PNG or GIF, then set if Transparent */
        if (($this->imageType == IMAGETYPE_GIF) || ($this->imageType == IMAGETYPE_PNG)) {
            imagealphablending($new_image, false);
            imagesavealpha($new_image, true);
            $transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
            imagefilledrectangle($new_image, 0, 0, $thumbw, $thumbh, $transparent);
        }
        imagecopyresampled($new_image, $this->image, 0, 0, $src_x, 0, $thumbw, $thumbh, $nw, $nh);
        $this->image = $new_image;
    }

    public function addWaterMark($fileWaterMark)
    {
        //$new_image_resized = imagecreatetruecolor($new_width, $new_height);
        //imagecopyresampled($new_image_resized,$sourcefile , 0, 0, 0, 0, $new_width, $new_height, $this->getWidth(), $this->getHeight());

        $water_mark_file = imagecreatefrompng($fileWaterMark);
        $water_mark_file_width = imagesx($water_mark_file);
        $water_mark_file_height = imagesy($water_mark_file);

        $final_image = imagecreatetruecolor($this->getWidth(), $this->getHeight() + $water_mark_file_height);
        $final_image_height = imagesy($final_image);

        imagecopy($final_image, $this->image, 0, 0, 0, 0, $this->getWidth(), $this->getHeight());
        imagecopy($final_image, $water_mark_file, 0, $final_image_height - $water_mark_file_height, 0, 0, $water_mark_file_width, $water_mark_file_height);
        $this->image = $final_image;
    }
}
