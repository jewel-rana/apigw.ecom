<?php

namespace Modules\Blog\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Blog\Entities\Blog;
use Modules\Blog\Services\BlogService;

class BlogController extends Controller
{
    private BlogService $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    public function index(Request $request)
    {
        return $this->blogService->index($request);
    }

    public function store(Request $request)
    {
        return $this->blogService->create($request);
    }

    public function show(Blog $blog)
    {
        return response()->success($blog->format);
    }

    public function update(Request $request, Blog $blog)
    {
        return $this->blogService->update($request, $blog);
    }

    public function destroy(Blog $blog)
    {
        return $this->blogService->delete($blog);
    }
}
