<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyContentTagRequest;
use App\Http\Requests\StoreContentTagRequest;
use App\Http\Requests\UpdateContentTagRequest;
use App\Models\Clinic;
use App\Models\ContentTag;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentTagController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('content_tag_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $contentTags = ContentTag::with(['clinic_ids'])->get();

        return view('admin.contentTags.index', compact('contentTags'));
    }

    public function create()
    {
        abort_if(Gate::denies('content_tag_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clinic_ids = Clinic::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.contentTags.create', compact('clinic_ids'));
    }

    public function store(StoreContentTagRequest $request)
    {
        $contentTag = ContentTag::create($request->all());

        return redirect()->route('admin.content-tags.index');
    }

    public function edit(ContentTag $contentTag)
    {
        abort_if(Gate::denies('content_tag_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clinic_ids = Clinic::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $contentTag->load('clinic_ids');

        return view('admin.contentTags.edit', compact('clinic_ids', 'contentTag'));
    }

    public function update(UpdateContentTagRequest $request, ContentTag $contentTag)
    {
        $contentTag->update($request->all());

        return redirect()->route('admin.content-tags.index');
    }

    public function show(ContentTag $contentTag)
    {
        abort_if(Gate::denies('content_tag_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $contentTag->load('clinic_ids');

        return view('admin.contentTags.show', compact('contentTag'));
    }

    public function destroy(ContentTag $contentTag)
    {
        abort_if(Gate::denies('content_tag_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $contentTag->delete();

        return back();
    }

    public function massDestroy(MassDestroyContentTagRequest $request)
    {
        $contentTags = ContentTag::find(request('ids'));

        foreach ($contentTags as $contentTag) {
            $contentTag->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
