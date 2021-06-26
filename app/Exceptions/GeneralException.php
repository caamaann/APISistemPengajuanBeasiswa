<?php

namespace App\Exceptions;

use Exception;

/**
 * Class GeneralException
 */
class GeneralException extends Exception
{
    /**
     * Any extra data to send with the response.
     *
     * @var array
     */
    public $data = [];

    /**
     * The status code to use for the response.
     *
     * @var integer
     */
    public $status = 500;

    /**
     * Create a new exception instance.
     *
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }

    /**
     * In Laravel 5.5, you can render your exceptions directly from the exception class
     * itself, allowing you to handle them they way you want to.
     */
    public function render($request)
    {
        if ($request->expectsJson()) {
            return $this->handleAjax();
        }

        return redirect()->back()
            ->withInput()
            ->withErrors($this->getMessage());
    }

    /**
     * Handle an ajax response.
     */
    private function handleAjax()
    {
        return response()->json([
            'error'   => true,
            'message' => $this->getMessage(),
            'data'    => $this->data
        ], $this->status);
    }

    /**
     * Set the extra data to send with the response.
     *
     * @param array $data
     *
     * @return $this
     */
    public function withData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Set the HTTP status code to be used for the response.
     *
     * @param integer $status
     *
     * @return $this
     */
    public function withStatus($status)
    {
        $this->status = $status;

        return $this;
    }

}

