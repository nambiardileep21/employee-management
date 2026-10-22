<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\Employee;
use App\Models\State;
use App\Notifications\EmployeeSavedNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(Request $request): View
    {
        $countries = Country::orderBy('name')->get();

        $employees = Employee::with(['country', 'state', 'city'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->input('status'));
            })
            ->when($request->filled('country_id'), function ($query) use ($request) {
                $query->where('country_id', $request->input('country_id'));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('employees.index', compact('employees', 'countries'));
    }

    public function create(): View
    {
        $countries = Country::orderBy('name')->get();
        // dd($countries);
        return view('employees.create', compact('countries'));
    }

    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $employee = Employee::create($data);

        // Send queued email notification to logged-in user or system admin
        // Notification::send($request->user(), new EmployeeSavedNotification($employee, 'created'));
        $employee->notify(new EmployeeSavedNotification($employee, 'created'));

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee): View
    {
        $employee->load(['country', 'state', 'city']);
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee): View
    {
        $countries = Country::orderBy('name')->get();
        $states = State::where('country_id', $employee->country_id)->orderBy('name')->get();
        $cities = City::where('state_id', $employee->state_id)->orderBy('name')->get();

        return view('employees.edit', compact('employee', 'countries', 'states', 'cities'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            if ($employee->avatar && Storage::disk('public')->exists($employee->avatar)) {
                Storage::disk('public')->delete($employee->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $employee->update($data);

        // Notification::send($request->user(), new EmployeeSavedNotification($employee, 'updated'));
        $employee->notify(new EmployeeSavedNotification($employee, 'updated'));

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        /*if ($employee->avatar && Storage::disk('public')->exists($employee->avatar)) {
            Storage::disk('public')->delete($employee->avatar);
        }*/

        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }

    // AJAX Endpoint for States
    public function getStates(Country $country): JsonResponse
    {
        return response()->json($country->states()->orderBy('name')->get(['id', 'name']));
    }

    // AJAX Endpoint for Cities
    public function getCities(State $state): JsonResponse
    {
        return response()->json($state->cities()->orderBy('name')->get(['id', 'name']));
    }
}