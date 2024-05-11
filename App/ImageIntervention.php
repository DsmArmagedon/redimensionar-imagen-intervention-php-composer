<?php

namespace App;

require '../vendor/autoload.php';
ini_set('display_errors', true);
error_reporting(E_ALL);

use Intervention\Image\ImageManagerStatic as Image;
use SplFileInfo;

class ImageIntervention {
    const SIZE = [32, 64, 128, 256, 512];

    public function __construct() {
    }

    function createImagesForTest(string $folder) {
        $id = 0;
        $directory = dirname(__DIR__) . '/resources' . $folder;
        $directoryImports = dirname(__DIR__) . '/resources/imports' . $folder;
        if (is_dir($directory)) {
            $gestor = opendir($directory);
            if (!is_dir($directoryImports)) {
                mkdir($directoryImports, 0775, true);
            }
            while (($file = readdir($gestor)) !== false) {
                if ($file != "." && $file != "..") {
                    $directoryFile = $directory . "/" . $file;
                    if ($this->isImage($directoryFile)) {
                        $id++;
                        if (!is_dir($directoryImports . '/' . $id)) {
                            mkdir($directoryImports . '/' . $id, 0775, true);
                        }
                        $info = new SplFileInfo(($directoryFile));
                        if ($info->getExtension() != "jpg") {
                            $newImage = Image::make($directoryFile)->encode('jpg', 75);
                            $newImage->save($directoryImports . '/' . $id . '/' . $id . '.jpg');
                        } else {
                            copy($directoryFile, $directoryImports . '/' . $id . '/' . $id . '.jpg');
                        }
                        $this->saveImage($directoryImports, $id);
                        echo("Imagen Creada Correctamente:" . $id . "\n");
                    }
                }
            }
        }
    }

    function createImagesForTestWebp(string $folder) {
        $id = 0;
        $directory = dirname(__DIR__) . '/resources' . $folder;
        $directoryImports = dirname(__DIR__) . '/resources/imports' . $folder;
        if (is_dir($directory)) {
            $gestor = opendir($directory);
            if (!is_dir($directoryImports)) {
                mkdir($directoryImports, 0775, true);
            }
            while (($file = readdir($gestor)) !== false) {
                if ($file != "." && $file != "..") {
                    $directoryFile = $directory . "/" . $file;
                    if ($this->isImage($directoryFile)) {
                        $id++;
                        if (!is_dir($directoryImports . '/' . $id)) {
                            mkdir($directoryImports . '/' . $id, 0775, true);
                        }
                        $info = new SplFileInfo(($directoryFile));
                        $newImage = Image::make($directoryFile)->encode('webp');
                        $newImage->save($directoryImports . '/' . $id . '/' . $id . '.webp');
                        $this->saveImage($directoryImports, $id);
                        echo("Imagen Creada Correctamente:" . $id . "\n");
                    }
                }
            }
        }
    }
    private function saveImage($path, $id): void {
        foreach (self::SIZE as $size) {
            $this->thumb($path, $id, $size);
        }
    }

    private function thumb($path, $id, $size): void {
        $imgThumb = Image::make($path . '/' . $id . '/' . $id . '.webp');
        $imgThumb->resize(null, $size, function ($constraint) {
            $constraint->aspectRatio();
        });
        $imgThumb->save($path . '/' . $id . '/' . $size . '_' . $id . '.webp');
    }

    private function isImage($directoryFile) {
        $imageSize = getimagesize($directoryFile);
        $imageType = $imageSize[2];
        return (bool)(in_array($imageType, array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP)));
    }
}

$execute = new ImageIntervention();
// $execute->createImagesForTest('/games');
// $execute->createImagesForTest('/tarjetas');
// $execute->createImagesForTest('/cards-production');
$execute->createImagesForTestWebp('/advertisements');