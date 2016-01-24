<?php

namespace Solid\Ajax;

use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    /**
     * Execute an action on the controller.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callAction($method, $parameters)
    {
        $result = parent::callAction($method, $parameters);

        if (app('ajax.helper')->isFrameworkAjax()) {
            $result = $this->prepareAjaxActionResponse($result);
        }

        return $result;
    }

    /**
     * Prepare response to make it compatible with 
     * front end Ajax Framework
     *
     * @param  mixed        $result
     *
     * @return JsonResponse         
     */
    protected function prepareAjaxActionResponse($result)
    {
        if ($result instanceof \Illuminate\View\View) {
            return $this->ajaxResponse(['result' => $result->render()]);
        }

        if (is_array($result)) {
            foreach ($result as $key => $value) {
                if ($value instanceof \Illuminate\View\View) {
                    $result[$key] = $value->render();
                }
            }
        }
        return $this->ajaxResponse($result);
    }

    /**
     * Convert given result to format expected by front end framework
     *
     * @param  mixed        $result 
     *
     * @return JsonResponse
     */
    protected function ajaxResponse($result)
    {
        return response()->json($result);
    }
}
