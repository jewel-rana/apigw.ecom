<?php

namespace Modules\Media\Http\Controllers;

use http\Env\Response;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Category\CategoryService;
use Modules\Category\Entities\Category;
use Modules\Category\Http\Requests\CategoryCreateRequest;
use Modules\Category\Http\Requests\CategoryUpdateRequest;
use Modules\Media\Entities\Media;
use Modules\Media\Http\Requests\MediaCreateRequest;
use Modules\Media\Http\Requests\MediaUpdateRequest;
use Modules\Media\MediaService;
use Modules\Operator\Entities\Operator;
use Yajra\DataTables\Facades\DataTables;

class MediaController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
    private MediaService $medias;

    public function __construct(MediaService $medias)
    {
        $this->medias = $medias;
    }

    public function index( Request $request)
    {
        if($request->wantsJson()) {
            $medias = Media::select(['id', 'original_name', 'attachment', 'type', 'size', 'dimension']);

            return Datatables::of($medias)
                ->addColumn('thumbnail', function($media) {
                    $thumb = ($media->attachment) ? $media->attachment : 'default/brand.jpg';
                    return "<img src='" . asset($thumb) . "' class='table-avatar' />";
                })
                ->addColumn('action', function($media) {
                    if(\App\Helpers\CommonHelper::hasPermission(['media-action'])) {
                        return "<form action='" . route('media.destroy', $media->id) . "' method='POST'>
                        " . csrf_field() . method_field('DELETE') . "
                        <button type='submit' class='btn btn-danger'>Delete</button>
</form>";
                    }
                    return '';
                })
                ->rawColumns(['action', 'thumbnail'])->addIndexColumn()
                ->make(true);
        }
        return view('media::index')->withTitle('Medias');
    }

    public function create(): Renderable
    {
        return view('media::create')->withTitle('Add new media');
    }

    public function store(MediaCreateRequest $request): JsonResponse
    {
        $data = ['success' => false, 'message' => 'Upload failed'];
        try {
            if (is_executable($request->file('file'))) {
                throw new \Exception('File not allowed to upload');
            }
            $media = $this->medias->handle($request->file('qqfile'));
            $data['success'] = true;
            $data['newUuid'] = $media->id;
            $data['name'] = $media->ratio . ' - ' . $media->original_name;
            $data['size'] = (int) $media->size * 1024;
        } catch (\Throwable $exception) {
            $data['message'] = $exception->getMessage();
        }

        return response()->json($data);
    }

    public function show(Media $media): Renderable
    {
        return view('media::show', compact('media'))->withTitle('Show media');
    }

    public function edit(Media $media): Renderable
    {
        return view('media::edit', compact('media'))->withTitle('Update media');
    }

    public function update(MediaUpdateRequest $request, $id): RedirectResponse
    {
        try {
            $this->medias->update($request->validated(), $id);
        } catch (\Throwable $exception) {
            session()->flash('error', $exception->getMessage());
        }

        return redirect()->route('media.index');
    }

    public function destroy(Media $media): RedirectResponse
    {
        if(!auth()->user()->can('media-delete'))
            session()->flash('error', 'You have no right to delete media');

        $media->delete();
        return redirect()->back();
    }

    public function delete(Request $request): JsonResponse
    {
        try {
            return response()->json(['success' => true]);
        } catch (\Exception $exception)
        {
            return response()->json(['success' => false]);
        }
    }

    public function jqUpload(Request $request): JsonResponse
    {
        $data = ['status' => false, 'message' => 'Could not upload'];
        try {
            $this->medias->handle($request->file('attachment'));
        } catch (\Throwable $exception) {
            $data['message'] = $exception->getMessage();
        }
        return response()->json($data);
    }

    public function callAction($method, $parameters)
    {
        if (!in_array($method, ['jqUpload', 'delete'])) {
            $this->authorize($method, Media::class);
        }
        return parent::callAction($method, $parameters);
    }
}
