<?php

namespace App\Http\Controllers;

use App\Actions\Attachments\CreateAttachment;
use App\Actions\Attachments\DeleteAttachment;
use App\Enums\AttachmentCategory;
use App\Http\Requests\TicketAttachments\TicketAttachmentStoreRequest;
use App\Jobs\GenerateAttachmentThumbnail;
use App\Models\Attachment;
use App\Models\Ticket;
use App\Support\Attachments\AttachmentThumbnailPath;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TicketAttachmentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(
        TicketAttachmentStoreRequest $request,
        Ticket $ticket,
        CreateAttachment $createAttachment
    ): RedirectResponse {
        $attachment = $createAttachment->handle($ticket, $request->validated());

        if ($attachment->category === AttachmentCategory::Photo) {
            GenerateAttachmentThumbnail::dispatch($attachment->id);
        }

        return redirect()->route('tickets.show', $ticket);
    }

    /**
     * Download the specified resource from storage.
     */
    public function download(Ticket $ticket, Attachment $attachment): StreamedResponse
    {
        $this->ensureBelongsToTicket($ticket, $attachment);

        abort_unless(Storage::disk('local')->exists($attachment->path), 404);

        return Storage::disk('local')->download($attachment->path, $attachment->name);
    }

    /**
     * Preview the specified image attachment in browser.
     */
    public function preview(Ticket $ticket, Attachment $attachment): BinaryFileResponse
    {
        $this->ensureBelongsToTicket($ticket, $attachment);

        abort_unless($attachment->category === AttachmentCategory::Photo, 404);
        abort_unless(Storage::disk('local')->exists($attachment->path), 404);

        return response()->file(
            Storage::disk('local')->path($attachment->path),
            [
                'Content-Disposition' => 'inline; filename="'.$attachment->name.'"',
            ]
        );
    }

    /**
     * Preview the thumbnail for the specified image attachment.
     */
    public function thumbnail(Ticket $ticket, Attachment $attachment): BinaryFileResponse
    {
        $this->ensureBelongsToTicket($ticket, $attachment);

        abort_unless($attachment->category === AttachmentCategory::Photo, 404);

        $thumbnailPath = AttachmentThumbnailPath::fromAttachment($attachment);
        $disk = Storage::disk('local');
        $path = $disk->exists($thumbnailPath) ? $thumbnailPath : $attachment->path;

        abort_unless($disk->exists($path), 404);

        return response()->file(
            $disk->path($path),
            [
                'Content-Disposition' => 'inline; filename="'.$attachment->name.'"',
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Ticket $ticket,
        Attachment $attachment,
        DeleteAttachment $deleteAttachment
    ): RedirectResponse {
        $this->ensureBelongsToTicket($ticket, $attachment);

        $deleteAttachment->handle($attachment);

        return redirect()->route('tickets.show', $ticket);
    }

    private function ensureBelongsToTicket(Ticket $ticket, Attachment $attachment): void
    {
        abort_unless(
            $attachment->attachable_type === Ticket::class
            && $attachment->attachable_id === $ticket->id,
            404
        );
    }
}
