<?php

namespace App\Http\Controllers;

use App\Models\SecureData;
use App\Services\AdvancedEncryptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SecureDataController extends Controller
{
    protected $encryptionService;

    public function __construct()
    {
        $this->encryptionService = new AdvancedEncryptionService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $records = SecureData::active()
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

            return view('secure-data.index', compact('records'));
        } catch (\Exception $e) {
            Log::error('Error fetching secure data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading data.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $securityLevels = SecureData::getSecurityLevels();
        return view('secure-data.create', compact('securityLevels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'credit_card' => 'required|string|max:19',
            'social_security_number' => 'required|string|size:11',
            'medical_info' => 'nullable|string',
            'financial_info' => 'nullable|string',
            'security_level' => 'required|in:low,medium,high,critical',
            'is_active' => 'boolean'
        ], [
            'social_security_number.size' => 'SSN must be exactly 11 characters (XXX-XX-XXXX).',
            'security_level.in' => 'Please select a valid security level.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }

        try {
            $data = $request->all();
            $data['is_active'] = $request->has('is_active');

            SecureData::create($data);

            Log::info('New secure record created', ['email' => $request->email]);

            return redirect()->route('secure-data.index')
                ->with('success', 'Secure record created successfully with advanced encryption.');

        } catch (\Exception $e) {
            Log::error('Error creating secure record: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error creating record: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $record = SecureData::findOrFail($id);
            return view('secure-data.show', compact('record'));
        } catch (\Exception $e) {
            Log::error('Error fetching record: ' . $e->getMessage());
            return redirect()->route('secure-data.index')
                ->with('error', 'Record not found.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $record = SecureData::findOrFail($id);
            $securityLevels = SecureData::getSecurityLevels();
            return view('secure-data.edit', compact('record', 'securityLevels'));
        } catch (\Exception $e) {
            Log::error('Error fetching record for edit: ' . $e->getMessage());
            return redirect()->route('secure-data.index')
                ->with('error', 'Record not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'credit_card' => 'required|string|max:19',
            'social_security_number' => 'required|string|size:11',
            'medical_info' => 'nullable|string',
            'financial_info' => 'nullable|string',
            'security_level' => 'required|in:low,medium,high,critical',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }

        try {
            $record = SecureData::findOrFail($id);
            $data = $request->all();
            $data['is_active'] = $request->has('is_active');

            $record->update($data);

            Log::info('Secure record updated', ['id' => $id, 'email' => $request->email]);

            return redirect()->route('secure-data.index')
                ->with('success', 'Record updated successfully.');

        } catch (\Exception $e) {
            Log::error('Error updating record: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error updating record: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $record = SecureData::findOrFail($id);
            $record->delete();

            Log::info('Secure record deleted', ['id' => $id]);

            return redirect()->route('secure-data.index')
                ->with('success', 'Record deleted successfully.');

        } catch (\Exception $e) {
            Log::error('Error deleting record: ' . $e->getMessage());
            return redirect()->route('secure-data.index')
                ->with('error', 'Error deleting record: ' . $e->getMessage());
        }
    }

    /**
     * Verify encryption (for testing)
     */
    public function verifyEncryption($id)
    {
        try {
            $record = SecureData::findOrFail($id);
            $rawData = $record->getRawOriginal();

            return response()->json([
                'success' => true,
                'record_id' => $id,
                'encrypted_data_sample' => [
                    'name' => substr($rawData['name'], 0, 50) . '...',
                    'email' => substr($rawData['email'], 0, 50) . '...',
                ],
                'decrypted_data' => [
                    'name' => $record->name,
                    'email' => $record->email,
                    'phone' => $record->phone,
                ],
                'security_level' => $record->security_level
            ]);

        } catch (\Exception $e) {
            Log::error('Encryption verification failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle active status
     */
    public function toggleStatus($id)
    {
        try {
            $record = SecureData::findOrFail($id);
            $record->update(['is_active' => !$record->is_active]);

            $status = $record->is_active ? 'activated' : 'deactivated';

            return redirect()->route('secure-data.index')
                ->with('success', "Record {$status} successfully.");

        } catch (\Exception $e) {
            Log::error('Error toggling status: ' . $e->getMessage());
            return redirect()->route('secure-data.index')
                ->with('error', 'Error updating record status.');
        }
    }
}
