<?php
namespace App\Domain\CRM\Http\Controllers;
use App\Core\Authorization\Enums\Permission;
use App\Core\Authorization\Facades\SmartGate;
use App\Domain\CRM\Http\Requests\StoreActivityRequest;
use App\Domain\CRM\Models\CrmActivity;
use App\Domain\CRM\Models\Lead;
use App\Domain\CRM\Services\ActivityService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
class CrmActivityController extends Controller {
 public function __construct(private readonly ActivityService $service){}
 public function store(StoreActivityRequest $request,Lead $lead):RedirectResponse{
  SmartGate::authorize(Permission::CRM_UPDATE);$this->service->create($lead,$request->validated());return back()->with('status','activity-created');
 }
 public function complete(CrmActivity $activity):RedirectResponse{
  SmartGate::authorize(Permission::CRM_UPDATE);$this->service->complete($activity);return back()->with('status','activity-completed');
 }
}
