<?php

namespace App\Http\Controllers;

use App\Core\Context\PlatformContext;
use App\Domain\CRM\Models\CrmTimelineEvent;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OperationsFeedController extends Controller
{
    public function __invoke(Request $request, PlatformContext $context): View
    {
        $query = CrmTimelineEvent::query()->with(['lead', 'user', 'company'])->latest();

        if ($context->companyId()) {
            $query->where('company_id', $context->companyId());
        } elseif (! $context->user()->isInternalSmartBizUser()) {
            $query->whereRaw('1 = 0');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->string('type')->toString());
        }

        if ($request->filled('q')) {
            $term = '%'.$request->string('q')->toString().'%';
            $query->where(function ($builder) use ($term): void {
                $builder->where('title', 'like', $term)
                    ->orWhere('description', 'like', $term)
                    ->orWhereHas('lead', fn ($lead) => $lead->where('name', 'like', $term));
            });
        }

        return view('operations.feed', [
            'events' => $query->paginate(25)->withQueryString(),
            'types' => CrmTimelineEvent::query()
                ->when($context->companyId(), fn ($builder) => $builder->where('company_id', $context->companyId()))
                ->select('type')->distinct()->orderBy('type')->pluck('type'),
        ]);
    }
}
