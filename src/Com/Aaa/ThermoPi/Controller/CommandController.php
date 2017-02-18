<?php
/**
 * @author tashton
 */

namespace Com\Aaa\ThermoPi\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CommandController extends Controller
{

    public function readAction(Request $request)
    {
        return new JsonResponse();
    }

    public function commandAction(Request $request)
    {
        return new JsonResponse();
    }

}