<?php
namespace App\Domain\CRM\Http\Controllers;
use App\Core\Authorization\Enums\Permission;
use App\Core\Authorization\Facades\SmartGate;
use App\Domain\CRM\Models\CrmStage;
use App\Domain\CRM\Models\Lead;
use App\Domain\CRM\Services\LeadService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class CrmMoveLeadController extends Controller {
 public function __construct(private readonly LeadService $service){}
 public function __invoke(Request $request,Lead $lead):JsonResponse{
  SmartGate::authorize(Permission::CRM_UPDATE);
  $data=$request->validate(['stage_id'=>['required','integer','exists:crm_stages,id']]);
  return response()->json($this->service->move($lead,CrmStage::query()->findOrFail($data['stage_id'])));
 }
}
