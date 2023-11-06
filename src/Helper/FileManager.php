<?php
namespace Ciber2018\Tabletoscript\Helper;

use DOMDocument;
use DOMXPath;
use ZipArchive;


class FileManager{  


    public function parseDocX ($fileDir){
        $zip = new ZipArchive;        
        $allTables = [];
        if ($zip->open($fileDir)) {            
            if (($index = $zip->locateName('word/document.xml')) !== false) {                       
                $data = $zip->getFromIndex($index);                               
                $zip->close();                
                $xml = new DOMDocument();                               
                $xml->loadXML($data, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);

                $xp=new DOMXPath( $xml );
                $xp->registerNamespace('ve','http://schemas.openxmlformats.org/markup-compatibility/2006');
                $xp->registerNamespace('r','http://schemas.openxmlformats.org/officeDocument/2006/relationships');
                $xp->registerNamespace('m','http://schemas.openxmlformats.org/officeDocument/2006/math');
                $xp->registerNamespace('wp','http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing');
                $xp->registerNamespace('w','http://schemas.openxmlformats.org/wordprocessingml/2006/main');
                $xp->registerNamespace('pic','http://schemas.openxmlformats.org/drawingml/2006/picture');
                $xp->registerNamespace('a','http://schemas.openxmlformats.org/drawingml/2006/main');           
                $xp->registerNamespace('wne','http://schemas.microsoft.com/office/word/2006/wordml');
                
            $tables = $xp->query( '//w:tbl' );
            if ($tables->length > 0) {
                foreach ($tables as $tab => $rows) {
                    $expr='w:tr';                   
                    $currentTable=$xp->query( $expr, $rows );  
                    $headers = [];
                    $body = [];                    
                    foreach ($currentTable as $row => $tr) {
                        $expr='w:tc';
                        $cells=$xp->query( $expr, $tr );
                        foreach ($cells as $cell => $text) {
                            if ($row == 0) {
                               $headers[]= $text->textContent;
                            }else{
                               $body[] = $text->textContent;
                            } 
                      
                        }
                    }
                    $aux = array('header'=>$headers,'body'=>$body);
                    array_push($allTables,$aux);
                }                
            }                 
            
            }            
        } 
        return $allTables;       
    }

}