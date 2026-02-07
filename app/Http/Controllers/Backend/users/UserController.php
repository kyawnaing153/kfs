<?php

namespace App\Http\Controllers\Backend\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\User\UserRequest;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = [];

        if ($request->filled('search')) {
            $filters['search'] = $request->search;
        }

        if ($request->filled('role')) {
            $filters['role'] = $request->role;
        }

        if($request->filled('status')) {
            $filters['status'] = $request->status;
        }

        $orderBy = $request->input('order_by', 'id');
        $orderDir = $request->input('order_dir', 'desc');

        $users = $this->userService
            ->getUsers($filters, $orderBy, $orderDir)
            ->paginate(15);

        return view('pages.admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $validated = $request->validated();

        // Handle file upload if present
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = uniqid('profile_') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('Backend/img/profile/'), $filename);
            $validated['profile_picture'] = $filename;
        }

        // Hash password
        $validated['password'] = bcrypt($validated['password']);

        $this->userService->createUser($validated);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = $this->userService->getUser($id);
        return view('pages.admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = $this->userService->getUser($id);
        return view('pages.admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
       $validated = $request->validated();

        // Remove password if empty (keep current)
        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = bcrypt($validated['password']);
        }

        // Handle file upload if present
        if ($request->hasFile('profile_picture')) {
            $user = $this->userService->getUser($id);
            
            // Delete old profile picture if exists
            if ($user->profile_picture && file_exists(public_path('Backend/img/profile/' . $user->profile_picture))) {
                unlink(public_path('Backend/img/profile/' . $user->profile_picture));
            }
            
            $file = $request->file('profile_picture');
            $filename = uniqid('profile_') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('Backend/img/profile/'), $filename);
            $validated['profile_picture'] = $filename;
        }

        $this->userService->updateUser($id, $validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully!'); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->userService->deleteUser($id);

        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }

    public function toggleStatus(string $id){
        
        $this->userService->toggleUserStatus($id);

        return redirect()->back()->with('success', 'User status updated successfully!');
    }
}
