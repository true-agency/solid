<?php namespace Solid\Ajax;

use App;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as BaseExceptionHandler;
use Illuminate\Foundation\Validation\ValidationException as LaravelValidationException;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Solid\Ajax\AjaxException;
use Solid\Exception\ValidationException as ValidationException;
use Solid\Facades\Flash;

/**
 * Handle exception in AJAX context
 */
class ExceptionHandler extends BaseExceptionHandler
{

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        foreach ($this->dontReport as $type) {
            if ($e instanceof $type) {
                return parent::report($e);
            }
        }

        if (app()->bound('bugsnag')) {
            app('bugsnag')->notifyException($e, null, "error");
        }

        parent::report($e);
    }

    /**
     * Render the exception response
     *
     * @param  Request    $request
     * @param  \Exception $e      
     *
     * @return mixed
     */
    public function render($request, \Exception $e)
    {
        if (app('ajax.helper')->isFrameworkAjax()) {
            $response = $this->handleAjaxException($request, $e);
                
            if (!is_null($response))
                return $response;
        }

        return parent::render($request, $e);
    }

    /**
     * Handle custom ajax response
     *
     * @param  [type] $request [description]
     * @param  [type] $e       [description]
     *
     * @return [type]          [description]
     */
    protected function handleAjaxException($request, $e)
    {
        $response = null;
        $responseContents = [];

        // Laravel default validation
        if ($e instanceof LaravelValidationException && $e->getResponse()) {
            
            $errors = $e->validator->messages();
            $fields = [];

            foreach ($errors->getMessages() as $field => $messages) {
                $fields[$field] = $messages;
            }

            $message = $errors->first();

            return $this->makeAjaxResponseContent(
                $fields, 
                $message
            );

        // Our custom, more simple Validation exception
        } else if ($e instanceof ValidationException) {

            return $this->makeAjaxResponseContent(
                $e->getFields(),
                $e->getMessage()
            );

        // Ajax error message
        } else if ($e instanceof AjaxException) {

            $responseContents = array_merge_recursive($responseContents, $e->getContents());
            $responseContents['X_OCTOBER_ERROR_MESSAGE'] = $responseContents['result'];
            // Smart error code
            $response = response()->json($responseContents, 406);
            
        // Runtime Errors
        } else if ($e instanceof \ErrorException) {
            
            $message = env('APP_DEBUG', false)
                ? ajax_dump($e)
                : 'Whoops, we encountered an error.';

            $responseContents['X_OCTOBER_ERROR_MESSAGE'] = $message;
            $response = response()->json($responseContents, 406);

        // Catch all
        } else {
            $message = env('APP_DEBUG', false)
                ? ajax_dump($e)
                : 'Whoops, we encountered an error.';

            $responseContents['X_OCTOBER_ERROR_MESSAGE'] = $message;
            $response = response()->json($responseContents, 406);
        }

        return $response;
    }

    protected function makeAjaxResponseContent($fields, $message)
    {
        $responseContents = [];

        /*
         * Handle validation errors
         */
        $responseContents['X_OCTOBER_ERROR_FIELDS'] = $fields;
        $responseContents['X_OCTOBER_ERROR_MESSAGE'] = $message;

        // Smart error code
        return response()->json($responseContents, 406);
    }

}