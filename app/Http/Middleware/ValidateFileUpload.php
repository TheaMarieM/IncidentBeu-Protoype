<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ValidateFileUpload
{
    /**
     * Allowed MIME types for uploads
     */
    private const ALLOWED_MIMES = [
        'application/pdf',
        'image/jpeg',
        'image/jpg',
        'image/png',
    ];

    /**
     * Maximum file size in bytes (5MB)
     */
    private const MAX_FILE_SIZE = 5242880;

    /**
     * Suspicious file patterns that might indicate malware
     */
    private const SUSPICIOUS_PATTERNS = [
        '<?php',
        '<?=',
        '<script',
        'javascript:',
        'onclick',
        'onerror',
        'onload',
        '<iframe',
        'eval(',
        'base64_decode',
        'exec(',
        'shell_exec',
        'system(',
        'passthru(',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasFile('narrative_files')) {
            $files = $request->file('narrative_files');
            
            if (!is_array($files)) {
                $files = [$files];
            }

            foreach ($files as $file) {
                if ($file === null) {
                    continue;
                }

                // Validate file size
                if ($file->getSize() > self::MAX_FILE_SIZE) {
                    Log::warning('File upload blocked: File too large', [
                        'size' => $file->getSize(),
                        'user_id' => Auth::id(),
                        'ip' => $request->ip(),
                    ]);
                    
                    return back()->withErrors([
                        'narrative_files' => 'File is too large. Maximum size is 5MB.'
                    ])->withInput();
                }

                // Validate MIME type
                if (!in_array($file->getMimeType(), self::ALLOWED_MIMES)) {
                    Log::warning('File upload blocked: Invalid MIME type', [
                        'mime' => $file->getMimeType(),
                        'user_id' => Auth::id(),
                        'ip' => $request->ip(),
                    ]);
                    
                    return back()->withErrors([
                        'narrative_files' => 'Invalid file type. Only PDF, JPG, JPEG, and PNG files are allowed.'
                    ])->withInput();
                }

                // Validate file extension
                $extension = strtolower($file->getClientOriginalExtension());
                if (!in_array($extension, ['pdf', 'jpg', 'jpeg', 'png'])) {
                    Log::warning('File upload blocked: Invalid extension', [
                        'extension' => $extension,
                        'user_id' => Auth::id(),
                        'ip' => $request->ip(),
                    ]);
                    
                    return back()->withErrors([
                        'narrative_files' => 'Invalid file extension.'
                    ])->withInput();
                }

                // Scan file content for malicious patterns
                $content = file_get_contents($file->getRealPath());
                foreach (self::SUSPICIOUS_PATTERNS as $pattern) {
                    if (stripos($content, $pattern) !== false) {
                        Log::critical('Potential malware upload blocked', [
                            'pattern_found' => $pattern,
                            'filename' => $file->getClientOriginalName(),
                            'user_id' => Auth::id(),
                            'ip' => $request->ip(),
                            'user_agent' => $request->userAgent(),
                        ]);
                        
                        return back()->withErrors([
                            'narrative_files' => 'File contains suspicious content and has been blocked for security reasons.'
                        ])->withInput();
                    }
                }

                // Additional check for double extensions (e.g., file.php.jpg)
                $filename = $file->getClientOriginalName();
                if (substr_count($filename, '.') > 1) {
                    Log::warning('File upload blocked: Double extension detected', [
                        'filename' => $filename,
                        'user_id' => Auth::id(),
                        'ip' => $request->ip(),
                    ]);
                    
                    return back()->withErrors([
                        'narrative_files' => 'File name contains multiple extensions and has been blocked.'
                    ])->withInput();
                }
            }
        }

        return $next($request);
    }
}
