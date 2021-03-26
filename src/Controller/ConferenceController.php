<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ConferenceController extends AbstractController
{
    public function index(string $name = ''): Response
    {

       $greet = '';
       if ($name) {
           $greet = sprintf('<h1>Hello %s!</h1>', htmlspecialchars($name));
       }
       
        return new Response("
            <html>
                <body>
                $greet
                    <h1>Hola mundo!!</h1>
                     
                </body>
            </html>
            ");
    }
}
