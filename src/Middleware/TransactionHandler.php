<?php

namespace Laraveldbtransactions\Middleware;

use Closure;

class TransactionHandler
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->startTime = microtime(true);
        \DB::enableQueryLog();

        \DB::beginTransaction();
        try {
            return $next($request);
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
        return $next($request);
    }

    public function terminate($request, $response)
    {

        $queries = \DB::getQueryLog();
        if ($response instanceof Response && $response->getStatusCode() >= 500) {
            \DB::rollBack();

        /*

            Uncomment this section to log errors

            if (env('API_DATALOGGER', true)) {
            $endTime = microtime(true);
            $filename = 'api_datalogger_rlogger.log';
            $dataToLog = ["Time" => gmdate("F j, Y, g:i a"),
            "Duration" => number_format($endTime - LARAVEL_START, 3),
            "Ip-address" => $request->ip(),
            "Url" => $request->fullUrl(),
            "method" => $request->method(),
            "input" => $request->getContent(),
            "headers" => request()->header(),
            "response" => $response->getContent(),
            "queries" => $queries];

            $dataToLog = json_encode($dataToLog);

            \File::append(storage_path('logs' . DIRECTORY_SEPARATOR . $filename), $dataToLog . "\n" . str_repeat("=", 60) . "\n\n");
            }

        */

        } else {
            \DB::commit();
        }

        return $response;

    }

}
