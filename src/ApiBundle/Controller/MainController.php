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
            'status' => 'working',
            'version' => 'v2',
            'build' => 'b0818-1',
            'timestamp' => time()
        ]);
    }

    /**
     * @Route("/test", name="api_test")
     */
    public function test() { }
}