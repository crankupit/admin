<?php

namespace CrankUpIT\Admin\Http\Responses;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Responsable;
use CrankUpIT\Admin\Contracts\AdminLoginViewResponse;
use CrankUpIT\Admin\Contracts\AdminTFAChallengeViewResponse;

class AdminViewResponse implements
    AdminLoginViewResponse,
    AdminTFAChallengeViewResponse
{
    /**
     * The name of the view or the callable used to generate the view.
     *
     * @var callable|string $view
     */
    protected $view;

    /**
     * Create a new response instance.
     *
     * @param  callable|string $view
     * @return void
     */
    public function __construct($view)
    {
        $this->view = $view;
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        if (!is_callable($this->view) || is_string($this->view)) {
            return view($this->view, ['request' => $request]);
        }

        $response = call_user_func($this->view, $request);

        if ($response instanceof Responsable) {
            return $response->toResponse($request);
        }

        return $response;
    }
}
