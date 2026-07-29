<?php

namespace App\Domain\CRM\Http\Controllers;

use App\Core\Context\PlatformContext;
use App\Domain\CRM\Models\CrmLeadFile;
use App\Domain\CRM\Models\Lead;
use App\Domain\CRM\Services\TimelineService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CrmLeadFileController extends Controller
{
    public function __construct(
        private readonly PlatformContext $context,
        private readonly TimelineService $timeline,
    ) {}

    public function store(Request $request, Lead $lead): RedirectResponse
    {
        $this->ensureLead($lead);
        $validated = $request->validate(['file' => ['required', 'file', 'max:10240']]);
        $uploaded = $validated['file'];
        $path = $uploaded->store("crm/{$this->context->companyId()}/leads/{$lead->id}", 'local');

        $file = CrmLeadFile::query()->create([
            'public_id' => (string) Str::ulid(),
            'company_id' => $this->context->companyId(),
            'lead_id' => $lead->id,
            'uploaded_by' => auth()->id(),
            'disk' => 'local',
            'path' => $path,
            'original_name' => $uploaded->getClientOriginalName(),
            'mime_type' => $uploaded->getClientMimeType(),
            'size' => $uploaded->getSize() ?: 0,
        ]);

        $this->timeline->record($lead, 'file_uploaded', 'Arquivo adicionado', $file->original_name);

        return back()->with('status', 'lead-file-uploaded');
    }

    public function download(CrmLeadFile $file): StreamedResponse
    {
        $this->ensureFile($file);
        abort_unless(Storage::disk($file->disk)->exists($file->path), 404);

        return Storage::disk($file->disk)->download($file->path, $file->original_name);
    }

    public function destroy(CrmLeadFile $file): RedirectResponse
    {
        $this->ensureFile($file);
        $lead = $file->lead;
        Storage::disk($file->disk)->delete($file->path);
        $name = $file->original_name;
        $file->delete();
        $this->timeline->record($lead, 'file_deleted', 'Arquivo removido', $name);

        return back()->with('status', 'lead-file-deleted');
    }

    private function ensureLead(Lead $lead): void
    {
        abort_unless((int) $lead->company_id === (int) $this->context->companyId(), 404);
    }

    private function ensureFile(CrmLeadFile $file): void
    {
        abort_unless((int) $file->company_id === (int) $this->context->companyId(), 404);
    }
}
