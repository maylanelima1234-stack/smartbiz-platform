<?php
namespace App\Domain\CRM\Http\Controllers;
use App\Core\Authorization\Enums\Permission;
use App\Core\Authorization\Facades\SmartGate;
use App\Core\Licensing\Enums\Feature;
use App\Core\Licensing\Facades\SmartLicense;
use App\Domain\CRM\Services\CrmDashboardService;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
class CrmDashboardController extends Controller
{
    public function __construct(private readonly CrmDashboardService $dashboard) {}
    public function __invoke(): View
    {
        abort_unless(SmartLicense::allows(Feature::CRM),403);
        SmartGate::authorize(Permission::CRM_VIEW);
        return view('crm.dashboard',$this->dashboard->summary());
    }
}

