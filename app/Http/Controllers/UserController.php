<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\Interfaces\UserServiceInterface;

class UserController extends Controller
{

    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = $this->userService->getAll();
            return view('pages.User.index', compact('users'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Memuat Data User');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('pages.User.create');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Memuat Tampilan Create User');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $this->userService->create($request->validated());
            return redirect()->route('users.index')->with('success', 'User Berhasil Ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Menambahkan User');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = $this->userService->getById($id);
            return view('pages.User.show', compact('user'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Memuat Data User');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $user = $this->userService->getById($id);
            return view('pages.User.edit', compact('user'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Memuat Data User');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        try {
            $data = collect($request->validated())->except('password')->toArray();
            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
            }
            $this->userService->update($id, $data);
            return redirect()->route('users.index')->with('success', 'User Berhasil Diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Memperbarui User');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->userService->delete($id);
            return redirect()->route('users.index')->with('success', 'User Berhasil Dinonaktifkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal Menghapus User');
        }
    }
}
