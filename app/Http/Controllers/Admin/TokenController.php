<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTokenRequest;
use App\Http\Requests\StoreTokenRequest;
use App\Http\Requests\UpdateTokenRequest;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Token;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('token_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tokens = Token::with(['clinic', 'patient', 'doctor'])->get();

        return view('admin.tokens.index', compact('tokens'));
    }

    public function create()
    {
        abort_if(Gate::denies('token_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clinics = Clinic::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $patients = Patient::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $doctors = Doctor::pluck('mobile_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.tokens.create', compact('clinics', 'doctors', 'patients'));
    }

    public function store(StoreTokenRequest $request)
    {
        $token = Token::create($request->all());

        return redirect()->route('admin.tokens.index');
    }

    public function edit(Token $token)
    {
        abort_if(Gate::denies('token_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clinics = Clinic::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $patients = Patient::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $doctors = Doctor::pluck('mobile_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $token->load('clinic', 'patient', 'doctor');

        return view('admin.tokens.edit', compact('clinics', 'doctors', 'patients', 'token'));
    }

    public function update(UpdateTokenRequest $request, Token $token)
    {
        $token->update($request->all());

        return redirect()->route('admin.tokens.index');
    }

    public function show(Token $token)
    {
        abort_if(Gate::denies('token_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $token->load('clinic', 'patient', 'doctor');

        return view('admin.tokens.show', compact('token'));
    }

    public function destroy(Token $token)
    {
        abort_if(Gate::denies('token_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $token->delete();

        return back();
    }

    public function massDestroy(MassDestroyTokenRequest $request)
    {
        $tokens = Token::find(request('ids'));

        foreach ($tokens as $token) {
            $token->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
