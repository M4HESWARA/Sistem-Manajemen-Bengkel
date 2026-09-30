<?php

declare(strict_types=1);

/**
 * Vercel entrypoint for the vercel-php runtime.
 *
 * The runtime boots `php -S` with this file as the router and returns a single
 * lambda with no static output files, so Vercel never serves public/ assets by
 * itself. We therefore stream them here and hand everything else to Laravel.
 */
const STATIC_MIME_TYPES = [
    'avif' => 'image/avif',
    'css' => 'text/css; charset=utf-8',
    'eot' => 'application/vnd.ms-fontobject',
    'gif' => 'image/gif',
    'ico' => 'image/x-icon',
    'jpeg' => 'image/jpeg',
    'jpg' => 'image/jpeg',
    'json' => 'application/json',
    'map' => 'application/json',
    'mjs' => 'text/javascript; charset=utf-8',
    'otf' => 'font/otf',
    'pdf' => 'application/pdf',
    'png' => 'image/png',
    'svg' => 'image/svg+xml',
    'txt' => 'text/plain; charset=utf-8',
    'ttf' => 'font/ttf',
    'webmanifest' => 'application/manifest+json',
    'webp' => 'image/webp',
    'woff' => 'font/woff',
    'woff2' => 'font/woff2',
    'xml' => 'application/xml',
];

const NEVER_SERVE_AS_STATIC = ['php', 'phtml', 'phar'];

/**
 * Streams a file from public/ with a correct content type and cache policy.
 */
function serveStaticFile(string $file, string $method, string $relativePath): void
{
    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $mimeType = STATIC_MIME_TYPES[$extension] ?? 'application/octet-stream';

    $modifiedAt = (int) filemtime($file);
    $etag = '"' . md5($file . $modifiedAt . (string) filesize($file)) . '"';
    $lastModified = gmdate('D, d M Y H:i:s', $modifiedAt) . ' GMT';

    // Vite fingerprints asset filenames, so anything under build/ is immutable.
    $isFingerprinted = str_starts_with($relativePath, 'build/');

    header('Content-Type: ' . $mimeType);
    header('Cache-Control: ' . ($isFingerprinted
        ? 'public, max-age=31536000, immutable'
        : 'public, max-age=3600'));
    header('ETag: ' . $etag);
    header('Last-Modified: ' . $lastModified);
    header('X-Content-Type-Options: nosniff');

    $ifNoneMatch = trim($_SERVER['HTTP_IF_NONE_MATCH'] ?? '');

    if ($ifNoneMatch !== '' && ($ifNoneMatch === '*' || str_contains($ifNoneMatch, $etag))) {
        http_response_code(304);

        return;
    }

    header('Content-Length: ' . (string) filesize($file));
    http_response_code(200);

    if ($method === 'HEAD') {
        return;
    }

    readfile($file);
}

$publicPath = realpath(__DIR__ . '/../public');

if ($publicPath === false) {
    http_response_code(500);
    exit('Public directory could not be resolved.');
}

$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

if ($method === 'GET' || $method === 'HEAD') {
    $relativePath = ltrim(rawurldecode(is_string($requestPath) ? $requestPath : '/'), '/');
    $extension = strtolower(pathinfo($relativePath, PATHINFO_EXTENSION));
    $isServable = $relativePath !== ''
        && $relativePath[0] !== '.'
        && ! in_array($extension, NEVER_SERVE_AS_STATIC, true);

    if ($isServable) {
        $candidate = realpath($publicPath . '/' . $relativePath);

        // Containment check keeps ../ traversal and symlink escapes out of public/.
        if ($candidate !== false
            && is_file($candidate)
            && str_starts_with($candidate, $publicPath . DIRECTORY_SEPARATOR)
        ) {
            serveStaticFile($candidate, $method, $relativePath);

            return;
        }
    }
}

require $publicPath . '/index.php';
