<?php

$imgDir = 'png';
$outDir = 'out';

$dirHandle = opendir($imgDir);
$images = [];



while ($item = readdir($dirHandle)) {
    if (is_dir($item))
        continue;
    $im = imagecreatefrompng($imgDir.'/'.$item);
    $images[] = $im;
}

closedir($dirHandle);

$resizedImages = [];
foreach ($images as $index => $image) {
    $width = imagesx($image);
    $height = imagesy($image);
    $outWidth += imagesy($image);
    $outHeight += imagesy($image);
    $outColumns = 3;
    
    $wantHeight = $outWidth / $outColumns;
     $wantWidth = $outHeight / (count($images) / $outColumns);

    $resized = imagecreatetruecolor($wantWidth, $wantHeight);
    imagecopyresampled($resized, $image, 0, 0, 0, 0, $wantWidth, $wantHeight, $width, $height);

    $resizedImages[] = $resized;
    imagedestroy($image);
}

$out = imagecreatetruecolor($outWidth, $outHeight);
for ($y = 0; $y < count($images) / $outColumns; $y++) {
    for ($x = 0; $x < $outColumns; $x++) {
        $index = $x + ($y * $outColumns);
        $resized = $resizedImages[$index] ?? null;

        if (!$resized)
            continue;

        $width = imagesx($resized);
        $height = imagesy($resized);

        imagecopy($out, $resized, $x * $width, $y * $height, 0, 0, $width, $height);
        imagedestroy($resized);
    }
}

imagepng($out, $outDir.'out.png');
imagedestroy($out);
