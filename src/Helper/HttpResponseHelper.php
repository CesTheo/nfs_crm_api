<?php


namespace App\Helper;

use Symfony\Component\HttpClient\Response\NativeResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HttpResponseHelper
{
  public static function success(mixed $data = null)
  {
    return [
      'success' => true,
      'data' => $data,
    ];
  }

  public static function error(string $message, array $errors = null, int $status = null) : array
  {
    $message = [
      'success' => false,
      'message' => $message,
    ];
    if($errors) {
      $message['errors'] = $errors;
    }

    if($status) {
      $message['status'] = $status;
    }

    throw new HttpException($message);
  }

  public static function notFound(string $message, array $errors = null)
  {
    return self::error($message, $errors, 404);
  }

  public static function badRequest(string $message, array $errors = null)
  {
    return self::error($message, $errors, 400);
  }

  public static function formatErrorFromResponse(NativeResponse $response)
  {
    $errors = [];
    
    $data = $response->toArray();
    foreach($data['errors'] as $error) {
      $errors[] = $error['message'];
    }

    return self::error($response->getErrorMessage(), $errors, $response->getStatusCode());
  }
}