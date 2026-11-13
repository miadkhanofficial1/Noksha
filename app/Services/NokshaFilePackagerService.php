<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;
use RuntimeException;

class NokshaFilePackagerService
{
    /**
     * Sanitize a title into Title_Case with underscores (e.g. "Modern_Logo_Design").
     */
    public static function sanitizeTitle(string $title): string
    {
        $clean = preg_replace('/[^a-zA-Z0-9\s]/', ' ', $title);
        $words = array_filter(explode(' ', (string) $clean));
        $titleCased = array_map(fn($w) => ucfirst(strtolower($w)), $words);
        $result = implode('_', $titleCased);

        return !empty($result) ? $result : 'Noksha_Asset';
    }

    /**
     * Sanitize a creator username for archive naming.
     */
    public static function sanitizeUsername(string $username): string
    {
        $clean = preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower(trim($username)));
        $clean = trim($clean, '_');

        return !empty($clean) ? $clean : 'creator';
    }

    /**
     * Store and auto-rename structured files for services or marketplace resources.
     *
     * @param string $baseTitle Title used as base for renaming (e.g. "Modern Logo Design")
     * @param array<int, UploadedFile|string> $previewImages Up to 5 preview images
     * @param UploadedFile|string|null $sourceFile Main source file (.psd, .ai, .fig, .eps, .svg, .zip)
     * @param UploadedFile|string|null $instructionPdf Optional instruction / guideline PDF
     * @param string $storageFolder Target base directory in public storage ('deliveries', 'resources', etc.)
     * @return array Manifest of stored files, metadata, and ordered paths
     */
    public static function storeStructuredFiles(
        string $baseTitle,
        array $previewImages,
        $sourceFile = null,
        $instructionPdf = null,
        string $storageFolder = 'deliveries'
    ): array {
        $sanitizedTitle = static::sanitizeTitle($baseTitle);
        $batchId = 'batch_' . date('Ymd_His') . '_' . Str::lower(Str::random(6));
        $batchDir = trim($storageFolder, '/') . '/' . $batchId;

        // Ensure storage directory exists
        $absoluteBatchDir = storage_path('app/public/' . $batchDir);
        if (!File::isDirectory($absoluteBatchDir)) {
            File::makeDirectory($absoluteBatchDir, 0755, true, true);
        }

        $storedPreviews = [];
        $storedSource = null;
        $storedPdf = null;
        $manifest = [];

        // 1. Process and sequentially rename Preview Images: Title_1.ext, Title_2.ext, etc.
        $previewIndex = 1;
        foreach ($previewImages as $previewItem) {
            if ($previewIndex > 5) {
                break; // Limit to maximum 5 preview images
            }

            if ($previewItem instanceof UploadedFile) {
                $ext = strtolower($previewItem->getClientOriginalExtension() ?: 'png');
                $targetFileName = "{$sanitizedTitle}_{$previewIndex}.{$ext}";
                $path = Storage::disk('public')->putFileAs($batchDir, $previewItem, $targetFileName);

                $fileData = [
                    'order' => $previewIndex,
                    'type' => 'preview',
                    'name' => $targetFileName,
                    'path' => $path,
                    'url' => asset('storage/' . $path),
                    'size' => $previewItem->getSize(),
                    'mime' => $previewItem->getMimeType(),
                ];
                $storedPreviews[] = $fileData;
                $manifest[] = $fileData;
                $previewIndex++;
            } elseif (is_string($previewItem) && !empty($previewItem)) {
                // String path passed
                $ext = pathinfo($previewItem, PATHINFO_EXTENSION) ?: 'png';
                $targetFileName = "{$sanitizedTitle}_{$previewIndex}.{$ext}";
                $targetPath = $batchDir . '/' . $targetFileName;
                $realSource = static::resolveAbsolutePath($previewItem);

                if ($realSource && file_exists($realSource)) {
                    copy($realSource, storage_path('app/public/' . $targetPath));
                    $fileData = [
                        'order' => $previewIndex,
                        'type' => 'preview',
                        'name' => $targetFileName,
                        'path' => $targetPath,
                        'url' => asset('storage/' . $targetPath),
                        'size' => filesize($realSource),
                        'mime' => mime_content_type($realSource) ?: 'image/' . $ext,
                    ];
                    $storedPreviews[] = $fileData;
                    $manifest[] = $fileData;
                    $previewIndex++;
                }
            }
        }

        // 2. Process and rename Main Source File: Title.ext
        if ($sourceFile instanceof UploadedFile) {
            $ext = strtolower($sourceFile->getClientOriginalExtension() ?: 'zip');
            $targetSourceFileName = "{$sanitizedTitle}.{$ext}";
            $path = Storage::disk('public')->putFileAs($batchDir, $sourceFile, $targetSourceFileName);

            $storedSource = [
                'order' => 10,
                'type' => 'source_file',
                'name' => $targetSourceFileName,
                'path' => $path,
                'url' => asset('storage/' . $path),
                'size' => $sourceFile->getSize(),
                'mime' => $sourceFile->getMimeType(),
            ];
            $manifest[] = $storedSource;
        } elseif (is_string($sourceFile) && !empty($sourceFile)) {
            $ext = pathinfo($sourceFile, PATHINFO_EXTENSION) ?: 'zip';
            $targetSourceFileName = "{$sanitizedTitle}.{$ext}";
            $targetPath = $batchDir . '/' . $targetSourceFileName;
            $realSource = static::resolveAbsolutePath($sourceFile);

            if ($realSource && file_exists($realSource)) {
                copy($realSource, storage_path('app/public/' . $targetPath));
                $storedSource = [
                    'order' => 10,
                    'type' => 'source_file',
                    'name' => $targetSourceFileName,
                    'path' => $targetPath,
                    'url' => asset('storage/' . $targetPath),
                    'size' => filesize($realSource),
                    'mime' => mime_content_type($realSource) ?: 'application/octet-stream',
                ];
                $manifest[] = $storedSource;
            }
        }

        // 3. Process and rename Instruction PDF: Title.pdf
        if ($instructionPdf instanceof UploadedFile) {
            $targetPdfFileName = "{$sanitizedTitle}.pdf";
            $path = Storage::disk('public')->putFileAs($batchDir, $instructionPdf, $targetPdfFileName);

            $storedPdf = [
                'order' => 20,
                'type' => 'instruction_pdf',
                'name' => $targetPdfFileName,
                'path' => $path,
                'url' => asset('storage/' . $path),
                'size' => $instructionPdf->getSize(),
                'mime' => 'application/pdf',
            ];
            $manifest[] = $storedPdf;
        } elseif (is_string($instructionPdf) && !empty($instructionPdf)) {
            $targetPdfFileName = "{$sanitizedTitle}.pdf";
            $targetPath = $batchDir . '/' . $targetPdfFileName;
            $realSource = static::resolveAbsolutePath($instructionPdf);

            if ($realSource && file_exists($realSource)) {
                copy($realSource, storage_path('app/public/' . $targetPath));
                $storedPdf = [
                    'order' => 20,
                    'type' => 'instruction_pdf',
                    'name' => $targetPdfFileName,
                    'path' => $targetPath,
                    'url' => asset('storage/' . $targetPath),
                    'size' => filesize($realSource),
                    'mime' => 'application/pdf',
                ];
                $manifest[] = $storedPdf;
            }
        }

        $orderedPaths = array_map(fn($item) => $item['path'], $manifest);

        return [
            'batch_id' => $batchId,
            'sanitized_title' => $sanitizedTitle,
            'preview_images' => $storedPreviews,
            'preview_image_paths' => array_map(fn($p) => $p['path'], $storedPreviews),
            'source_file' => $storedSource,
            'source_file_path' => $storedSource['path'] ?? null,
            'instruction_pdf' => $storedPdf,
            'instruction_pdf_path' => $storedPdf['path'] ?? null,
            'all_files_ordered' => $orderedPaths,
            'manifest' => $manifest,
        ];
    }

    /**
     * Dynamically bundles files on-the-fly with native ZipArchive in exact serial order:
     * 1) Preview images (Title_1.ext, Title_2.ext...)
     * 2) Main source file (Title.ext)
     * 3) Instruction PDF (Title.pdf)
     *
     * Streams download named strictly: {$sanitizedTitle}_{$creatorUsername}.zip
     * Automatically cleans up temporary zip archive after streaming.
     *
     * @param string $baseTitle
     * @param string $creatorUsername
     * @param array<int|string, string> $filePathsInOrder Array of relative/absolute file paths or key-value [zip_name => path]
     * @return BinaryFileResponse
     */
    public static function streamZipDownload(string $baseTitle, string $creatorUsername, array $filePathsInOrder): BinaryFileResponse
    {
        $sanitizedTitle = static::sanitizeTitle($baseTitle);
        $cleanUsername = static::sanitizeUsername($creatorUsername);
        $downloadFilename = "{$sanitizedTitle}_{$cleanUsername}.zip";

        $tempDir = storage_path('app/temp');
        if (!File::isDirectory($tempDir)) {
            File::makeDirectory($tempDir, 0755, true, true);
        }

        $tempZipPath = $tempDir . '/' . uniqid('noksha_pkg_', true) . '.zip';

        $zip = new ZipArchive();
        $res = $zip->open($tempZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        if ($res !== true) {
            throw new RuntimeException("Could not create dynamic ZIP archive at {$tempZipPath} (Code: {$res})");
        }

        // Segregate and sort files to enforce strict serial order:
        // Previews -> Main Source -> Instruction PDF
        $previews = [];
        $sources = [];
        $instructions = [];
        $customNamed = [];

        foreach ($filePathsInOrder as $key => $filePath) {
            if (empty($filePath)) {
                continue;
            }

            $realPath = static::resolveAbsolutePath($filePath);
            if (!$realPath || !file_exists($realPath)) {
                continue;
            }

            if (is_string($key) && !is_numeric($key)) {
                $customNamed[$key] = $realPath;
                continue;
            }

            $ext = strtolower(pathinfo($realPath, PATHINFO_EXTENSION));

            if (in_array($ext, ['pdf']) && (str_contains(strtolower($realPath), 'instruction') || str_contains(strtolower($realPath), 'guideline'))) {
                $instructions[] = $realPath;
            } elseif (in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'gif'])) {
                $previews[] = $realPath;
            } elseif ($ext === 'pdf' && count($instructions) === 0) {
                // If it's a PDF and not identified as source or preview
                $instructions[] = $realPath;
            } else {
                $sources[] = $realPath;
            }
        }

        // If customNamed entries exist, add them directly
        foreach ($customNamed as $zipEntryName => $realPath) {
            $zip->addFile($realPath, $zipEntryName);
        }

        // 1) Add Preview images in sequence: Title_1.ext, Title_2.ext...
        $imgCounter = 1;
        foreach ($previews as $previewPath) {
            $ext = strtolower(pathinfo($previewPath, PATHINFO_EXTENSION));
            $zipName = "{$sanitizedTitle}_{$imgCounter}.{$ext}";
            $zip->addFile($previewPath, $zipName);
            $imgCounter++;
        }

        // 2) Add Main Source File: Title.ext
        $srcCounter = 1;
        foreach ($sources as $sourcePath) {
            $ext = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));
            $zipName = ($srcCounter === 1) ? "{$sanitizedTitle}.{$ext}" : "{$sanitizedTitle}_source_{$srcCounter}.{$ext}";
            $zip->addFile($sourcePath, $zipName);
            $srcCounter++;
        }

        // 3) Add Instruction PDF: Title.pdf
        $pdfCounter = 1;
        foreach ($instructions as $pdfPath) {
            $zipName = ($pdfCounter === 1) ? "{$sanitizedTitle}.pdf" : "{$sanitizedTitle}_guide_{$pdfCounter}.pdf";
            $zip->addFile($pdfPath, $zipName);
            $pdfCounter++;
        }

        // Fallback: If zip is completely empty (no valid files found), add a readme
        if ($zip->numFiles === 0) {
            $zip->addFromString("README.txt", "Package: {$baseTitle}\nCreator: {$creatorUsername}\nPlatform: Noksha Marketplace\nGenerated on: " . date('Y-m-d H:i:s'));
        }

        $zip->close();

        return response()->download($tempZipPath, $downloadFilename, [
            'Content-Type' => 'application/zip',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0',
            'Pragma' => 'no-cache',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Resolve absolute disk path from various relative formats.
     */
    public static function resolveAbsolutePath(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        if (file_exists($path)) {
            return $path;
        }

        $normalized = ltrim($path, '/\\');

        if (Storage::disk('public')->exists($normalized)) {
            return Storage::disk('public')->path($normalized);
        }

        $publicStoragePath = storage_path('app/public/' . $normalized);
        if (file_exists($publicStoragePath)) {
            return $publicStoragePath;
        }

        $appStoragePath = storage_path('app/' . $normalized);
        if (file_exists($appStoragePath)) {
            return $appStoragePath;
        }

        $publicHtmlPath = public_path($normalized);
        if (file_exists($publicHtmlPath)) {
            return $publicHtmlPath;
        }

        return null;
    }
}
