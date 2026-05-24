<?php

namespace App\Http\Controllers;

use App\Models\AgreementType;
use App\Models\Agreement;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AgreementTypeController extends Controller
{
    
    public function index(Request $request): View
    {
        $query = AgreementType::withCount('agreements');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', '%' . $search . '%');
        }

        $agreementTypes = $query->paginate(10);

        return view('AgreementTypes.agreement_types', compact('agreementTypes'));
    }

    
    public function create(): View
    {
        return view('AgreementTypes.agreement_types-add_edit');
    }

    
    public function store(Request $request): RedirectResponse
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255|unique:agreement_types,name',
            'description' => 'nullable|string',
        ]);

        AgreementType::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('agreement_types.index')->with('success', 'Agreement type added successfully.');
    }

    /**
     * Show the form for editing the specified agreement type.
     */
    public function edit(string $id): View
    {
        $agreementType = AgreementType::findOrFail($id);

        return view('AgreementTypes.agreement_types-add_edit', compact('agreementType'));
    }

    
    public function update(Request $request, string $id): RedirectResponse
    {
        // Find the agreement type to be updated
        $agreementType = AgreementType::findOrFail($id);

        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255|unique:agreement_types,name,' . $agreementType->id,
            'description' => 'nullable|string',
        ]);

        // Update the agreement type data
        $agreementType->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('agreement_types.index')->with('success', 'Agreement type updated successfully.');
    }

    /**
     * Remove the specified agreement type from the database.
     */
    public function destroy(string $id): RedirectResponse
    {
        $agreementType = AgreementType::findOrFail($id);

        if (Agreement::where('agreement_type_id', $agreementType->id)->exists()) {
            return redirect()->route('agreement_types.index')->with('error', 'Cannot delete the agreement type. There are agreements associated with it.');
        }

        $agreementType->delete();

        return redirect()->route('agreement_types.index')->with('success', 'Agreement type deleted successfully.');
    }
}
