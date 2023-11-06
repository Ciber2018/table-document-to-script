<?php

namespace Ciber2018\Tabletoscript\Controllers;

use DOMDocument;
use DOMXPath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use ZipArchive;
use Ciber2018\Tabletoscript\Helper\FileManager;

class ReadController{

    public function __construct(protected FileManager $fileManager)
    {
        
    }

    public function index()
    {
        return view('tabletoscript::index');       
    }

    

    public function read(Request $request)
    {  
        
        $filepath = $request->file('document')->getPathname();   
        
        return $this->readDocX($filepath);       
        
    }

    private function readDocX ($fileDir){        
      
        $tables = $this->fileManager->parseDocX($fileDir);        
        return view('tabletoscript::process/process',compact('tables'));
        
    }

}