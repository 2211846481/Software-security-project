<?php

namespace App\Http\Controllers;

use App\Models\PartnerInstitution;
use App\Models\Agreement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PartnerInstitutionController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $user = auth()->user();
            $query = PartnerInstitution::query();
            
            // Add withCount('agreements') to improve performance and get the number of agreements directly
            if ($user->role == 2) {
                $query->withCount('agreements');
            } else {
                // For other users, we should only retrieve institutions associated with their department
                $query->whereHas('agreements', function ($q) use ($user) {
                    $q->where('department_id', $user->department_id);
                })->withCount('agreements');
            }

            // Add interactive search if there is a value in the search field
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('country', 'LIKE', "%{$search}%")
                      ->orWhere('sector', 'LIKE', "%{$search}%");
                });
            }

            // Use paginate instead of get to display results in pages
            $partners = $query->paginate(10); // You can change the number of items per page

            return view('partners.index', compact('partners'));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve partner institutions: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'An error occurred while loading the list of partner institutions.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // For debugging - log user information
        Log::info('User attempting to create partner institution', [
            'user_id' => auth()->id(),
            'user_role' => auth()->user()->role,
            'user_email' => auth()->user()->email
        ]);

        // Check user permissions - only admin (role 2) or department manager (role 1) can add
        if (auth()->user()->role != 2 && auth()->user()->role != 1) {
            Log::warning('Unauthorized access attempt to create partner institution', [
                'user_id' => auth()->id(),
                'user_role' => auth()->user()->role
            ]);
            return redirect()->route('partners.index')->with('error', 'You do not have permission to add new partner institutions.');
        }

        Log::info('User authorized to create partner institution');
        return view('partners.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Check user permissions - only admin (role 2) or department manager (role 1) can add
            if (auth()->user()->role != 2 && auth()->user()->role != 1) {
                return redirect()->route('partners.index')->with('error', 'You do not have permission to add new partner institutions.');
            }

            // Validate the entered data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'country' => 'required|string|max:100',
                'contact_name' => 'nullable|string|max:255',
                'contact_email' => 'nullable|email|max:255',
                'website' => 'nullable|url|max:255',
                'sector' => 'nullable|string|max:100',
            ]);

            // Create a new record in the database
            PartnerInstitution::create($validated);

            return redirect()->route('partners.index')->with('success', 'Partner institution added successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput()->with('error', 'Please check the entered data.');
        } catch (\Exception $e) {
            Log::error('Failed to create partner institution: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An unexpected error occurred. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PartnerInstitution  $partner
     * @return \Illuminate\View\View
     */
    public function show(PartnerInstitution $partner)
    {
        $agreements = $partner->agreements;
        
        // If the user is not an admin, filter agreements
        // (based on role=1 being the admin)
        if (auth()->user()->role != 2) {
            $agreements = $agreements->where('department_id', auth()->user()->department_id);
        }
    
        return view('partners.show', ['partner' => $partner, 'agreements' => $agreements]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PartnerInstitution  $partner
     * @return \Illuminate\View\View
     */
    public function edit(PartnerInstitution $partner)
    {
        // Check user permissions - only admin (role 2) or department manager (role 1) can edit
        if (auth()->user()->role != 2 && auth()->user()->role != 1) {
            return redirect()->route('partners.index')->with('error', 'You do not have permission to edit partner institutions.');
        }

        return view('partners.edit', compact('partner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PartnerInstitution  $partner
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, PartnerInstitution $partner)
    {
        try {
            // Check user permissions - only admin (role 2) or department manager (role 1) can edit
            if (auth()->user()->role != 2 && auth()->user()->role != 1) {
                return redirect()->route('partners.index')->with('error', 'You do not have permission to edit partner institutions.');
            }

            // Validate the entered data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'country' => 'required|string|max:100',
                'contact_name' => 'nullable|string|max:255',
                'contact_email' => 'nullable|email|max:255',
                'website' => 'nullable|url|max:255',
                'sector' => 'nullable|string|max:100',
            ]);

            // Update the institution's data
            $partner->update($validated);

            return redirect()->route('partners.index')->with('success', 'Partner institution data updated successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput()->with('error', 'Please check the entered data.');
        } catch (\Exception $e) {
            Log::error('Failed to update partner institution: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An unexpected error occurred. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PartnerInstitution  $partner
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(PartnerInstitution $partner)
    {
        try {
            // Check user permissions - only admin can delete
            if (auth()->user()->role != 2) {
                return redirect()->route('partners.index')->with('error', 'You do not have permission to delete partner institutions.');
            }

            // Check if there are agreements linked to the institution
            if ($partner->agreements()->count() > 0) {
                return redirect()->route('partners.index')->with('error', 'The institution cannot be deleted because it is associated with agreements.');
            }

            // Delete the institution record from the database
            $partner->delete();

            return redirect()->route('partners.index')->with('success', 'Partner institution deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete partner institution: ' . $e->getMessage());
            return redirect()->route('partners.index')->with('error', 'An error occurred during deletion. Please try again.');
        }
    }

    /**
     * Search partner institutions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        try {
            $query = $request->get('query');
            
            $partners = PartnerInstitution::where('name', 'LIKE', "%{$query}%")
                ->orWhere('country', 'LIKE', "%{$query}%")
                ->orWhere('sector', 'LIKE', "%{$query}%")
                ->orWhere('contact_name', 'LIKE', "%{$query}%")
                ->with('agreements')
                ->get();

            return view('partners.index', compact('partners', 'query'));
        } catch (\Exception $e) {
            Log::error('Failed to search partner institutions: ' . $e->getMessage());
            return redirect()->route('partners.index')->with('error', 'An error occurred during the search.');
        }
    }

    /**
     * Get partner institutions by sector.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function bySector(Request $request)
    {
        try {
            $sector = $request->get('sector');
            
            $partners = PartnerInstitution::where('sector', $sector)
                ->with('agreements')
                ->get();

            return view('partners.index', compact('partners', 'sector'));
        } catch (\Exception $e) {
            Log::error('Failed to filter partner institutions by sector: ' . $e->getMessage());
            return redirect()->route('partners.index')->with('error', 'An error occurred while filtering the institutions.');
        }
    }
}