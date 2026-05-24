<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Department;
use App\Models\AgreementType;
use App\Models\PartnerInstitution;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; // Added for logging potential issues

class AgreementController extends Controller
{
    /**
     * Display a listing of all agreements based on user role.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Check if a user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            $agreements = null;

            // Admin (role 2) can see all agreements
            if ($user->role === 2) {
                $agreements = Agreement::with(['department', 'agreementType'])->get();
            } else {
                // Other roles can only see agreements from their department
                $agreements = Agreement::with(['department', 'agreementType'])
                    ->where('department_id', $user->department_id)
                    ->get();
            }
        } else {
            // If no user is authenticated, return an empty collection
            $agreements = collect();
        }

        $userRole = Auth::user()->role ?? null;
        
        return view('Agreement.HEDAYA', compact('agreements', 'userRole'));
    }

    /**
     * Show the form for creating a new agreement.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        // Get all necessary data for the form
        $departments = Department::all();
        $agreementTypes = AgreementType::all();
        $partners = PartnerInstitution::all();
        
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        $userRole = $user->role;
        
        // Check if the user has permission to create an agreement (Admin or Department Supervisor)
        if ($userRole === 2 || $userRole === 1) {
            return view('Agreement.create_agreement', compact('departments', 'agreementTypes', 'partners', 'userRole'));
        }

        return redirect()->route('hedaya')->with('error', 'You do not have permission to create an agreement.');
    }

    /**
     * Store a newly created agreement in the database.
     *
     * This method now handles validation, file uploads, and linking partner institutions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Define validation rules
        $rules = [
            'title' => 'required|string|max:255',
            'reference_code' => 'required|string|unique:agreements|max:255',
            'signing_date' => 'required|date',
            'effective_date' => 'required|date|after_or_equal:signing_date',
            'expiry_date' => 'required|date|after_or_equal:effective_date',
            'description' => 'nullable|string',
            'agreement_type_id' => 'required|exists:agreement_types,id',
            // Department_id is required for Admin, but will be auto-set for supervisors
            'department_id' => 'required|exists:departments,id',
            // Validation for partner institutions
            'partner_institutions' => 'nullable|array',
            'partner_institutions.*' => 'exists:partner_institutions,id',
            // Validation for documents. Can be multiple files.
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
        ];
        
        // If the user is a Department Supervisor, override the department_id rule
        if ($user->role === 1) {
            $rules['department_id'] = ['prohibits:department_id'];
        }

        $validatedData = $request->validate($rules);
        
        // If the user is a Department Supervisor, automatically set the department_id
        if ($user->role === 1) {
            $validatedData['department_id'] = $user->department_id;
        }

        // Determine the agreement status based on dates
        $currentDate = Carbon::now()->startOfDay();
        $effectiveDate = Carbon::parse($validatedData['effective_date'])->startOfDay();
        $expiryDate = Carbon::parse($validatedData['expiry_date'])->startOfDay();

        $status = 0; // 0 = Draft
        if ($currentDate->isBetween($effectiveDate, $expiryDate, true)) {
            $status = 1; // 1 = Active
        } elseif ($currentDate->gt($expiryDate)) {
            $status = 2; // 2 = Expired
        }
        
        $validatedData['status'] = $status;
        
        // Extract partner IDs before creating the agreement
        $partnerIds = $validatedData['partner_institutions'] ?? [];
        unset($validatedData['partner_institutions']);

        // Create the agreement
        $agreement = Agreement::create($validatedData);

        // Link the agreement to the partner institutions using the sync method
        $agreement->partners()->sync($partnerIds);
        
        // Handle document uploads
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                // Store the file in the 'agreements/documents' directory
                $path = $file->store('agreements/documents', 'public');
                
                // Create a new document record linked to the agreement
                $agreement->documents()->create([
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getMimeType(),
                ]);
            }
        }

        return redirect()->route('hedaya')->with('success', 'Agreement created successfully!');
    }

    /**
     * Display the specified agreement.
     *
     * @param  \App\Models\Agreement  $agreement
     * @return \Illuminate\View\View
     */
    public function show(Agreement $agreement)
    {
        $userRole = Auth::user()->role ?? null;
        $agreement->load(['department', 'agreementType', 'documents', 'partners']);
        return view('Agreement.agreement-details', compact('agreement', 'userRole'));
    }

    /**
     * Show the form for editing the specified agreement.
     *
     * @param  \App\Models\Agreement  $agreement
     * @return \Illuminate\View\View
     */
    public function edit(Agreement $agreement)
    {
        // Load all data required for the form
        $departments = Department::all();
        $agreementTypes = AgreementType::all();
        $partners = PartnerInstitution::all();
        $userRole = Auth::user()->role ?? null;
        
        // Eager load relationships for the agreement being edited
        $agreement->load(['department', 'agreementType', 'documents', 'partners']);

        return view('Agreement.edit_agreement', compact('agreement', 'departments', 'agreementTypes', 'partners', 'userRole'));
    }

    /**
     * Update the specified agreement in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agreement  $agreement
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Agreement $agreement)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'reference_code' => ['required', 'string', 'max:255', Rule::unique('agreements')->ignore($agreement->id)],
            'signing_date' => 'required|date',
            'effective_date' => 'required|date|after_or_equal:signing_date',
            'expiry_date' => 'required|date|after_or_equal:effective_date',
            'description' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
            'agreement_type_id' => 'required|exists:agreement_types,id',
            'partner_institutions' => 'nullable|array',
            'partner_institutions.*' => 'exists:partner_institutions,id',
            // Validation for new document uploads
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        // Determine the agreement status based on dates
        $currentDate = Carbon::now()->startOfDay();
        $effectiveDate = Carbon::parse($validatedData['effective_date'])->startOfDay();
        $expiryDate = Carbon::parse($validatedData['expiry_date'])->startOfDay();
        
        $status = 0; // 0 = Draft
        if ($currentDate->isBetween($effectiveDate, $expiryDate, true)) {
            $status = 1; // 1 = Active
        } elseif ($currentDate->gt($expiryDate)) {
            $status = 2; // 2 = Expired
        }
        
        $validatedData['status'] = $status;

        // Extract partner IDs before updating the agreement
        $partnerIds = $validatedData['partner_institutions'] ?? [];
        unset($validatedData['partner_institutions']);

        // Update the agreement
        $agreement->update($validatedData);
        // Sync the partner institutions
        $agreement->partners()->sync($partnerIds);
        
        // Handle document uploads
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                // Store the file in the 'agreements/documents' directory
                $path = $file->store('agreements/documents', 'public');
                
                // Create a new document record linked to the agreement
                $agreement->documents()->create([
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getMimeType(),
                ]);
            }
        }

        return redirect()->route('hedaya')->with('success', 'Agreement updated successfully!');
    }

    /**
     * Remove the specified agreement from storage.
     *
     * @param  \App\Models\Agreement  $agreement
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Agreement $agreement)
    {
        // Before deleting the agreement, delete any associated documents from storage
        foreach ($agreement->documents as $document) {
            Storage::disk('public')->delete($document->file_path);
            $document->delete();
        }

        $agreement->delete();

        return redirect()->route('hedaya')->with('success', 'Agreement deleted successfully!');
    }

    /**
     * Download the specified document.
     *
     * This method is refactored to use Laravel's Storage facade
     * and assumes the Document model has a `file_path` column.
     *
     * @param  \App\Models\Document  $document
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\RedirectResponse
     */
    public function downloadDocument(Document $document)
    {
        // Check if the file exists on the 'public' disk
        if (Storage::disk('public')->exists($document->file_path)) {
            // Use Laravel's Storage::download() to serve the file
            return Storage::disk('public')->download($document->file_path, $document->file_name);
        }

        Log::error('Attempt to download a non-existent file.', ['file_path' => $document->file_path, 'document_id' => $document->id]);
        return redirect()->back()->with('error', 'The requested document was not found.');
    }
}
