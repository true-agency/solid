<?php

namespace Solid\Ajax;

use Illuminate\Contracts\Validation\Factory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Solid\Exception\ValidationException;

trait ValidatesAjaxRequests
{
    /**
     * The default error bag.
     *
     * @var string
     */
    protected $validatesRequestErrorBag;

    /**
     * Validate the given request with the given rules.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $customAttributes
     * @return void
     */
    public function validateAjax(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getAjaxValidationFactory()->make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $this->throwAjaxValidationException($request, $validator);
        }
    }

    /**
     * Throw the failed validation exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Foundation\Validation\ValidationException
     */
    protected function throwAjaxValidationException(Request $request, $validator)
    {
        throw new ValidationException($validator, $this->buildFailedValidationResponse(
            $request, $this->formatValidationErrors($validator)
        ));
    }

    /**
     * Get a validation factory instance.
     *
     * @return \Illuminate\Contracts\Validation\Factory
     */
    protected function getAjaxValidationFactory()
    {
        return app(Factory::class);
    }

}
