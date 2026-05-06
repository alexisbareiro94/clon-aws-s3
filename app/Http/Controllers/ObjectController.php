<?php

namespace App\Http\Controllers;

use App\Http\Requests\ObjectStoreRequest;
use App\Http\Requests\UpdateObjectRequest;
use App\Jobs\ExtractObjectMetadata;
use App\Models\Bucket;
use App\Models\Objecto;
use App\Services\ObjectService;
// use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ObjectController extends Controller
{
    public function index(Bucket $bucket, Request $request)
    {
        Gate::authorize('create', $bucket);

        return $request->expectsJson() ?
            response()->json($bucket->objectos()->with('bucket')->get()) :
            view('object.upload', compact('bucket'));
    }

    public function show(Bucket $bucket, Objecto $objecto, Request $request)
    {
        Gate::authorize('view', $objecto);

        if ($request->wantsJson()) {
            return response()->json($objecto);
        }

        return view('object.show', ['object' => $objecto]);
    }

    public function viewFile(Bucket $bucket, Objecto $objecto, Request $request, ObjectService $objectService)
    {
        Gate::authorize('view', $objecto);

        return $objectService->viewFile($objecto, $request);
    }

    public function update(UpdateObjectRequest $request, $bucket, Objecto $objecto)
    {
        Gate::authorize('update', $objecto);
        $data = $request->validated();
        $objecto->update($data);

        return $request->wantsJson() ?
            response()->json([
                'success' => true,
                'message' => 'Objeto actualizado correctamente',
            ]) :
            redirect()->route('object.show', [$objecto->bucket->slug, $objecto])->with('success', 'Objeto actualizado correctamente.');
    }

    public function store(ObjectStoreRequest $request, Bucket $bucket)
    {
        Gate::authorize('create', $bucket);

        $data = $request->validated();
        $files = $request->file('files');

        if ($files instanceof UploadedFile) {
            $files = [$files];
        }

        $uploadedObjects = collect();

        foreach ($files as $file) {
            $originalName = $file->getClientOriginalName();

            $storageDisk = 'local';
            $directory = $bucket->slug;

            $storagePath = $file->store($directory, $storageDisk);

            $objecto = Objecto::create([
                'bucket_id' => $bucket->id,
                'user_id' => auth()->id(),
                'object_key' => $originalName,
                'original_name' => $originalName,
                'storage_disk' => $storageDisk,
                'storage_path' => $storagePath,
                'mime_type' => $file->getClientMimeType(),
                'size_bytes' => $file->getSize(),
                'checksum' => md5_file($file->getRealPath()),
                'visibility' => $data['visibility'],
            ]);
            $objecto->update([
                'object_key' => $objecto->checksum.'.'.$file->getClientOriginalExtension(),
            ]);

            $uploadedObjects->push($objecto);
        }

        if ($uploadedObjects->isNotEmpty()) {
            ExtractObjectMetadata::dispatch($uploadedObjects);
        }

        return $request->expectsJson() ?
            response()->json([
                'success' => true,
                'message' => 'Archivos cargados correctamente.',
            ]) :
            redirect()->route('bucket.show', $bucket)->with('success', 'Archivos cargados correctamente.');
    }

    public function destroy(Objecto $objecto)
    {
        Gate::authorize('deleteObject', $objecto);
        Storage::disk($objecto->storage_disk)->delete($objecto->storage_path);
        $objecto->delete();

        return redirect()->route('bucket.show', $objecto->bucket)->with('success', 'Objeto eliminado correctamente.');
    }
}
