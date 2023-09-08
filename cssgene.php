<?php

function betterX($files)
{
    $betterX= 0;
    foreach ($files as $index => $image) {
        $width = imagesx($image);
        
        $betterX += $width;
    }
return $betterX;
}
function betterY($files)
{
    $betterY= 0;
    foreach ($files as $index => $image) {
        $height = imagesy($image);
        if($betterY < $height)
        $betterY = $height;
    }
return $betterY;
}
////////////////////////////////////////////////////////////////// css 
const CSS_TEMPLATE_FIRST = 'one.css';
const CSS_TEMPLATE_SECOND = 'another.css';


function fillTemplate(string $template, array $data): string
{
    $formattedData = [];
    foreach ($data as $key => $value)
        $formattedData["\$$key"] = $value;

    $stream = fopen($template, 'r');
    $content = fread($stream, filesize($template));
    fclose($stream);

    return str_replace(array_keys($formattedData), array_values($formattedData), $content);
}

function crearcss($array1, $array2, $nombre, $name = "style")
{
    $count = count($array1);

    $abrircss = fopen("$name.css",'w');
    fwrite($abrircss, fillTemplate(CSS_TEMPLATE_FIRST, [
        'selector' => ".$array2",
        'image' => "$nombre.png"
    ]));

    $y=0;
    for($i=0; $i<$count; $i++)
    {
        if ($i>0)
            $y+=$array1[$i-2][3];

        fwrite($abrircss, fillTemplate(CSS_TEMPLATE_SECOND, [
            'selector' => ".{$array1[$i][1]}",
            'width' => $array1[$i][2],
            'height' => $array1[$i][3],
            'x' => 0,
            'y' => "-{$y}px"
        ]));
    }
    fclose($array1);
}
/////////////////////////////////////////////

// function lista
//////////////////////////////

////////////////////////////////////
function genenarsprite($files)
{
    if( file_exists($files))
    {
$imgDir = $files;
$outDir = ".";

$dirHandle = opendir($imgDir);
$images = [];
$tbb= [];
//////////////////////////////////////////////////////////////////////// crear opcion para llamar al css,y la recursiva 
while ($item = readdir($dirHandle)) {
    if (is_dir($item))
        continue;
    $im = imagecreatefrompng($imgDir.'/'.$item);
    
    // $cropped = imagecropauto($im, IMG_CROP_TRANSPARENT);
    // $images[] = [$cropped, imagesx($im), imagesy($im)]; abajo
    $images[] = $im;

    $tbb= substr($item,0,-4);
    
}

// var_dump($);

closedir($dirHandle);

$resized = imagecreatetruecolor(betterX($images), betterY($images));

$stockY= 0;
$stockX= 0;
$offset=0;
$tablero= array();

foreach($images as $index => $image){
$width = imagesx($image);

    $height= imagesy($image);
    
    $stockX += $width ;
    $stockY += $height ;
    
    // var_dump($stockX);
    // var_dump($stockY);
    if($index != 0)
    {
        $offset +=imagesx($images[$index-1]);

    }
     $x= [$width];
     $y= [$height];
  

$tabb=[$image,$tbb,$width,$height];
   array_push($tablero,$tabb);
//  array_push($tablero,$tbb);
//    var_dump($y);
//    var_dump($tbb);
    // var_dump($tablero);

    imagecopymerge($resized,$image,$offset,0,0,0,$width,$height, 100);
   
    
}

imagepng($resized,"salut.png");

}
}
// generarcss($argv[1]);
genenarsprite($argv[1]);