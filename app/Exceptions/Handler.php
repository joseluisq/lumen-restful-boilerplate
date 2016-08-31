<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Http\Exception\HttpResponseException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Response;

class Handler extends ExceptionHandler {

  /**
   * A list of the exception types that should not be reported.
   *
   * @var array
   */
  protected $dontReport = [
    AuthorizationException::class,
    HttpException::class,
    ModelNotFoundException::class,
    ValidationException::class,
  ];

  /**
   * Report or log an exception.
   *
   * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
   *
   * @param  \Exception  $e
   * @return void
   */
  public function report(Exception $e) {
    parent::report($e);
  }

  /**
   * Render an exception into an HTTP response.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Exception  $e
   * @return \Illuminate\Http\Response
   */
  public function render($request, Exception $e) {
    if (env('APP_DEBUG')) {
      return parent::render($request, $e);
    }

    $status = 200;
    $success = TRUE;
    $response = NULL;

    if ($e instanceof HttpResponseException) {
      $success = FALSE;
      $status = Response::HTTP_INTERNAL_SERVER_ERROR;
    } elseif ($e instanceof MethodNotAllowedHttpException) {
      $success = FALSE;
      $status = Response::HTTP_METHOD_NOT_ALLOWED;
      $e = new MethodNotAllowedHttpException([], 'The request method is not supported for the requested resource.', $e);
    } elseif ($e instanceof NotFoundHttpException) {
      $success = FALSE;
      $status = Response::HTTP_NOT_FOUND;
      $e = new NotFoundHttpException('The requested resource could not be found but may be available in the future.', $e);
    } elseif ($e instanceof AuthorizationException) {
      $success = FALSE;
      $status = Response::HTTP_FORBIDDEN;
      $e = new AuthorizationException('You do not have the necessary permissions for the resource.', $status);
    }

    $res = response()->json([
      'success' => $success,
      'status' => $status,
      'message' => $e->getMessage()
      ], $status);

    return $res;
  }

}
