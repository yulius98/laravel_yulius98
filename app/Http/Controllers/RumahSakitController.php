<?php

namespace App\Http\Controllers;

use App\Http\Requests\RumahSakitRequest;
use App\Services\RumahSakitService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class RumahSakitController extends Controller
{
    protected RumahSakitService $rumahSakitService;

    public function __construct(RumahSakitService $rumahSakitService)
    {
        $this->rumahSakitService = $rumahSakitService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(string $user): View
    {
        $dtrumahsakit = $this->rumahSakitService->getPaginatedData();

        return view('DataRumahSakit', [
            'title' => $user,
            'dtrumahsakit' => $dtrumahsakit
        ]);
    }

    /**
     * Search rumah sakit data (AJAX)
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->input('q');
            $data = $this->rumahSakitService->searchData($query);
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
            $rumahSakit = $this->rumahSakitService->findById($id);
            return response()->json($rumahSakit);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RumahSakitRequest $request): JsonResponse
    {
        try {
            $rumahSakit = $this->rumahSakitService->create($request->validated());
            return response()->json($rumahSakit, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RumahSakitRequest $request, int $id): JsonResponse
    {
        try {
            $rumahSakit = $this->rumahSakitService->update($id, $request->validated());
            return response()->json($rumahSakit);
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
            $this->rumahSakitService->delete($id);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
