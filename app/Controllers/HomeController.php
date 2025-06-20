<?php

declare(strict_types=1);
namespace App\Controllers;
use App\View;
require_once('./View.php');

class HomeController
{
    // view, umesto ovoga ide react recimo
    public function index(): View
    {
        return View::make('index', ['foo' => 'bar']); 
    }

    public function download() {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment;filename="myfile.pdf"');
        readfile(STORAGE_PATH . '/CV-Pavle-Stojanovic.pdf');
        // /receipt 6-20-2025.pdf'
    }
    
    public function upload() 
    {
        echo '<pre>';
        var_dump($_FILES);
        echo '</pre>';

        $filePath = STORAGE_PATH . '/' .$_FILES['receipt']['name'];
        move_uploaded_file($_FILES['receipt']['tmp_name'], $filePath);

        // echo '<pre>';
        // var_dump(pathinfo($filePath));
        // echo '</pre>';

        header('Location: /programming_with_gio/061_http_headers/');
        exit;        

    }
}
