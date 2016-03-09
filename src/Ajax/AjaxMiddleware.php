<?php

namespace Solid\Ajax;

use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Request;
use Response;

class AjaxMiddleware
{

    /*
    |--------------------------------------------------------------------------
    | Ajax Framework
    |--------------------------------------------------------------------------
    |
    | Ported version of Ajax Handler from OctoberCMS
    | @see OctoberCMS Backend\Classes\Controller
    |
    */

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $response = $next($request);

        if (app('ajax.helper')->isFrameworkAjax()) {
            $response = $this->modifyAjaxResponse($response);
        }

        return $response;
    }

    protected function modifyAjaxResponse($response)
    {
        $responseContents = [];

        if ($response->getStatusCode() === 406) {
            return $response;
        }

        $originalContent = $response->getContent();

        /*
         * If the handler returned an array, we should add it to output for rendering.
         * If it is a string, add it to the array with the key "result".
         */
        if (is_array($response)) {
            $responseContents = array_merge($responseContents, $response);
        }
        elseif ($response instanceof JsonResponse) {
            $decode = json_decode($response->getContent(), true);
            $responseContents = array_merge($responseContents, $decode);
        }
        elseif (is_string($response)) {
            $responseContents['result'] = $response;
        }

        /*
         * Render partials and return the response as array that will be converted to JSON automatically.
         */
        // foreach ($partialList as $partial) {
        //     $responseContents[$partial] = $this->makePartial($partial);
        // }

        /*
         * If the handler returned a redirect, process it so framework.js knows to redirect
         * the browser and not the request!
         */
        if ($response instanceof RedirectResponse) {
            $responseContents['X_OCTOBER_REDIRECT'] = $response->getTargetUrl();
        }
        /*
         * No redirect is used, look for any flash messages
         */
        elseif (app('solid.flash')->check()) {
            $responseContents['#layout-flash-messages'] = view('shared.solid-flash-message')->render();
        }

        return Response::make()->setContent($responseContents);
    }


}