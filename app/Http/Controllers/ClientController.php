<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'client')->withCount('bookings');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $clients = $query->latest()->paginate(15)->withQueryString();

        return view('admin.clients.index', compact('clients', 'search'));
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'min:2', 'max:100'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ], [
            'name.required'      => 'Please enter the client\'s full name.',
            'email.required'     => 'Please enter an email address.',
            'email.unique'       => 'This email is already registered.',
            'password.required'  => 'Please set a password.',
            'password.min'       => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'client',
        ]);

        return redirect()->route('admin.clients.index')
            ->with('success', "Client \"{$data['name']}\" created successfully.");
    }

    public function show(User $client)
    {
        $client->load(['bookings' => fn ($q) => $q->with('room')->latest()]);

        return view('admin.clients.show', compact('client'));
    }

    public function edit(User $client)
    {
        return view('admin.clients.edit', compact('client'));
    }

    public function update(Request $request, User $client)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'min:2', 'max:100'],
            'email'    => ['required', 'email', Rule::unique('users', 'email')->ignore($client->id)],
            'password' => ['nullable', 'min:8', 'confirmed'],
        ], [
            'name.required'  => 'Please enter the client\'s full name.',
            'email.required' => 'Please enter an email address.',
            'email.unique'   => 'This email is already registered.',
            'password.min'   => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $client->name  = $data['name'];
        $client->email = $data['email'];

        if (! empty($data['password'])) {
            $client->password = Hash::make($data['password']);
        }

        $client->save();

        return redirect()->route('admin.clients.index')
            ->with('success', "Client \"{$client->name}\" updated successfully.");
    }

    public function destroy(User $client)
    {
        $name = $client->name;
        $client->delete();

        return redirect()->route('admin.clients.index')
            ->with('success', "Client \"{$name}\" deleted.");
    }
}
