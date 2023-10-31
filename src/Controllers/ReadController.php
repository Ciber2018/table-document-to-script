<?php

namespace Ciber2018\Tabletoscript\Controllers;

use DOMDocument;
use DOMXPath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use ZipArchive;

use function Laravel\Prompts\text;

class ReadController{
    public function index()
    {
        return view('tabletoscript::index');       
    }

    

    public function read(Request $request)
    {  
        $content = $request->file('document')->getContent();
        $fileSize = File::size($request->file('document')->getPath());

        echo $this->parseDocX($request->file('document')->getPathname());

        /*$striped_content = '';
        $content = '';
        $zip = zip_open($request->file('document')->getPathname());
        if (!$zip || is_numeric($zip)) return false;
        while ($zip_entry = zip_read($zip)) {
            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
            if (zip_entry_name($zip_entry) != "word/document.xml") continue;
            $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
            zip_entry_close($zip_entry);
        }// end while
        zip_close($zip);
        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        $content = str_replace('</w:r></w:p>', "\r\n", $content);
        $striped_content = strip_tags($content);
        return nl2br($striped_content);*/
       

        /*$doc = fopen($request->file('document')->getPathname(),'r');
        $headers = fread($doc, $fileSize);
        $n1 = ( ord($headers[0x21C]) - 1 );
        $n2 = ( ( ord($headers[0x21D]) - 8 ) * 256 );
        $n3 = ( ( ord($headers[0x21E]) * 256 ) * 256 );
        $n4 = ( ( ( ord($headers[0x21F]) * 256 ) * 256 ) * 256 );
        $textLength = ($n1 + $n2 + $n3);
        $extracted_plaintext = fread($doc, $fileSize);
        $extracted_plaintext = mb_convert_encoding($extracted_plaintext,'UTF-8');
        echo nl2br($extracted_plaintext);*/

       // dd($doc);
        /*$lines = explode(chr(0x0D),$content);      
    
        $outtext = "";

        dd(chr(0x07));
        foreach($lines as $thisline)
        {
            $tam = strlen($thisline);
            if( !$tam )
            {
                continue;
            }
    
            $new_line = ""; 
            for($i=0; $i<$tam; $i++)
            {
                $onechar = $thisline[$i];
                if( $onechar > chr(240) )
                {
                    continue;
                }            
    
                if( $onechar >= chr(0x20) )
                {
                    //$caracteres++;
                    $new_line .= $onechar;
                }
    
                if( $onechar == chr(0x14) )
                {
                    $new_line .= "</a>";
                }
    
                if( $onechar == chr(0x07) )
                {
                    $new_line .= "\t";
                    if( isset($thisline[$i+1]) )
                    {
                        if( $thisline[$i+1] == chr(0x07) )
                        {
                            $new_line .= "\n";
                        }
                    }
                }
            }
            //troca por hiperlink
            $new_line = str_replace("HYPERLINK" ,"<a href=",$new_line); 
            $new_line = str_replace("\o" ,">",$new_line); 
            $new_line .= "\n";
    
            //link de imagens
            $new_line = str_replace("INCLUDEPICTURE" ,"<br><img src=",$new_line); 
            $new_line = str_replace("\*" ,"><br>",$new_line); 
            $new_line = str_replace("MERGEFORMATINET" ,"",$new_line); 
    
    
            $outtext .= nl2br($new_line);
        }
    
     //echo $outtext;*/
        
        
    }

    private function parseDocX ($fileDir){        
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
        
        return view('tabletoscript::process/process',compact('allTables'));
        
    }

}