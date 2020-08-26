<?php

/**
 * Description of FileUtils
 *
 * @author Studio7
 */
class FileUtils
{

    /**
     * Copies a file from a source folder to a destination folder
     * @return bool True if the copy was successful, false in other wise
     */
    public static function copyFile ($sourceFolder, $destinationFolder, $fileName)
    {
        if (!file_exists($destinationFolder))
        {
            mkdir($destinationFolder, 0777, true);
        }

        return copy($sourceFolder, DOCUMENT_ROOT . "/{$destinationFolder}/{$fileName}");
    }


    /**
     * Deletes a file by a url
     * @param String $fileUrl : Url of the file to delete
     * @return bool True if the delete was successful, false in other wise
     */
    public static function deleteFile ($fileUrl)
    {
        return unlink(DOCUMENT_ROOT . "/{$fileUrl}");
    }


    /**
     * Gets the urls from existing files within a folder
     * @param String $folderPath : Path of the parent folder
     */
    public static function getFolderFiles ($folderPath)
    {
        $path = DOCUMENT_ROOT . "/{$folderPath}";

        $folder = opendir($path);

        $filesList = array();

        while ($file = readdir($folder))
        {
            $filePath = $path . "/" . $file;

            if (!is_dir($filePath))
            {
                array_push($filesList, $file);
            }
        }

        echo json_encode($filesList);
    }


    public static function saveImage ($files, $imageName, $folderName)
    {
        foreach ($files as $file) {

            if ($file['error'] == UPLOAD_ERR_OK)
            {
                $sourceNameParts = explode('/', $file['type']);
                $sourceType = $sourceNameParts[1];
                $sourceUrl  = $file['tmp_name'];
                $imageName  = "{$imageName}.{$sourceType}";
                $fileCopied = self::copyFile($sourceUrl, $folderName, $imageName);

                if($fileCopied)
                {
                    return "{$folderName}/{$imageName}";
                }

                return false;
            }

        }
    }


    public function saveImages ($files, $imageName, $folderName)
    {
        $imagesPathsArray = array ();

        foreach ($files['tmp_name'] as $i => $tmpName)
        {
            if ($files['error'][$i] == UPLOAD_ERR_OK)
            {
                $sourceNameParts = explode('/', $files['type'][$i]);
                $sourceType   = $sourceNameParts[1];
                $sourceUrl    = $tmpName;
                $tmpImageName = "{$imageName}_{$i}.{$sourceType}";
                $fileCopied   = self::copyFile($sourceUrl, $folderName, $tmpImageName);

                if($fileCopied)
                {
                    $imagePath = "{$folderName}/{$tmpImageName}";
                    array_push($imagesPathsArray, $imagePath);
                }
            }
        }

        return $imagesPathsArray;
    }
}
