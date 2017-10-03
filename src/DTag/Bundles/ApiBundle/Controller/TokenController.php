<?php

namespace DTag\Bundles\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenController extends Controller
{
    /**
     * Retrieve a JSON Web Token
     *
     * @RequestParam(name="username")
     * @RequestParam(name="password")
     *
     * @ApiDoc(
     * statusCodes={
     *  200 = "Successful login",
     *  401 = "Login failed"
     * })
     */
    public function getTokenAction()
    {
        // The security layer will intercept this request
        return new Response('', 401);
    }

    /**
     * check token for the current authenticated user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkTokenAction(Request $request)
    {
        // The security layer has already checked if the user has a valid token

        $data = [];
        $user = $this->getUser();

        return new JsonResponse(
            $this->get('tagcade_api.service.jwt_response_transformer')->transform($data, $user)
        );
    }
}
