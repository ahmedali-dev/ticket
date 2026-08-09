<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CompanyController extends Controller
{
    /**
     * Display a listing of companies.
     */
    public function index(): View
    {
        $companies = Company::withCount('users')
            ->latest()
            ->paginate(15);

        return view('companies.index', compact('companies'));
    }

    /**
     * Store a newly created company.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'phone'  => ['required', 'integer'],
            'status' => ['required', 'boolean'],
        ]);

        Company::create($validated);

        return redirect()
            ->route('company.index')
            ->with('success', 'Company created successfully.');
    }

    /**
     * Show the form for editing the specified company.
     */
    public function edit(Company $company): View
    {
        return view('companies.edit', compact('company'));
    }

    /**
     * Update the specified company.
     */
    public function update(Request $request, Company $company): RedirectResponse
    {
        $validated = $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'phone'  => ['required', 'integer'],
            'status' => ['required', 'boolean'],
        ]);

        $company->update($validated);

        return redirect()
            ->route('company.index')
            ->with('success', 'Company updated successfully.');
    }

    /**
     * Display the specified company along with its users.
     */
    public function show(Company $company): View
    {
        $users = $company->users()->latest()->paginate(15);

        return view('companies.show', compact('company', 'users'));
    }
}
