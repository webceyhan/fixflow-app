<?php

use App\Enums\AttachmentCategory;
use App\Models\Attachment;
use App\Models\Ticket;
use App\Models\User;
use App\Support\Attachments\AttachmentThumbnailPath;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;

it('deletes a ticket attachment from database and storage', function () {
    // Arrange
    Storage::fake('local');
    $user = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();
    $attachment = Attachment::factory()->forTicket($ticket)->create([
        'path' => "tickets/{$ticket->id}/delete-me.pdf",
    ]);
    Storage::disk('local')->put($attachment->path, 'file-content');

    // Act
    $response = actingAs($user)->delete(route('tickets.attachments.destroy', [$ticket, $attachment]));

    // Assert
    $response->assertRedirect(route('tickets.show', $ticket));
    expect($attachment->fresh())->toBeNull();
    Storage::disk('local')->assertMissing($attachment->path);
});

it('deletes a photo thumbnail from storage when deleting photo attachment', function () {
    // Arrange
    Storage::fake('local');
    $user = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();
    $attachment = Attachment::factory()->forTicket($ticket)->create([
        'path' => "tickets/{$ticket->id}/delete-me.jpg",
        'mime_type' => 'image/jpeg',
        'category' => AttachmentCategory::Photo,
    ]);

    $thumbnailPath = AttachmentThumbnailPath::fromAttachment($attachment);
    Storage::disk('local')->put($attachment->path, 'photo-content');
    Storage::disk('local')->put($thumbnailPath, 'thumb-content');

    // Act
    $response = actingAs($user)->delete(route('tickets.attachments.destroy', [$ticket, $attachment]));

    // Assert
    $response->assertRedirect(route('tickets.show', $ticket));
    Storage::disk('local')->assertMissing($attachment->path);
    Storage::disk('local')->assertMissing($thumbnailPath);
});

it('returns 404 when deleting attachment from another ticket', function () {
    // Arrange
    $user = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();
    $anotherTicket = Ticket::factory()->create();
    $attachment = Attachment::factory()->forTicket($anotherTicket)->create();

    // Act
    $response = actingAs($user)->delete(route('tickets.attachments.destroy', [$ticket, $attachment]));

    // Assert
    $response->assertNotFound();
});
