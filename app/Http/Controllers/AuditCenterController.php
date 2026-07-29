<?php
namespace App\Http\Controllers;
use App\Core\Context\PlatformContext;
use App\Models\SmartAuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
class AuditCenterController extends Controller
{
 public function __construct(private readonly PlatformContext $context) {}
 public function __invoke(Request $request): View {
  $logs=SmartAuditLog::query()->where('company_id',$this->context->companyId())->with('user')->when($request->string('event')->toString(),fn($q,$v)=>$q->where('event','like',"%{$v}%"))->latest('occurred_at')->paginate(30)->withQueryString();
  return view('audit.index',compact('logs'));
 }
}
