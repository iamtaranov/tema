<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class MainController extends Controller
{
    /**
     * @return JsonResponse
     *
     * @Route("/", name="api_homepage")
     */
    public function surveyHomepageAction()
    {
        return new JsonResponse([
            'status' => 'OK',
            'ver' => 'v2',
            'rev' => '1',
            'timestamp' => time()
        ]);
    }
}