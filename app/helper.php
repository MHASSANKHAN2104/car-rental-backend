<?php

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\EncryptException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Stripe\Exception\ApiErrorException;

if (!function_exists('errorResponse')) {
    /**
     * errorResponse
     *
     * @param mixed $error
     * @param int $code
     * @param array $errorMessages
     * @return \Illuminate\Http\JsonResponse
     */
    function errorResponse($error, $code = 401)
    {
        $statusCode = $code == 0 ? 401 : $code;
        $response = [
            'success' => false,
            'status_code' => $statusCode,
            'message' => is_array($error) ? $error : [$error],
            'data' => null
        ];

        return response()->json($response, $statusCode);
    }
}

if (!function_exists('successResponse')) {
    /**
     * successResponse
     *
     * @param string $message
     * @param mixed $result
     * @param bool $paginate
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    // function successResponse($message, $result = [], $paginate = false, $code = 200)
    // {
    //     if ($paginate && is_object($result)) {
    //         $result = paginate($result);
    //     }

    //     $response = [
    //         'success' => true,
    //         'status_code' => $code,
    //         'message' => [$message],
    //         'data' => $result
    //     ];

    //     return response()->json($response, $code);
    // }

    function successResponse($message, $result = [], $paginate = false, $code = 200)
    {
        $response = [
            'success' => true,
            'status_code' => $code,
            'message' => [$message],
            'data' => $result
        ];

        // Handle pagination
        if ($paginate && !empty($result)) {
            $totalRecords = DB::table('trainers')->count(); // Total records in trainers table
            $limit = request()->query('limit', 10); // Default limit
            $page = request()->query('page', 1); // Default page
            $totalPages = ceil($totalRecords / $limit); // Total pages

            $paginationData = [
                'current_page' => (int) $page,
                'total_records' => $totalRecords,
                'total_pages' => $totalPages,
                'limit' => (int) $limit
            ];

            $response['pagination'] = $paginationData;
        }

        return response()->json($response, $code);
    }

}

if (!function_exists('getAuthenticatedUser')) {

    function getAuthenticatedUser()
    {
        return auth('api')->user();
    }
}

if (!function_exists('paginate')) {
    /**
     * paginate
     *
     * @param \Illuminate\Contracts\Pagination\LengthAwarePaginator $data
     * @return array|null
     */
    function paginate($data)
    {
        if ($data != null) {
            $paginationArray = [
                'list' => $data->items(),
                'pagination' => [
                    'total' => $data->total(),
                    'current' => $data->currentPage(),
                    'first' => 1,
                    'last' => $data->lastPage(),
                    'previous' => $data->currentPage() > 1 ? $data->currentPage() - 1 : null,
                    'next' => $data->hasMorePages() ? $data->currentPage() + 1 : null,
                    'pages' => range(1, $data->lastPage()),
                    'from' => $data->firstItem(),
                    'to' => $data->lastItem()
                ]
            ];

            return $paginationArray;
        }

        return null;
    }
}

if (!function_exists('handleException')) {
    /**
     * handleException
     *
     * @param \Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    function handleException(\Exception $e)
    {
        // Log the exception
        Log::error('An exception occurred: ' . $e);

        // Check specific exceptions and return appropriate error responses
        if ($e instanceof QueryException) {
            return errorResponse('Something went wrong with the database query.', 400);
        }

        if ($e instanceof ModelNotFoundException) {
            return errorResponse('The requested data was not found.', 404);
        }

        if ($e instanceof TokenInvalidException) {
            return errorResponse('Token is Invalid', 401);
        }

        if ($e instanceof TokenExpiredException) {
            return errorResponse('Token is Expired', 401);
        }

        if ($e instanceof JWTException) {
            return errorResponse('Authorization Token not found', 401);
        }

        if ($e instanceof ApiErrorException) {
            return errorResponse($e->getMessage(), 402);
        }

        // For other exceptions, return a generic error response
        return errorResponse('An unexpected error occurred.', $e->getCode());
    }
}




if (!function_exists('getCurrentUserId')) {

    function getCurrentUserId()
    {
        return Auth::guard('api')->id();
    }
}
