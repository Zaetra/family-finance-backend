<?php

namespace App\Http\Controllers;

use App\Http\Requests\FamilyGroup\StoreFamilyGroupRequest;
use App\Http\Requests\FamilyGroup\UpdateFamilyGroupRequest;
use App\Models\FamilyGroup;
use App\Services\FamilyGroupService;
use Illuminate\Http\Request;

class FamilyGroupController extends Controller
{
    protected $familyService;

    public function __construct(FamilyGroupService $familyService)
    {
        $this->familyService = $familyService;
    }

    public function index(Request $request)
    {
        $familyGroup = $this->familyService->getFamilyGroupForUser($request->user());
        
        if ($familyGroup) {
            return $this->successResponse($familyGroup, 'Grupo familiar recuperado');
        }
        
        return $this->successResponse([], 'El usuario no pertenece a ningún grupo');
    }

    public function store(StoreFamilyGroupRequest $request)
    {
        $familyGroup = $this->familyService->createFamilyGroup($request->user(), $request->validated());

        return $this->successResponse($familyGroup, 'Grupo familiar creado correctamente', 201);
    }

    public function show(FamilyGroup $familyGroup)
    {
        $details = $this->familyService->getDetails($familyGroup);
        return $this->successResponse($details, 'Detalles del grupo familiar');
    }

    public function update(UpdateFamilyGroupRequest $request, FamilyGroup $familyGroup)
    {
        $updated = $this->familyService->updateFamilyGroup($familyGroup, $request->validated());
        return $this->successResponse($updated, 'Grupo familiar actualizado');
    }

    public function destroy(FamilyGroup $familyGroup)
    {
        $this->familyService->deleteFamilyGroup($familyGroup);
        return $this->successResponse(null, 'Grupo familiar eliminado', 204);
    }
}
