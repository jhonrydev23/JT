<?php

//returns list of excel files
function getFiles($path)
{
    //check if its a proper/real/accesable path
    if (!is_dir($path)) {
        return [];
    }

    /**
     * array_values resets the array so no empty index
     * array_diff remove the specified values in the array
     * scandir gets the contents of the folder eg: files or folder names
     */
    $items = array_values(array_diff(scandir($path), ['.', '..']));
    $excelFiles = [];

    //loop through the items
    foreach ($items as $item) {
        //get the full path
        $filepath = $path . DIRECTORY_SEPARATOR . $item;

        //if a valid file then proceed
        if (is_file($filepath)) {
            //get the extension of the file
            $ext = pathinfo($item, PATHINFO_EXTENSION);

            //include only the filw with xlsx and xls extionsions
            if (in_array(strtolower($ext), ['xlsx', 'xls'])) {
                $excelFiles[] = $filepath;
            }
        }
    }

    return $excelFiles;
}

function cleanExcel($excel, $columnLimit = 5)
{

    $cleanedExcel = [];

    foreach ($excel as $rowKey => $rows) {
        $isRowEmpty = true;

        foreach ($rows as $cell) {
            if (!empty($cell)) {
                $isRowEmpty = false;
            }
        }

        if ($rowKey === 0) {
            continue;
        }

        if ($isRowEmpty) {
            break;
        }

        foreach ($rows as $cellKey => $cell) {
            if ($cellKey > $columnLimit) {
                break;
            }

            if (empty($cell)) {
                $cell = "N/A";
            }

            $cleanedExcel[$rowKey][$cellKey] = $cell;
        }
    }

    return $cleanedExcel;
}

function moveExcel($originalPath, $newPath)
{
    $isMoved = false;
    $message = '';

    //check for the path if exist, create if not
    if (!is_dir($newPath)) {
        mkdir($newPath, 0775, true);
    }

    if (file_exists($originalPath)) {
        $filename = basename($originalPath);

        $destination = rtrim($newPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;

        if (rename($originalPath, $destination)) {
            return [
                "file" => [
                    'filename' => $filename,
                    'success' => true,
                    'message' => "File $filename has been moved to archive"
                ]
            ];
        } else {
            return [
                "file" => [
                    'filename' => $filename,
                    'success' => false,
                    'message' => "Failed to move file $filename. Please delete the file or manually move it"
                ]
            ];
        }
    }
}
