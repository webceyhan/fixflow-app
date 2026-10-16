<?php

use App\Enums\AttachmentCategory;
use App\Jobs\GenerateAttachmentThumbnail;
use App\Models\Attachment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;

it('stores a ticket attachment', function () {
    // Arrange
    Storage::fake('local');
    Queue::fake();
    $user = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();
    $file = UploadedFile::fake()->create('invoice.pdf', 256, 'application/pdf');

    // Act
    $response = actingAs($user)->post(route('tickets.attachments.store', $ticket), [
        'file' => $file,
    ]);

    // Assert
    $response->assertRedirect(route('tickets.show', $ticket));

    $attachment = Attachment::query()->latest()->first();

    expect($attachment)->not->toBeNull()
        ->and($attachment->attachable_type)->toBe(Ticket::class)
        ->and($attachment->attachable_id)->toBe($ticket->id)
        ->and($attachment->name)->toBe('invoice.pdf')
        ->and($attachment->category)->toBe(AttachmentCategory::Other);

    Storage::disk('local')->assertExists($attachment->path);
    Queue::assertNotPushed(GenerateAttachmentThumbnail::class);
});

it('validates file is required', function () {
    // Arrange
    $user = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();

    // Act
    $response = actingAs($user)->post(route('tickets.attachments.store', $ticket), []);

    // Assert
    $response->assertSessionHasErrors(['file']);
});

it('validates allowed file types', function () {
    // Arrange
    $user = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();
    $file = UploadedFile::fake()->create('payload.exe', 8, 'application/x-msdownload');

    // Act
    $response = actingAs($user)->post(route('tickets.attachments.store', $ticket), [
        'file' => $file,
    ]);

    // Assert
    $response->assertSessionHasErrors(['file']);
});

it('validates file size limit', function () {
    // Arrange
    $user = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();
    $file = UploadedFile::fake()->create('huge.pdf', 12 * 1024, 'application/pdf');

    // Act
    $response = actingAs($user)->post(route('tickets.attachments.store', $ticket), [
        'file' => $file,
    ]);

    // Assert
    $response->assertSessionHasErrors(['file']);
});

it('ignores category input and determines category from uploaded file mime type', function () {
    // Arrange
    Storage::fake('local');
    Queue::fake();
    $user = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();
    $file = UploadedFile::fake()->image('device.jpg');

    // Act
    actingAs($user)->post(route('tickets.attachments.store', $ticket), [
        'file' => $file,
        'category' => AttachmentCategory::Other->value,
    ]);

    // Assert
    $attachment = Attachment::query()->latest()->first();

    expect($attachment)->not->toBeNull()
        ->and($attachment->category)->toBe(AttachmentCategory::Photo);

    Queue::assertPushed(GenerateAttachmentThumbnail::class);
});

it('defaults category to photo for image files when category is omitted', function () {
    // Arrange
    Storage::fake('local');
    Queue::fake();
    $user = User::factory()->manager()->create();
    $ticket = Ticket::factory()->create();
    $file = UploadedFile::fake()->image('device.jpg');

    // Act
    actingAs($user)->post(route('tickets.attachments.store', $ticket), [
        'file' => $file,
    ]);

    // Assert
    $attachment = Attachment::query()->latest()->first();

    expect($attachment)->not->toBeNull()
        ->and($attachment->category)->toBe(AttachmentCategory::Photo);

    Queue::assertPushed(GenerateAttachmentThumbnail::class);
});
