<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 29.03.15 - 12:19.
 */
namespace DripsPHP\Uploader;

use DripsPHP\Converter\FilesizeConverter;
use Exception;

/**
 * class Uploader.
 *
 * used for uploading files
 */
class Uploader
{
    protected $files = array();
    protected $tmpdir = 'core/tmp/.upload/';

    protected $imagetypes = array('png', 'jpg', 'gif', 'jpeg');
    protected $imgWidth = 0;
    protected $imgHeight = 0;
    protected $minWidth = 0;
    protected $minHeight = 0;
    protected $filetypes = array();
    protected $filesize = 0;

    /**
     * creates a new uploader-instance.
     *
     * @throws \DripsPHP\Converter\UnitNotFoundException
     */
    public function __construct()
    {
        // set tmp upload directory
        if (is_dir($this->tmpdir)) {
            ini_set('upload_tmp_dir', $this->tmpdir);
        }
        $this->filesize = FilesizeConverter::convert(1, 'mib', 'byte');
    }

    /**
     * restrict uploader only for image-formats.
     */
    public function setImageUpload()
    {
        $this->filetypes = $this->imagetypes;
    }

    /**
     * set allowed filetypes.
     *
     * @param $filetypes
     */
    public function setFiletypes($filetypes)
    {
        if (is_array($filetypes)) {
            $this->filetypes = $filetypes;
        } else {
            $this->filetypes[] = $filetypes;
        }
    }

    /**
     * returns allowed filetypes.
     *
     * @return array
     */
    public function getFiletypes()
    {
        return $this->filetypes;
    }

    /**
     * returns if $filetype is allowed or not.
     *
     * @param $filetype
     *
     * @return bool
     */
    public function isAllowedFiletype($filetype)
    {
        return empty($this->filetypes) || in_array(strtolower($filetype), $this->filetypes);
    }

    /**
     * set max filesize in bytes.
     *
     * @param $byte
     */
    public function setFilesize($byte)
    {
        $this->filesize = $byte;
    }

    /**
     * return max filesize in bytes.
     *
     * @return int|mixed
     */
    public function getFilesize()
    {
        return $this->filesize;
    }

    /**
     * returns if $filesize is smaller than max filesize.
     *
     * @param $filesize
     *
     * @return bool
     */
    public function isAllowedFilesize($filesize)
    {
        return $filesize <= $this->filesize;
    }

    /**
     * sets max image width.
     *
     * @param $width
     */
    public function setMaxImageWidth($width)
    {
        $this->imgWidth = $width;
    }

    /**
     * returns max image width.
     *
     * @return int
     */
    public function getMaxImageWidth()
    {
        return $this->imgWidth;
    }

    /**
     * sets max image height.
     *
     * @param $height
     */
    public function setMaxImageHeight($height)
    {
        $this->imgHeight = $height;
    }

    /**
     * returns max image height.
     *
     * @return int
     */
    public function getMaxImageHeight()
    {
        return $this->imgHeight;
    }

    /**
     * set min image width.
     *
     * @param $width
     */
    public function setMinImageWidth($width)
    {
        $this->minWidth = $width;
    }

    /**
     * returns min image width.
     *
     * @return int
     */
    public function getMinImageWidth()
    {
        return $this->minWidth;
    }

    /**
     * sets min image height.
     *
     * @param $height
     */
    public function setMinImageHeight($height)
    {
        $this->minHeight = $height;
    }

    /**
     * returns min image height.
     *
     * @return int
     */
    public function getMinImageHeight()
    {
        return $this->minHeight;
    }

    /**
     * returns if image is smaller than maxwidth and maxheight.
     *
     * @param $tmpfile
     *
     * @return bool
     */
    public function isAllowedImageSize($tmpfile)
    {
        $img = getimagesize($tmpfile);
        if ($img[2] != 0) {
            if ($this->imgWidth == 0 && $this->imgHeight == 0) {
                return true;
            }

            return $img[0] >= $this->minWidth && $img[1] >= $this->minHeight;
        }

        return false;
    }

    /**
     * returns if image is bigger than minwidth and minheight.
     *
     * @param $tmpfile
     *
     * @return bool
     */
    public function isAllowedMinImageSize($tmpfile)
    {
        $img = getimagesize($tmpfile);
        if ($img[2] != 0) {
            if ($this->minWidth == 0 && $this->minHeight == 0) {
                return true;
            }

            return $img[0] <= $this->imgWidth && $img[1] <= $this->imgHeight;
        }

        return false;
    }

    /**
     * check function for filetype, filesize, and other restrictions.
     *
     * @param $file
     *
     * @return bool
     *
     * @throws UploadFileImageIsToBigException
     * @throws UploadFileIsToBigException
     * @throws UploadFiletypeNotAllowedException
     */
    public function checkFile($file)
    {
        $filetype = $file['filetype'];
        if (!$this->isAllowedFiletype($filetype)) {
            throw new UploadFiletypeNotAllowedException($filetype);
        }
        if (!$this->isAllowedFilesize($file['size'])) {
            throw new UploadFileIsToBigException($this->filesize);
        }
        // is image upload?
        $diff = array_diff($this->filetypes, $this->imagetypes);
        if (empty($diff)) {
            if (!$this->isAllowedImageSize($file['tmpname'])) {
                throw new UploadFileImageIsToBigException($this->imgWidth.'x'.$this->imgHeight);
            }
            if (!$this->isAllowedMinImageSize($file['tmpname'])) {
                throw new UploadFileImageIsToSmallException($this->imgWidth.'x'.$this->imgHeight);
            }
        }

        return true;
    }

    /**
     * returns filetype of a $filename.
     *
     * @param $filename
     *
     * @return string
     */
    public function getFiletype($filename)
    {
        $fileParts = explode('.', $filename);
        if (count($fileParts) > 1) {
            return array_pop($fileParts);
        }

        return '';
    }

    /**
     * returns if upload via post-request was successful.
     *
     * @param $name
     * @param $destination_dir
     * @param bool $override_existing
     *
     * @return bool
     *
     * @throws UploadErrorException
     * @throws UploadFileImageIsToBigException
     * @throws UploadFileIsToBigException
     * @throws UploadFileNameNotFoundException
     * @throws UploadFiletypeNotAllowedException
     * @throws UploadOverrideNotAllowedException
     */
    public function upload($name, $destination_dir, $override_existing = true)
    {
        if (array_key_exists($name, $_FILES)) {
            // multiple uploads?
            if (is_array($_FILES[$name]['tmp_name'])) {
                for ($i = 0; $i < count($_FILES[$name]['tmp_name']); $i++) {
                    $this->files[] = array(
                        'name' => $name,
                        'filename' => $_FILES[$name]['name'][$i],
                        'tmpname' => $_FILES[$name]['tmp_name'][$i],
                        'type' => $_FILES[$name]['type'][$i],
                        'error' => $_FILES[$name]['error'][$i],
                        'size' => $_FILES[$name]['size'][$i],
                        'filetype' => $this->getFiletype($_FILES[$name]['name'][$i]),
                    );
                }
            } else {
                $this->files[] = array(
                    'name' => $name,
                    'filename' => $_FILES[$name]['name'],
                    'tmpname' => $_FILES[$name]['tmp_name'],
                    'type' => $_FILES[$name]['type'],
                    'error' => $_FILES[$name]['error'],
                    'size' => $_FILES[$name]['size'],
                    'filetype' => $this->getFiletype($_FILES[$name]['name']),
                );
            }

            foreach ($this->files as $file) {
                if ($this->checkFile($file)) {
                    $path = $destination_dir.'/'.$file['filename'];
                    if ($file['error'] != UPLOAD_ERR_OK) {
                        throw new UploadErrorException($file['error'].','.$file['filename']);
                    }
                    if (is_file($path) && !$override_existing) {
                        throw new UploadOverrideNotAllowedException($file['filename']);
                    }
                    if (move_uploaded_file($file['tmpname'], $path)) {
                        return true;
                    }
                }
            }
        } else {
            throw new UploadFileNameNotFoundException();
        }

        return false;
    }
}

class UploadFileNameNotFoundException extends Exception
{
}

class UploadErrorException extends Exception
{
}

class UploadOverrideNotAllowedException extends Exception
{
}

class UploadFiletypeNotAllowedException extends Exception
{
}

class UploadFileIsToBigException extends Exception
{
}

class UploadFileImageIsToBigException extends Exception
{
}

class UploadFileImageIsToSmallException extends Exception
{
}
