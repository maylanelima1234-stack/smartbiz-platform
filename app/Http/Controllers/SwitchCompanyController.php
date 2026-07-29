<?php

namespace App\Http\Controllers;

use App\Core\Context\Contracts\CurrentCompanyResolverContract;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SwitchCompanyController extends Controller
{
    public function __invoke(
        Request $request,
        Company $company,
        CurrentCompanyResolverContract $resolver
    ): RedirectResponse {
        $resolver->switch($request->user(), $company);

        return redirect()
            ->route('companies.workspace', $company)
            ->with('success', 'Empresa ativa alterada com sucesso.');
    }
}
