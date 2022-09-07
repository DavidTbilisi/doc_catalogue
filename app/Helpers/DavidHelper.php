<?php

if (!function_exists('bytesToMb')) {

    function bytesToMb(int $bytes, int $precision = 1): float
    {
        return number_format($bytes / 1048576, $precision);
    }
}


if (!function_exists('pathToTiles')) {

    function pathToTiles(string $tileFolder): string
    {
        $fileName = pathinfo($tileFolder,  PATHINFO_FILENAME);
        return base_path("public/storage/tiles/".$fileName);

    }
}
