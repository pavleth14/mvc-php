<?php

declare(strict_types=1);

namespace App\Controllers;

use App\View;
use PDO;
use PDOException;

require_once('./View.php');

class HomeController
{
    // view, umesto ovoga ide react recimo
    public function index(): View
    {        
        try {
            $db = new PDO(
                'mysql:host=' . $_ENV['DB_HOST'] . 
                ';dbname=' . $_ENV['DB_DATABASE'],
                $_ENV['DB_USER'],
                $_ENV['DB_PASS']                
            );
        } catch (PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }

        $email = 'pavleee.com';  // paziti da nije duplikat
        $name = 'John Doe';
        $amount = 25;
        // $createdAt = date('Y-m-d H:i:s', strtotime('06/25/2025 13:20'));

        try {
            $db->beginTransaction();

            $newUserStmt = $db->prepare(
                'INSERT INTO users(email, full_name, is_active, created_at)
             VALUES(?, ?, 1, NOW())'
            );

            $newInvoiceStmt = $db->prepare(
                'INSERT INTO invoices(amount, user_id)
             VALUES(?, ?)'
            );


            $newUserStmt->execute([$email, $name]);
            
            $userId = (int) $db->lastInsertId();            
            
            $newInvoiceStmt->execute([$amount, $userId]);

            $db->commit();
            
        } catch (\Throwable $e) {
            if($db->inTransaction()) {
                $db->rollBack();
            }

            throw $e;
        }

        $fetchStmt = $db->prepare(
           'SELECT invoices.id AS invoice_id, amount, user_id, full_name
            FROM invoices
            INNER JOIN users on user_id = users.id
            WHERE email = ?'
        );

        $fetchStmt->execute([$email]);

        echo "<pre>";
        var_dump($fetchStmt->fetch(PDO::FETCH_ASSOC));
        echo "</pre>";

        return View::make('index', ['foo' => 'bar']);
    }

    public function download()
    {
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

        $filePath = STORAGE_PATH . '/' . $_FILES['receipt']['name'];
        move_uploaded_file($_FILES['receipt']['tmp_name'], $filePath);

        // echo '<pre>';
        // var_dump(pathinfo($filePath));
        // echo '</pre>';

        header('Location: /programming_with_gio/062_pdo_2/');
        exit;
    }
}
