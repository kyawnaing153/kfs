<?php

namespace App\Http\Controllers\Backend\staffs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\staff\StaffRequest;
use App\Services\StaffService;

class StaffController extends Controller
{
    protected $staffService;

    public function __construct(StaffService $staffService)
    {
        $this->staffService = $staffService;
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

        if ($request->filled('status')) {
            $filters['status'] = $request->status;
        }

        $orderBy = $request->input('order_by', 'id');
        $orderDir = $request->input('order_dir', 'desc');

        $staffs = $this->staffService->getStaffs($filters, $orderBy, $orderDir)->paginate(15);

        return view('pages.admin.staffs.index', compact('staffs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.staffs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StaffRequest $request)
    {
        // Get validated data
        $validatedData = $request->validated();
        try {
            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                $file = $request->file('profile_picture');
                $filename = uniqid('staff_') . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('Backend/img/staff/'), $filename);
                $validatedData['profile_picture'] = $filename;
            }


            // Create staff record
            $this->staffService->createStaff($validatedData);

            return redirect()->route('staffs.index')
                ->with('success', 'Staff member created successfully!');
        } catch (\Exception $e) {
            // If there's an error, delete the uploaded file            if (isset($validatedData['profile_picture'])) {
            $filePath = public_path('Backend/img/staff/' . $validatedData['profile_picture']);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to create staff member. Please try again.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $staff = $this->staffService->getStaff($id);
        return view('pages.admin.staffs.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $staff = $this->staffService->getStaff($id);
        return view('pages.admin.staffs.edit', compact('staff'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StaffRequest $request, string $id)
    {
        $validated = $request->validated();

        //Handle profile picture upload
        if($request->hasFile('profile_picture')) {
            $staff = $this->staffService->getStaff($id);

            //Delete old profile picture if exists
            if($staff->profile_picture && file_exists(public_path('Backend/img/staff/' . $staff->profile_picture))) {
                unlink(public_path('Backend/img/staff/' . $staff->profile_picture));
            }

            $file = $request->file('profile_picture');
            $filename = uniqid('staff_') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('Backend/img/staff/'), $filename);
            $validated['profile_picture'] = $filename;
        }

        try {
            // Update staff record
            $this->staffService->updateStaff($id, $validated);

            return redirect()->route('staffs.index')
                ->with('success', 'Staff member updated successfully!');
        } catch (\Exception $e) {
            // If there's an error, delete the uploaded file if it exists
            if (isset($validated['profile_picture'])) {
                $filePath = public_path('Backend/img/staff/' . $validated['profile_picture']);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }
        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to update staff member. Please try again.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->staffService->deleteStaff($id);
        return redirect()->route('staffs.index')->with('success', 'Staff member deleted successfully.');
    }

    public function toggleStatus(string $id) 
    {
        $this->staffService->toggleStaffStatus($id);
        return redirect()->route('staffs.index')->with('success', 'Staff member status updated successfully.');
    }
}
