<?php

namespace App\Domain\CRM\Http\Controllers;

use App\Core\Authorization\Enums\Permission;
use App\Core\Authorization\Facades\SmartGate;
use App\Domain\CRM\Http\Requests\StoreCommentRequest;
use App\Domain\CRM\Http\Requests\SyncLeadTagsRequest;
use App\Domain\CRM\Models\Lead;
use App\Domain\CRM\Services\LeadEngagementService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class CrmEngagementController extends Controller
{
    public function __construct(private readonly LeadEngagementService $service) {}

    public function comment(StoreCommentRequest $request, Lead $lead): RedirectResponse
    {
        SmartGate::authorize(Permission::CRM_UPDATE);
        $this->service->addComment($lead, $request->validated('body'));
        return back()->with('status', 'comment-created');
    }

    public function tags(SyncLeadTagsRequest $request, Lead $lead): RedirectResponse
    {
        SmartGate::authorize(Permission::CRM_UPDATE);
        $this->service->syncTags($lead, $request->validated('tags', []));
        return back()->with('status', 'tags-updated');
    }

    public function favorite(Lead $lead): RedirectResponse
    {
        SmartGate::authorize(Permission::CRM_UPDATE);
        $this->service->toggleFavorite($lead);
        return back()->with('status', 'favorite-updated');
    }
}
