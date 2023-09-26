<?php
namespace App\Traits;

use Illuminate\Http\Response;

trait ResponseService
{
    /**
     * Build success responses
     * @param  string|array $message
     * @param  int $code
     * @return Illuminate\Http\JsonResponse
     */
    public function successResponse($data, $code = Response::HTTP_OK)
    {
        return response()->json(['error' => false, 'response' => $data], $code);
    }

    /**
     * Build error responses
     * @param  string|array $message
     * @param  int $code
     * @return Illuminate\Http\JsonResponse
     */
    public function errorResponse($message, $code = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        $code = in_array($code, array_keys(Response::$statusTexts)) ? $code : Response::HTTP_INTERNAL_SERVER_ERROR;
        return response()->json(['error' => true, 'message' => $message], $code);
    }
}
