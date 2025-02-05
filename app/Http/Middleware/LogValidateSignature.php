<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class LogValidateSignature extends ValidateSignature
{
    public function handle($request, Closure $next, ...$args)
    {
        if ($this->hasInvalidSignature($request)) {
            Log::warning('Invalid signature detected', [
                'url' => $request->fullUrl(),
                'query' => $request->query(),
                'expected' => $this->expectedSignature($request),
                'provided' => $request->query('signature'),
            ]);

            throw new InvalidSignatureException;
        }

        return $next($request);
    }

    protected function expectedSignature($request)
    {
        $url = $request->url();

        $queryString = Arr::except($request->query(), 'signature');

        ksort($queryString);

        $original = rtrim($url.'?'.http_build_query($queryString, '', '&', PHP_QUERY_RFC3986), '?');

        $key = config('app.key');

        return hash_hmac('sha256', $original, $key);
    }
}
