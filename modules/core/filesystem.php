<?php

namespace Core;
class FileSystem
{
    private $basePath;

    public function __construct(string $basePath) 
    {
        $this->basePath = $basePath;
    }

    public function saveFiles(array $files, string $fieldName) : array
    {
        $filenames = [];
        foreach ($files[$fieldName]['tmp_name'] as $key => $tmpName) {
            $name = $files[$fieldName]['name'][$key];
            $extension = pathinfo($name, PATHINFO_EXTENSION);

            $dirToLoad = $this->getDirectoryForPublicFile($extension);
            $filePath = $this->basePath . 'public/' . $dirToLoad;
    
            $filename = $this->getFilename($extension, $filePath);
    
            move_uploaded_file($tmpName, $filePath . $filename);
    
            $filenames[] = $filename;
        }
        return $filenames;
    }

    public function deleteFile($filename) 
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $dirToLoad = $this->getDirectoryForPublicFile($extension);
        $filePath = $this->basePath . 'public/' . $dirToLoad . $filename;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    private function getFilename($extension, $filePath) : string
    { 
        $name = date('YmdHi');
        $postfix = '';
        $number = 0;
        while(file_exists($filePath.$name.$postfix.'.'.$extension)) 
        {
            $postfix = '_'.++$number;
        }
        return $name.$postfix.'.'.$extension;
    }

    private function getDirectoryForPublicFile($extension) : string
    {
        $resultDir = '';

        $imagesExtensions = \Settings\IMAGES_EXTENSIONS;
        $videosExtensions = \Settings\VIDEOS_EXTENSIONS;
        $audiosExtensions = \Settings\AUDIOS_EXTENSIONS;
        $presentsExtensions = \Settings\PRESENTS_EXTENSIONS;
        $tablesExtensions = \Settings\TABLES_EXTENSIONS;

        if (in_array($extension, $imagesExtensions)) {
            $resultDir = \Settings\IMAGES_FILE_PATH;
        } elseif (in_array($extension, $videosExtensions)) {
            $resultDir = \Settings\VIDEOS_FILE_PATH;
        } elseif (in_array($extension, $audiosExtensions)) {
            $resultDir = \Settings\AUDIOS_FILE_PATH;
        } elseif (in_array($extension, $presentsExtensions)) {
            $resultDir = \Settings\PRESENTS_FILE_PATH;
        } elseif (in_array($extension, $tablesExtensions)) {
            $resultDir = \Settings\TABLES_FILE_PATH;
        } else {
            throw new \Exception\Page503Exception('Файлы с таким расширением не могут быть отправлены на сайт');
        }

        return $resultDir;
    }
}


?>