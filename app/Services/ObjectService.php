<?php

namespace App\Services;

use App\Models\Objecto;
use App\Models\ObjectShareLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ObjectService
{
    public function checkLink(string $token)
    {
        $link = ObjectShareLink::with('object')->where('token', $token)->firstOrFail();
        if ($link->expires_at <= now() && $link->expires_at != null) {
            return redirect()->route('login')->with('error', 'El enlace ha expirado.');
        } elseif ($link->revoked_at != null) {
            return redirect()->route('login')->with('error', 'El enlace ha sido revocado.');
        }

        return $link;
    }

    public function viewFile(Objecto $objecto, Request $request)
    {
        $path = $objecto->storage_path ?? $objecto->object_key;

        $response = Storage::disk($objecto->storage_disk)->response($path);

        $response->setEtag($objecto->checksum);
        $response->setPrivate();
        $response->setMaxAge(31536000);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->headers->addCacheControlDirective('immutable', true);

        if ($response->isNotModified($request)) {
            return $response;
        }

        return $response;
    }
}
