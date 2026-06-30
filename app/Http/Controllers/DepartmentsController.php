<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Services\DepartmentService;
use Illuminate\Support\Facades\Log;

class DepartmentsController extends Controller
{
    protected DepartmentService $departmentService;

    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    public function index()
    {
        try {
            $departments = $this->departmentService->getAll();
            return view('pages.department.index', compact('departments'));
        } catch (\Exception $e) {
            Log::error('Error retrieving departments: ' . $e->getMessage() . ' User: ' . auth()->user()->name . ' IP Address: ' . request()->ip() . ' User Agent: ' . request()->userAgent());
            return redirect()->back()->with('error', 'Failed to retrieve departments: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            return view('pages.department.create');
        } catch (\Exception $e) {
            Log::error('Error displaying create department form: ' . $e->getMessage() . ' User: ' . auth()->user()->name . ' IP Address: ' . request()->ip() . ' User Agent: ' . request()->userAgent());
            return redirect()->back()->with('error', 'Failed to display create department form: ' . $e->getMessage());
        }
    }

    public function store(StoreDepartmentRequest $request)
    {
        try {
            $this->departmentService->create($request->validated());
            Log::info('Department created successfully: ' . json_encode($request->validated()) . ' User: ' . auth()->user()->name . ' IP Address: ' . request()->ip() . ' User Agent: ' . request()->userAgent());
            return redirect()->route('departments.index')->with('success', 'Department created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating department: ' . $e->getMessage() . ' User: ' . auth()->user()->name . ' IP Address: ' . request()->ip() . ' User Agent: ' . request()->userAgent());
            return redirect()->back()->with('error', 'Failed to create department: ' . $e->getMessage());
        }
    }

    public function edit(string $id)
    {
        try {
            $department = $this->departmentService->getById($id);
            return view('pages.department.edit', compact('department'));
        } catch (\Exception $e) {
            Log::error('Error retrieving department for edit: ' . $e->getMessage() . ' User: ' . auth()->user()->name . ' IP Address: ' . request()->ip() . ' User Agent: ' . request()->userAgent());
            return redirect()->back()->with('error', 'Failed to retrieve department for edit: ' . $e->getMessage());
        }
    }

    public function update(UpdateDepartmentRequest $request, string $id)
    {
        try {
            $this->departmentService->update($id, $request->validated());
            Log::info('Department updated successfully: ' . json_encode($request->validated()) . ' User: ' . auth()->user()->name . ' IP Address: ' . request()->ip() . ' User Agent: ' . request()->userAgent());
            return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating department: ' . $e->getMessage() . ' User: ' . auth()->user()->name . ' IP Address: ' . request()->ip() . ' User Agent: ' . request()->userAgent());
            return redirect()->back()->with('error', 'Failed to update department: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->departmentService->delete($id);
            Log::info('Department deleted successfully: ' . $id . ' User: ' . auth()->user()->name . ' IP Address: ' . request()->ip() . ' User Agent: ' . request()->userAgent());
            return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting department: ' . $e->getMessage() . ' User: ' . auth()->user()->name . ' IP Address: ' . request()->ip() . ' User Agent: ' . request()->userAgent());
            return redirect()->back()->with('error', 'Failed to delete department: ' . $e->getMessage());
        }
    }
}
