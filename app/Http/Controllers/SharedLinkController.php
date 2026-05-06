<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSharedLinkRequest;
use App\Models\Objecto;
use App\Models\ObjectShareLink;
use App\Services\ObjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SharedLinkController extends Controller
{
    public function store(StoreSharedLinkRequest $request, Objecto $objecto)
    {
        Gate::authorize('createLink', $objecto, $objecto->bucket());
        if ($objecto->bucket->visibility === 'pr') {
            return redirect()->back()->with('error', 'No se puede crear un enlace para un bucket privado.');
        }
        $data = $request->validated();
        try {
            $data['token'] = Str::random(32);
            $data['url'] = 'http://127.0.0.1:8000/shared/'.$data['token'];
            ObjectShareLink::create($data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear el enlace.');
        }

        return redirect()->back()->with('success', 'Enlace creado correctamente.');
    }

    public function processToken(string $token, ObjectService $objectService)
    {
        $link = $objectService->checkLink($token);
        $object = Storage::disk('local')->url($link->object->storage_path);

        return view('object.shared', compact('link', 'object'));
    }

    public function viewFile(string $token, Request $request)
    {
        $link = ObjectShareLink::with('object')
            ->where('visibility', 'pu')
            ->where('token', $token)
            ->firstOrFail();
        $path = $link->object->storage_path ?? $link->object->object_key;

        $response = Storage::disk('local')->response($path);

        $response->setEtag($link->object->checksum);
        $response->setPublic();
        $response->setMaxAge(31536000); // Cache por 1 año
        $response->headers->addCacheControlDirective('must-revalidate', true);

        abort_if($link->object->visibility === 'pr', 403, 'No tienes permiso para ver este objeto.');

        if ($response->isNotModified($request)) {
            return $response;
        }

        return $response;
    }

    public function revoke(ObjectShareLink $link)
    {
        $link->update([
            'revoked_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Enlace revocado correctamente.');
    }
}
