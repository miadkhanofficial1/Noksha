<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class AdminLogController extends Controller
{
    /**
     * Display live system logs and diagnostics.
     */
    public function index(Request $request): View
    {
        $logPath = storage_path('logs/laravel.log');
        $logs = [];

        if (File::exists($logPath)) {
            $fileContent = File::get($logPath);
            // Grab last ~150KB to avoid excessive memory on giant logs
            if (strlen($fileContent) > 200000) {
                $fileContent = substr($fileContent, -200000);
            }

            // Regex pattern for Laravel standard logs: [YYYY-MM-DD HH:MM:SS] environment.LEVEL: message
            $pattern = '/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]\s+([\w\.-]+)\.([A-Z]+):\s+(.*?)(?=\n\[\d{4}-\d{2}-\d{2}|\z)/s';
            if (preg_match_all($pattern, $fileContent, $matches, PREG_SET_ORDER)) {
                foreach (array_reverse($matches) as $match) {
                    $logs[] = [
                        'date' => $match[1],
                        'env' => $match[2],
                        'level' => $match[3],
                        'message' => trim($match[4]),
                    ];
                }
            } else {
                // Fallback raw lines
                $lines = array_reverse(array_filter(explode("\n", $fileContent)));
                foreach (array_slice($lines, 0, 100) as $line) {
                    $logs[] = [
                        'date' => now()->format('Y-m-d H:i:s'),
                        'env' => 'local',
                        'level' => 'INFO',
                        'message' => $line,
                    ];
                }
            }
        }

        // Limit to 100 entries
        $logs = array_slice($logs, 0, 100);

        // System Diagnostics
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'environment' => app()->environment(),
            'debug_mode' => config('app.debug') ? 'Enabled' : 'Disabled',
            'database_driver' => config('database.default'),
            'log_file_size' => File::exists($logPath) ? round(File::size($logPath) / 1024, 2) . ' KB' : '0 KB',
            'memory_usage' => round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB',
        ];

        return view('admin.logs.index', compact('logs', 'systemInfo'));
    }

    /**
     * Clear the current laravel.log file.
     */
    public function clear(): RedirectResponse
    {
        $logPath = storage_path('logs/laravel.log');
        if (File::exists($logPath)) {
            File::put($logPath, '');
        }

        return redirect()->back()
            ->with('success', '🧹 System logs have been wiped successfully.');
    }
}
