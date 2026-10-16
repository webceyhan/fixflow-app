<?php

use App\Enums\AttachmentCategory;
use App\Models\Attachment;
use App\Models\Ticket;
use App\Models\User;
use App\Support\Attachments\AttachmentThumbnailPath;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;

it('downloads a ticket attachment', function () {
    // Arrange
    Storage::fake('local');
    $user = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();
    $attachment = Attachment::factory()->forTicket($ticket)->create([
        'name' => 'report.pdf',
        'path' => "tickets/{$ticket->id}/report.pdf",
        'mime_type' => 'application/pdf',
        'category' => AttachmentCategory::Other,
    ]);

    Storage::disk('local')->put($attachment->path, 'pdf-content');

    // Act
    $response = actingAs($user)->get(route('tickets.attachments.download', [$ticket, $attachment]));

    // Assert
    $response->assertOk()
        ->assertHeader('content-disposition', 'attachment; filename=report.pdf');
});

it('returns 404 when attachment does not belong to ticket', function () {
    // Arrange
    $user = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();
    $anotherTicket = Ticket::factory()->create();
    $attachment = Attachment::factory()->forTicket($anotherTicket)->create();

    // Act
    $response = actingAs($user)->get(route('tickets.attachments.download', [$ticket, $attachment]));

    // Assert
    $response->assertNotFound();
});

it('returns 404 when file is missing from storage', function () {
    // Arrange
    Storage::fake('local');
    $user = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();
    $attachment = Attachment::factory()->forTicket($ticket)->create([
        'path' => "tickets/{$ticket->id}/missing.pdf",
    ]);

    // Act
    $response = actingAs($user)->get(route('tickets.attachments.download', [$ticket, $attachment]));

    // Assert
    $response->assertNotFound();
});

it('previews a ticket photo attachment inline', function () {
    // Arrange
    Storage::fake('local');
    $user = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();
    $attachment = Attachment::factory()->forTicket($ticket)->create([
        'name' => 'device.jpg',
        'path' => "tickets/{$ticket->id}/device.jpg",
        'mime_type' => 'image/jpeg',
        'category' => AttachmentCategory::Photo,
    ]);

    Storage::disk('local')->put($attachment->path, 'fake-jpeg');

    // Act
    $response = actingAs($user)->get(route('tickets.attachments.preview', [$ticket, $attachment]));

    // Assert
    $response->assertOk()
        ->assertHeader('content-disposition', 'inline; filename="device.jpg"');
});

it('returns 404 when previewing a non-photo attachment', function () {
    // Arrange
    Storage::fake('local');
    $user = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();
    $attachment = Attachment::factory()->forTicket($ticket)->create([
        'name' => 'report.pdf',
        'path' => "tickets/{$ticket->id}/report.pdf",
        'mime_type' => 'application/pdf',
        'category' => AttachmentCategory::Other,
    ]);

    Storage::disk('local')->put($attachment->path, 'fake-pdf');

    // Act
    $response = actingAs($user)->get(route('tickets.attachments.preview', [$ticket, $attachment]));

    // Assert
    $response->assertNotFound();
});

it('previews a ticket photo thumbnail inline', function () {
    // Arrange
    Storage::fake('local');
    $user = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();
    $attachment = Attachment::factory()->forTicket($ticket)->create([
        'name' => 'device.jpg',
        'path' => "tickets/{$ticket->id}/device.jpg",
        'mime_type' => 'image/jpeg',
        'category' => AttachmentCategory::Photo,
    ]);

    Storage::disk('local')->put(
        AttachmentThumbnailPath::fromAttachment($attachment),
        'fake-thumbnail',
    );

    // Act
    $response = actingAs($user)->get(route('tickets.attachments.thumbnail', [$ticket, $attachment]));

    // Assert
    $response->assertOk()
        ->assertHeader('content-disposition', 'inline; filename="device.jpg"');
});

it('falls back to original image when thumbnail file is missing from storage', function () {
    // Arrange
    Storage::fake('local');
    $user = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();
    $attachment = Attachment::factory()->forTicket($ticket)->create([
        'name' => 'device.jpg',
        'path' => "tickets/{$ticket->id}/device.jpg",
        'mime_type' => 'image/jpeg',
        'category' => AttachmentCategory::Photo,
    ]);
    Storage::disk('local')->put($attachment->path, 'fake-jpeg');

    // Act
    $response = actingAs($user)->get(route('tickets.attachments.thumbnail', [$ticket, $attachment]));

    // Assert
    $response->assertOk()
        ->assertHeader('content-disposition', 'inline; filename="device.jpg"');
});

it('returns 404 when previewing a thumbnail for a non-photo attachment', function () {
    // Arrange
    Storage::fake('local');
    $user = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();
    $attachment = Attachment::factory()->forTicket($ticket)->create([
        'name' => 'report.pdf',
        'path' => "tickets/{$ticket->id}/report.pdf",
        'mime_type' => 'application/pdf',
        'category' => AttachmentCategory::Other,
    ]);

    Storage::disk('local')->put(
        AttachmentThumbnailPath::fromAttachment($attachment),
        'fake-thumbnail',
    );

    // Act
    $response = actingAs($user)->get(route('tickets.attachments.thumbnail', [$ticket, $attachment]));

    // Assert
    $response->assertNotFound();
});
