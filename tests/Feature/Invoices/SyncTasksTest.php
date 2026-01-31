<?php

use App\Actions\Invoices\SyncInvoiceTasks;
use App\Models\Invoice;
use App\Models\Task;

it('syncs task_total when tasks are added', function () {
    // Arrange
    $invoice = Invoice::factory()->create(['task_total' => 0]);
    $ticket = $invoice->ticket;

    // Act - Create billable tasks
    Task::factory()->billable()->create([
        'ticket_id' => $ticket->id,
        'cost' => 100,
    ]);
    Task::factory()->billable()->create([
        'ticket_id' => $ticket->id,
        'cost' => 50,
    ]);

    app(SyncInvoiceTasks::class)->handle($invoice);

    // Assert
    expect($invoice->fresh()->task_total)->toBe(150.0);
});

it('syncs task_total when tasks are updated', function () {
    // Arrange
    $invoice = Invoice::factory()->create(['task_total' => 100]);
    $ticket = $invoice->ticket;
    $task = Task::factory()->billable()->create([
        'ticket_id' => $ticket->id,
        'cost' => 100,
    ]);

    // Act - Update task cost
    $task->update(['cost' => 200]);
    app(SyncInvoiceTasks::class)->handle($invoice);

    // Assert
    expect($invoice->fresh()->task_total)->toBe(200.0);
});

it('syncs task_total when tasks are deleted', function () {
    // Arrange
    $invoice = Invoice::factory()->create(['task_total' => 150]);
    $ticket = $invoice->ticket;
    $task1 = Task::factory()->billable()->create([
        'ticket_id' => $ticket->id,
        'cost' => 100,
    ]);
    $task2 = Task::factory()->billable()->create([
        'ticket_id' => $ticket->id,
        'cost' => 50,
    ]);

    // Act - Delete one task
    $task1->delete();
    app(SyncInvoiceTasks::class)->handle($invoice);

    // Assert
    expect($invoice->fresh()->task_total)->toBe(50.0);
});

it('only counts billable tasks in task_total', function () {
    // Arrange
    $invoice = Invoice::factory()->create(['task_total' => 0]);
    $ticket = $invoice->ticket;

    // Act - Create mix of billable and non-billable tasks
    Task::factory()->billable()->create([
        'ticket_id' => $ticket->id,
        'cost' => 100,
    ]);
    Task::factory()->notBillable()->create([
        'ticket_id' => $ticket->id,
        'cost' => 50,
    ]);

    app(SyncInvoiceTasks::class)->handle($invoice);

    // Assert
    expect($invoice->fresh()->task_total)->toBe(100.0);
});

it('handles null invoice gracefully', function () {
    // Arrange & Act & Assert - Should not throw error
    app(SyncInvoiceTasks::class)->handle(null);
    expect(true)->toBeTrue();
});

it('calculates task_total as zero when no billable tasks exist', function () {
    // Arrange
    $invoice = Invoice::factory()->create(['task_total' => 500]);
    $ticket = $invoice->ticket;

    // Create only non-billable tasks
    Task::factory()->notBillable()->create([
        'ticket_id' => $ticket->id,
        'cost' => 100,
    ]);

    // Act
    app(SyncInvoiceTasks::class)->handle($invoice);

    // Assert
    expect($invoice->fresh()->task_total)->toBe(0.0);
});
