#!/usr/bin/php
<?php
/*
 * Extract function signature from doc files
 *
 * @author Robin ZHAO (boborabit@gmail.com)
 * @version 1.0
 * @date 2013-07-02
 *
 */
$dir = __DIR__ . '/../html';
$filename = 'book.filesystem.html';
//$filename = 'function.basename.html';

if($argc > 1){// with arguments.
  $filename = $argv[1];
}

$file = $dir . '/' . $filename;

function stripNewline($string){
  return preg_replace('/\n/', '', $string);
}

function extractSignature($file) {
  $contents = file_get_contents($file);
  $contents = stripNewline($contents);
  // match function signature part.
  $pattern_signature = '/<div[^>]+methodsynopsis[^>]+.*<\/div>/U';
  $matches_signature = array();
  $return = '';
  if (preg_match($pattern_signature, $contents, $matches_signature)) {
    $return = $matches_signature[0];
    $pattern_desc = '/<p[^>]+rdfs-comment.*<\/p>/U';
    $matches_desc = array();
    if (preg_match($pattern_desc, $contents, $matches_desc)) {
      $return .= $matches_desc[0];
    }
  }
  return $return;
}

function extractLinks($file){
  $contents = file_get_contents($file);
  $contents = stripNewline($contents);
  $pattern = '/<ul[^>]+chunklist_children[^>]+>.+<\/ul>/U';
  $matches = array();
  preg_match_all($pattern, $contents, $matches);
  if(isset($matches[0][1])){
    $m = array();
    preg_match_all('/href="(.*)"/U', $matches[0][1], $m);
    var_dump($m[1]);
    if(isset($m[1])){
      return $m[1];
    }
  }
  return array();
}

function style(){
  return <<<CSS
<style>
  body{width:750px;font-size:11px;}
  .para{margin-left:20px;margin-bottom:15px;margin-top:0;}
</style>
CSS;
}

$handle = fopen('/tmp/toto', 'w');
fwrite($handle, style());
foreach(extractLinks($file) as $each){
  $c =  extractSignature($dir . '/' . $each);
  fwrite($handle, $c);
}
fclose($handle);

system('firefox /tmp/toto &');
