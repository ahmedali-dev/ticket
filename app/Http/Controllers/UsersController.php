<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    public function index(){
        $users = User::where('type' , '!=' , 'admin')->get();
        return view('user.index', compact('users'));
    }

    public function create(){
        $companies = Company::all();
        return view('user.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password'   => ['required', 'string', 'min:8'],
            'status'     => ['required', Rule::in(['active', 'disabled'])],
            'company_id' => ['nullable', 'exists:companies,id'],
            'phone'      => ['nullable', 'numeric', 'digits:9'],
        ]);

        User::create([
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'status'     => $validated['status'] == 'active',
            'company_id' => $validated['company_id'] ?? null,
            'phone'      => $validated['phone'],
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function toggleStatus(User $user)
    {
        // Prevent a user from disabling their own account
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('users.index')
                ->with('error', 'You cannot change your own status.');
        }

        $user->status = $user->status ? false : true;
        $user->save();

        $message = $user->status
            ? 'User enabled successfully.'
            : 'User disabled successfully.';

        return redirect()
            ->route('users.index')
            ->with('success', $message);
    }
}
