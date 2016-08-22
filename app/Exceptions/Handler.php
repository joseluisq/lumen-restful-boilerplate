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
    $status = 200;
    $success = TRUE;
    $response = NULL;

    if ($e instanceof HttpResponseException) {
      $success = FALSE;
      $status = Response::HTTP_INTERNAL_SERVER_ERROR;
      $response = $e->getResponse();
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
    } elseif ($e instanceof \Dotenv\Exception\ValidationException && $e->getResponse()) {
      $success = FALSE;
      $status = Response::HTTP_BAD_REQUEST;
      $e = new \Dotenv\Exception\ValidationException('The server cannot or will not process the request due to an apparent client error.', $status, $e);
      $response = $e->getResponse();
    } elseif ($e) {
      $success = FALSE;
      $status = Response::HTTP_INTERNAL_SERVER_ERROR;
      $e = new HttpException($status, 'It has occurred an unexpected server error.');
    }

    $res = response()->json([
      'success' => $success,
      'status' => $status,
      'message' => $e->getMessage()
      ], $status);

    return $res;
  }

}
