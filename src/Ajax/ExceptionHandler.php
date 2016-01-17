<?php namespace Solid\Ajax;

use App;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as BaseExceptionHandler;
use Illuminate\Foundation\Validation\ValidationException as LaravelValidationException;
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
        parent::report($e);
    }

    public function render($request, \Exception $e)
    {
        if (app('ajax.helper')->isFrameworkAjax()) {
            $response = $this->handleAjaxException($request, $e);
                
            if (!is_null($response))
                return $response;
        }

        return parent::render($request, $e);
    }

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

        // Runtime Errors
        } else if ($e instanceof \ErrorException) {
            
            $message = env('APP_DEBUG', false)
                ? '<strong>' . $e->getFile() . '</strong><br>' . $e->getMessage() . ' - Line: ' . $e->getLine()
                : 'Whoops, we encountered an error.';

            $responseContents['X_OCTOBER_ERROR_MESSAGE'] = $message;
            $response = response()->json($responseContents, 406);

        // Ajax error message
        } else if ($e instanceof AjaxException) {

            $responseContents = array_merge_recursive($responseContents, $e->getContents());
            $responseContents['X_OCTOBER_ERROR_MESSAGE'] = $responseContents['result'];
            // Smart error code
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