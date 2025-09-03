<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasienRequest;
use App\Services\PasienService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class PasienController extends Controller
{
    protected PasienService $pasienService;

    public function __construct(PasienService $pasienService)
    {
        $this->pasienService = $pasienService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(string $user): View
    {
        $dtpasien = $this->pasienService->getPaginatedData();
        $dtrumahsakit = $this->pasienService->getAllHospitals();

        return view('DataPasien', [
            'title' => $user,
            'dtpasien' => $dtpasien,
            'dtrumahsakit' => $dtrumahsakit
        ]);
    }

    /**
     * Search pasien data (AJAX)
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->input('q');
            $rumahSakitId = $request->input('rumah_sakit_id');
            $data = $this->pasienService->searchData($query, $rumahSakitId);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal melakukan pencarian'], 500);
        }
    }

    /**
     * Show the specified resource (AJAX)
     */
    public function show(int $id): JsonResponse
    {
        try {
            $pasien = $this->pasienService->findById($id);
            return response()->json($pasien);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PasienRequest $request): JsonResponse
    {
        try {
            $pasien = $this->pasienService->create($request->validated());
            return response()->json($pasien, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PasienRequest $request, int $id): JsonResponse
    {
        try {
            $pasien = $this->pasienService->update($id, $request->validated());
            return response()->json($pasien);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage (AJAX)
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->pasienService->delete($id);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get rumah sakit list for dropdown (AJAX)
     */
    public function getRumahSakit(): JsonResponse
    {
        try {
            $rumahSakit = $this->pasienService->getAllHospitals();
            return response()->json($rumahSakit);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal memuat data rumah sakit'], 500);
        }
    }
}
