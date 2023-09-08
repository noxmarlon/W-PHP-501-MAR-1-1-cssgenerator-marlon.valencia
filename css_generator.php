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




while ($item = readdir($dirHandle)) {
    if (is_dir($item))
        continue;
    $im = imagecreatefrompng($imgDir.'/'.$item);
    
    // $cropped = imagecropauto($im, IMG_CROP_TRANSPARENT);
    // $images[] = [$cropped, imagesx($im), imagesy($im)]; abajo
    $images[] = $im;

    $tbb= substr($item,0,-4);
    
}

var_dump($tbb);

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
    var_dump($tablero);

    imagecopymerge($resized,$image, $offset,0,0,0,$width,$height, 100);
   
    
}

imagepng($resized,"salut.png");

}
}
function crearcss($array1,$array2,$nombre,$name="style")
{
    
    $count = count($array1);

    $abrircss= fopen("$name.css",'w');
    fwrite($abrircss, '.'.$array2."\n".'{'."\n".'background-image: url('.$nombre.'.png);'."\n".'display: inline-block;'."\n".'overflow: hidden;'."\n".'text-align: left;'."\n".'}'."\n\n");
    $y=0;
    for($i=0; $i<$count; $i++)
    {
        if ($i>0)
            $y+=$array1[$i-2][3];
        fwrite($abrircss, '.'.$array1[$i][1]."\n".'{'."\n".'width: '.$array1[$i][2].'px;'."\n".'height: '.$array1[$i][3].'px;'."\n".'background-position : -0px -'.$y.'px;'."\n".'}'."\n\n");
    }
    fclose($array1);
    
}


        




    








// generarcss($argv[1]);
genenarsprite($argv[1]);