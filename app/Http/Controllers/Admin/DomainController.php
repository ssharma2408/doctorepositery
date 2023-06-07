<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDomainRequest;
use App\Http\Requests\StoreDomainRequest;
use App\Http\Requests\UpdateDomainRequest;
use App\Models\Domain;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DomainController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('domain_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domains = Domain::all();

        return view('admin.domains.index', compact('domains'));
    }

    public function create()
    {
        abort_if(Gate::denies('domain_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.domains.create');
    }

    public function store(StoreDomainRequest $request)
    {
        $domain = Domain::create($request->all());

        return redirect()->route('admin.domains.index');
    }

    public function edit(Domain $domain)
    {
        abort_if(Gate::denies('domain_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.domains.edit', compact('domain'));
    }

    public function update(UpdateDomainRequest $request, Domain $domain)
    {
        $domain->update($request->all());

        return redirect()->route('admin.domains.index');
    }

    public function show(Domain $domain)
    {
        abort_if(Gate::denies('domain_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.domains.show', compact('domain'));
    }

    public function destroy(Domain $domain)
    {
        abort_if(Gate::denies('domain_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $domain->delete();

        return back();
    }

    public function massDestroy(MassDestroyDomainRequest $request)
    {
        $domains = Domain::find(request('ids'));

        foreach ($domains as $domain) {
            $domain->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
