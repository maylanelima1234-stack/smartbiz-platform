<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Models\SmartNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search'));

        $companies = Company::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('trade_name', 'like', "%{$search}%")
                        ->orWhere('cnpj', 'like', "%{$search}%")
                        ->orWhere('owner', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('companies.index', compact('companies', 'search'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(StoreCompanyRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = $this->uniqueSlug($data['trade_name'] ?: $data['name']);
        $data['created_by'] = auth()->id();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('companies/logos', 'public');
        }

        $company = Company::create($data);

        SmartNotification::pushFor(
            $request->user(),
            'Empresa cadastrada',
            ($company->trade_name ?: $company->name) . ' foi adicionada à plataforma.',
            'company',
            route('companies.show', $company),
            'E'
        );

        return redirect()
            ->route('companies.index')
            ->with('success', 'Empresa cadastrada com sucesso!');
    }

    public function show(Company $company)
    {
        return view('companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $data = $request->validated();

        if (empty($company->slug)) {
            $data['slug'] = $this->uniqueSlug($data['trade_name'] ?: $data['name'], $company->id);
        }

        if ($request->hasFile('logo')) {
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }

            $data['logo'] = $request->file('logo')->store('companies/logos', 'public');
        }

        $company->update($data);

        return redirect()
            ->route('companies.index')
            ->with('success', 'Empresa atualizada com sucesso!');
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()
            ->route('companies.index')
            ->with('success', 'Empresa arquivada com sucesso!');
    }

    private function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = Str::slug($base) ?: 'empresa';
        $original = $slug;
        $counter = 1;

        while (Company::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $original . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
