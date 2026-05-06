<?php

namespace App\Http\Controllers;

use App\Models\ObjectDownloadEvent;
use App\Models\Objecto;
use App\Models\ObjectShareLink;
use App\Services\ObjectService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class DownloadEventController extends Controller
{
    public function downloadShared(ObjectShareLink $link, ObjectService $objectService)
    {
        $link = $objectService->checkLink($link->token); // retorna un objeto ObjectShareLink
        if ($link->download_limit != null) {
            return redirect()->back()->with('error', 'El enlace ha alcanzado su límite de descargas.');
        } elseif ($link->download_limit <= $link->download_count) {
            return redirect()->back()->with('error', 'El enlace ha alcanzado su límite de descargas.');
        } elseif ($link->permission != 'r') {
            return redirect()->back()->with('error', 'El enlace no tiene permiso de descarga.');
        }
        $link->increment('download_count');
        ObjectDownloadEvent::create([
            'object_id' => $link->object_id,
            'share_link_id' => $link->id,
            'user_id' => auth()->id() ?? null,
            'ip_address' => request()->ip(),
            'country_code' => request()->getLocale(),
            'user_agent' => request()->userAgent(),
            'referrer' => request()->header('Referer'),

        ]);

        return Storage::disk('local')->download($link->object->storage_path);
    }

    public function downloadOwn(Objecto $objecto)
    {
        Gate::authorize('update', $objecto);
        ObjectDownloadEvent::create([
            'object_id' => $objecto->id,
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'country_code' => request()->getLocale(),
            'user_agent' => request()->userAgent(),
            'referrer' => request()->header('Referer'),
        ]);

        return Storage::disk('local')->download($objecto->storage_path);
    }
}
