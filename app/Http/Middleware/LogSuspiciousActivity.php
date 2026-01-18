<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogSuspiciousActivity
{
    /**
     * Patterns that indicate potential SQL injection attempts
     */
    private const SQL_INJECTION_PATTERNS = [
        '/(\bUNION\b.*\bSELECT\b)/i',
        '/(\bSELECT\b.*\bFROM\b)/i',
        '/(\bINSERT\b.*\bINTO\b)/i',
        '/(\bDELETE\b.*\bFROM\b)/i',
        '/(\bUPDATE\b.*\bSET\b)/i',
        '/(\bDROP\b.*\bTABLE\b)/i',
        '/(\bEXEC\b|\bEXECUTE\b)/i',
        '/(--|\#|\/\*|\*\/)/i',
        '/(\bOR\b.*=.*)/i',
        '/(\bAND\b.*=.*)/i',
        "/(';|\")/i",
    ];

    /**
     * Patterns that indicate potential XSS attempts
     */
    private const XSS_PATTERNS = [
        '/<script\b/i',
        '/<iframe\b/i',
        '/javascript:/i',
        '/onerror\s*=/i',
        '/onload\s*=/i',
        '/onclick\s*=/i',
        '/<embed\b/i',
        '/<object\b/i',
        '/eval\s*\(/i',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->detectSqlInjection($request);
        $this->detectXss($request);
        $this->detectExcessiveRequests($request);

        return $next($request);
    }

    /**
     * Detect potential SQL injection attempts
     */
    private function detectSqlInjection(Request $request): void
    {
        $inputs = $request->all();
        
        foreach ($inputs as $key => $value) {
            if (is_string($value)) {
                foreach (self::SQL_INJECTION_PATTERNS as $pattern) {
                    if (preg_match($pattern, $value)) {
                        Log::critical('Potential SQL injection detected', [
                            'user_id' => Auth::id(),
                            'ip' => $request->ip(),
                            'url' => $request->fullUrl(),
                            'user_agent' => $request->userAgent(),
                            'input_field' => $key,
                            'pattern_matched' => $pattern,
                            'value' => substr($value, 0, 200),
                        ]);
                        break;
                    }
                }
            }
        }
    }

    /**
     * Detect potential XSS attempts
     */
    private function detectXss(Request $request): void
    {
        $inputs = $request->all();
        
        foreach ($inputs as $key => $value) {
            if (is_string($value)) {
                foreach (self::XSS_PATTERNS as $pattern) {
                    if (preg_match($pattern, $value)) {
                        Log::critical('Potential XSS attack detected', [
                            'user_id' => Auth::id(),
                            'ip' => $request->ip(),
                            'url' => $request->fullUrl(),
                            'user_agent' => $request->userAgent(),
                            'input_field' => $key,
                            'pattern_matched' => $pattern,
                            'value' => substr($value, 0, 200),
                        ]);
                        break;
                    }
                }
            }
        }
    }

    /**
     * Detect excessive requests from single IP
     */
    private function detectExcessiveRequests(Request $request): void
    {
        $cacheKey = 'request_count_' . $request->ip();
        $count = cache()->get($cacheKey, 0);
        
        if ($count > 200) { // More than 200 requests per minute
            Log::warning('Excessive requests detected', [
                'ip' => $request->ip(),
                'count' => $count,
                'url' => $request->fullUrl(),
                'user_agent' => $request->userAgent(),
            ]);
        }
        
        cache()->put($cacheKey, $count + 1, now()->addMinute());
    }
}
