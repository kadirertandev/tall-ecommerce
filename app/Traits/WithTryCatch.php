<?php

namespace App\Traits;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Throwable;

trait WithTryCatch
{
  use WithSweetAlert;

  public function defaultExceptionHandlers()
  {
    return [
      AuthorizationException::class => function ($e) {
        $this->swalError([
          "titleText" => $e->getMessage()
        ]);
      },
      ModelNotFoundException::class => function ($e) {
        $this->swalError([
          "titleText" => Str::singular(Str::ucfirst(app($e->getModel())->getTable())) . " not found!"
        ]);
      },
      Throwable::class => function ($e) {
        $this->swalTemplateSomethingWentWrong();
      }
    ];
  }

  public function tryCatch(\closure $try, array $exceptions = [])
  {
    try {
      $try();
      return true; #no exception
    } catch (Throwable $e) {
      $handlers = $this->defaultExceptionHandlers();

      $throwableKey = array_key_last($handlers);
      $throwableValue = $handlers[$throwableKey];

      $throwableCustomized = false;
      foreach ($exceptions as $exception => $handler) {
        $handlers[$exception] = $handler;

        if ($exception == $throwableKey) {
          $throwableCustomized = true;
        }
      }

      unset($handlers[$throwableKey]);

      #check if throwable is customized
      $handlers[$throwableKey] = $throwableCustomized
        ? $exceptions[$throwableKey]
        : $throwableValue;

      foreach ($handlers as $exception => $handler) {
        if ($e instanceof $exception) {
          $handler($e);
          return false; #exception handled
        }
      }
    }
  }
}