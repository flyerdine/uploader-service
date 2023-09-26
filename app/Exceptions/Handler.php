<?php

namespace App\Exceptions;

use App\Traits\ResponseService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    use ResponseService;

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        //
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (\Throwable $e) {
            //
        });

        $this->renderable(function (ValidationException $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        });

        $this->renderable(function (MethodNotAllowedHttpException $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_METHOD_NOT_ALLOWED);
        });

        $this->renderable(function (NotFoundHttpException $e) {
            return $this->errorResponse($e->getMessage() ? $e->getMessage() : "Not found!", Response::HTTP_NOT_FOUND);
        });

        $this->renderable(function (AuthorizationException $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
        });

        $this->renderable(function (BadRequestException $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        });

        $isProduction = env('APP_ENV', 'production') === 'production';

        $this->renderable(function (HttpResponseException $e) use ($isProduction) {
            $code = ($e instanceof HttpExceptionInterface) ? $e->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = $isProduction ? "Opps! Something went wrong." : $e->getMessage();
            return $this->errorResponse($message, $code);
        });

        $this->renderable(function (\Exception $e) use ($isProduction) {
            $code = ($e instanceof HttpExceptionInterface) ? $e->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = $isProduction ? "Opps! Something went wrong." : $e->getMessage();
            return $this->errorResponse($message, $code);
        });

        $this->renderable(function (\Throwable $th) use ($isProduction) {
            $code = $th->getCode();
            $message = $isProduction ? "Opps! Something went wrong." : $th->getMessage();
            return $this->errorResponse($message, $code);
        });
    }
}
