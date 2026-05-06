<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateBucketRequest;
use App\Jobs\DeleteBucketFiles;
use App\Models\Bucket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class BucketController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Bucket::class);

        $cacheKey = 'buckets_user_'.auth()->id();

        $buckets = Cache::remember($cacheKey, 300, function () {
            return Bucket::where('user_id', auth()->id())->get();
        });

        return $request->expectsJson() ?
            response()->json($buckets) :
            view('bucket.index', [
                'buckets' => $buckets,
            ]);
    }

    public function create()
    {
        return view('bucket.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Bucket::class);
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'visibility' => ['required', 'string', 'in:pr,pu'],
        ]);
        Cache::forget('buckets_user_'.auth()->id());

        $bucket = Bucket::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'visibility' => $validated['visibility'],
        ]);

        return $request->wantsJson() ?
            response()->json([
                'success' => true,
                'message' => "Bucket \"{$bucket->name}\" creado con éxito.",
            ]) :
            redirect()->route('bucket.show', $bucket)
                ->with('success', "Bucket \"{$bucket->name}\" creado con éxito.");
    }

    // Route::get('/{bucket:slug}'
    public function show(Bucket $bucket, Request $request)
    {
        Gate::authorize('view', $bucket);
        $q = $request->q ?? '';
        $query = $bucket->objectos()
            ->with(['shareLinks', 'bucket'])
            ->where(function($query) use ($q) {
                $query->where('original_name', 'ilike', "%{$q}%")
                    ->orWhere('mime_type', 'ilike', "%{$q}%");
            });

        $objects = $query->latest()->paginate(20)->withQueryString();
        $total = $objects->total();
        $totalBytes = $bucket->objectos()->sum('size_bytes');

        if ($request->ajax()) {
            $html = view('bucket.partials.objects-table', compact('objects', 'bucket'))->render();

            return response()->json([
                'success' => true,
                'objects' => $objects,
                'html' => $html,
            ]);
        }

        return $request->wantsJson() ?
            response()->json([
                'success' => true,
                'bucket' => $bucket,
                'objects' => $objects,
                'total' => $total,
                'total_bytes' => $totalBytes,
            ]) :
            view('bucket.show', [
                'bucket' => $bucket,
                'objects' => $objects,
                'totalBytes' => $totalBytes,
            ]);
    }

    public function settingsView(Bucket $bucket)
    {
        Gate::authorize('update', $bucket);

        return view('bucket.settings', compact('bucket'));
    }

    public function update(UpdateBucketRequest $request, Bucket $bucket)
    {
        Gate::authorize('update', $bucket);
        $data = $request->validated();
        $bucket->update($data);

        return $request->wantsJson() ?
            response()->json([
                'success' => true,
                'message' => 'Bucket actualizado correctamente',
            ]) :
            redirect()->back()->with('success', 'Bucket actualizado correctamente');
    }

    public function destroy(Bucket $bucket, Request $request)
    {
        Gate::authorize('delete', $bucket);
        try {
            $request->validate([
                'name' => 'required|string|in:'.$bucket->name,
                'confirm' => 'required|accepted',
            ]);
        } catch (\Exception $e) {
            return $request->wantsJson() ?
                response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422)
                : redirect()->back()->with('error', 'Debes confirmar la eliminación escribiendo el nombre y activando el interruptor.');
        }
        $name = $bucket->name;
        $bucket->delete();
        Cache::forget('buckets_user_'.auth()->id());
        DeleteBucketFiles::dispatch($bucket->slug);

        return $request->wantsJson() ?
            response()->json([
                'success' => true,
                'message' => "Bucket \"{$name}\" eliminado.",
            ])
            : redirect()->route('bucket.index')
                ->with('success', "Bucket \"{$name}\" eliminado.");
    }
}
