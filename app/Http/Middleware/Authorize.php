<?php

namespace App\Http\Middleware;

use Closure;
use Exception;

class Authorize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, string ...$guards)
    {
        $authorizationToken = $request->header('Authorization');
        $token = str_replace('Bearer ', '', $authorizationToken);

        if (!$token) {
            // Unauthorized response if token not there
            $message = "Authorization failed ! Please add a token";
            return $this->response($message, 401);
        }
        try {
            if (!in_array($token, ['YI5uZzpS9VUXPfpsTYdEkTYMAHOIEcBl'])) {
                $message = "Authorization Failed";
                return $this->response($message, 401);
            }
        } catch (Exception $e) {
            $message = "Authentication Failed";
            return $this->response($message, 403);
        }
        // Now let's put the user in the request class so that you can grab it from there
        $request->auth = true;
        return $next($request);
    }

    /**
     * Get response
     */
    private function response($data = null, $statusCode = 200)
    {
        $error = $this->error($statusCode);

        return response()->json([
            'error' => $error,
            'statusCode' => $statusCode,
            'response' => $data
        ], $statusCode);
    }

    /**
     * Get error code
     */
    private function error($statusCode)
    {
        return substr($statusCode, 0, 1) == 2 ? false : true;
    }
}
