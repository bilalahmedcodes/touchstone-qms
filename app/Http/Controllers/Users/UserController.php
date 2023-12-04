<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\UserRequest;
use App\Models\Campaign;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::when($request, function ($query, $request) {
            $query->search($request);
        })
            ->with('roles', 'campaign', 'supervisor')
            ->sortable()
            ->orderBy('name', 'asc')
            ->paginate(15);

        $campaigns = Campaign::where('status', 1)
            ->orderBy('name', 'asc')
            ->get();

        return view('users.index')->with(compact('users', 'campaigns'));
    }
    public function create()
    {
        $reporting_to = User::where('id', '!=', 1)->get();
        $campaigns = Campaign::where('status', 'Active')->get();
        $roles = Role::all();
        return view('users.create', compact('roles', 'campaigns', 'reporting_to'));
    }
    public function store(UserRequest $request)
    {
        $roles = explode(',', $request->role);
        $user = User::create($request->except('role'));
        $user->syncRoles($roles);
        Session::flash('success', 'User added successfully!');
        return redirect()->route('users.index');
    }
    public function edit(User $user)
    {
        $reporting_to = User::where('id', '!=', 1)->get();
        $campaigns = Campaign::where('status', 'Active')->get();
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles', 'campaigns', 'reporting_to'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $roles = explode(',', $request->role);
        if (empty($request->password)) {
            $user->update($request->except('role', 'password'));
        } else {
            $user->update($request->except('role'));
        }
        $user->syncRoles($roles);
        Session::flash('success', 'User updated successfully!');
        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();
        Session::flash('success', 'User updated successfully!');
        return redirect()->route('users.index');
    }
}
