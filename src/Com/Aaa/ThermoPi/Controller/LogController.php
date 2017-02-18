<?php
/**
 * @author tashton
 */

namespace Com\Aaa\ThermoPi\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LogController extends Controller
{

    public function indexAction(Request $request)
    {
        return new Response();
    }

    public function graphAction(Request $request)
    {
        return new Response();
    }

    public function historyAction(Request $request)
    {
        return new JsonResponse();
    }

}