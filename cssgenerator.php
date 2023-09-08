<?php

/// poner png nombre en css //
global $name;
//

function listPng(string $path, bool $recursive = false): array
{
    if (!file_exists($path))
        throw new Exception("fichero no existe");

    $data = [];
    $images = [];

    $readDir = function ($dir) use (&$data, &$images, &$readDir, $recursive) {
        $handle = opendir($dir);
        while ($item = readdir($handle)) {
            if ($item == '.' || $item == '..')
                continue;

            if (is_dir("$dir/$item") && $recursive) {
                $readDir("$dir/$item");
                continue;
            } 
            elseif (is_dir("$dir/$item") && !$recursive) {
                continue;
            }

            $images[] = $img = imagecreatefrompng("$dir/$item");

            $name = substr($item, 0, -4);
            list($sw, $sh) = maxWY($images);
            
            $data[] = [
                'image' => $img,
                'name' => $name,
                'width' => imagesx($img),
                'height' => imagesy($img),
                'totalWidth' => $sw,
                'totalHeight' => $sh
            ];
        }
        closedir($handle);
    };

    $readDir($path);

    return [$images, $data];
}

function maxWY(array $files): array
{
    $w = 0;
    foreach ($files as $image)
        $w += imagesx($image);

    $h = 0;
    foreach ($files as $image) {
        $imgH = imagesy($image);
        if ($h < $imgH) $h = $imgH;
    }

    return [$w, $h];
}

function crearplatilla(string $template, array $data): string
{
    $formattedData = [];
    foreach ($data as $key => $value)
        $formattedData["\$$key"] = $value;

    $stream = fopen($template, 'r');
    $content = fread($stream, filesize($template));
    fclose($stream);
    
    return str_replace(array_keys($formattedData), array_values($formattedData), $content);
} 

function generateCss($data,string $output)
{
    global $name;
    $count = count($data);
    $stream = fopen($output, 'w');

    $y = 0;
    for ($i = 0; $i < $count; $i++) {
        if ($i > 0)
            $y += $data[$i - 1]['totalHeight'];
            var_dump($y);
        
        fwrite($stream,  crearplatilla("template.css", [
            'selector' => '.'.$data[$i]['name'],
            'image' => $name,
            'width' => $data[$i]['width'].'px',
            'height' => $data[$i]['height'].'px',
            'y' => $y > 0 ? "-{$y}px" : 0
        ]));
    }

    fclose($stream);
}

function generateSprite(array $images, string $output)
{
    global $name;
    $name =$output;
    list($sw, $sh) = maxWY($images);
    $resized = imagecreatetruecolor($sw, $sh);
    $color  = imagecolorallocate($resized, 0, 0, 0);
    imagecolortransparent($resized, $color);
    imagefill($resized, 0, 0, $color);
    $stockY = 0;
    $stockX = 0;
    $offset = 0;

    foreach ($images as $index => $image) {
        $width = imagesx($image);
        $height = imagesy($image);

        $stockX += $width;
        $stockY += $height;

        if ($index != 0)
            $offset += imagesx($images[$index - 1]);

        imagecopymerge($resized, $image, $offset, 0, 0, 0, $width, $height, 100);
    }
   
    imagepng($resized, $output);
    
}

function Juntaropciones(array $options): array
{
    return [
        'i' => $options['i'] ?? ($options['image'] ?? null),
        's' => $options['s'] ?? ($options['style'] ?? null),
        'r' => $options['r'] ?? ($options['recursive'] ?? null)
    ];
}

function normalizaropciones(array $options): array
{
    return [
        'i' => $options['i'],
        's' => $options['s'],
        'r' => $options['r'] !== null
    ];
}

function filtaropciones(array $options): array
{
    $messages = [];

    if ($image = $options['i'] ?? false) {
        if (!substr($image,4))
            $messages[] = "La salida de la imagen debe tener la extensión PNG.";
    }

    if ($style = $options['s'] ?? false)
        if (!substr($style,4))
            $messages[] = "La salida de estilo debe tener extensión CSS.";

    return $messages;
}

try {
    $opt = normalizaropciones(
        Juntaropciones(
            getopt('i:s:r', ['image', 'style', 'recursive'], $restIndex)
        )
    );
    $path = $argv[$restIndex];

    $messages = filtaropciones($opt);
    foreach ($messages as $message)
        echo "[Error] $message\n";
    if ($messages)
        return;

     list($images, $data) = listPng($path, $opt['r']);
    generateSprite($images, $opt['i'] ?: 'sprite.png');
     generateCss($data, $opt['s'] ?: 'style.css');
} catch (Exception $e) {
    echo "[Error] {$e->getMessage()}\n";
}

?>