<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController
{
    /**
     * @Route("/", name="default")
     */
    public function default()
    {
       

        return new Response(
            '<html><body>Home</body></html>'
        );
    }
}
