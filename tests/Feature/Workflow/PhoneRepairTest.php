<?php

use App\Enums\AdjustmentReason;
use App\Enums\InvoiceStatus;
use App\Enums\TaskType;
use App\Enums\TransactionMethod;
use App\Models\Adjustment;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Task;
use App\Models\Ticket;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Feature Test: Phone Screen Repair
 *
 * Real-world scenario:
 * - Customer John brings cracked iPhone 14
 * - Tasks: Screen replacement ($80), Assembly labor ($20)
 * - Orders: New screen part ($45)
 * - Discount: First-time customer 10% off ($14.50)
 * - Payment: Customer pays $130.50 cash on completion
 *
 * Tests the complete invoice flow from creation to payment completion.
 */
it('handles phone screen repair workflow', function () {
    // SETUP: Create customer and device
    $customer = Customer::factory()->namedAs('John Doe')->create();
    $device = Device::factory()
        ->forCustomer($customer)
        ->namedAs('iPhone 14')
        ->create();

    // STEP 1: Create ticket and invoice
    $ticket = Ticket::factory()
        ->forDevice($device)
        ->describedAs('Cracked screen repair')
        ->create();
    $invoice = Invoice::factory()
        ->forTicket($ticket)
        ->draft()
        ->create();

    // Initial state: Empty invoice
    expect($invoice->subtotal)->toBe(0.0);
    expect($invoice->net_amount)->toBe(0.0);
    expect($invoice->total)->toBe(0.0);
    expect($invoice->balance)->toBe(0.0);
    expect($invoice->status)->toBe(InvoiceStatus::Draft);

    // STEP 2: Add repair tasks
    // Screen replacement task ($80)
    $screenTask = Task::factory()
        ->forTicket($ticket)
        ->ofType(TaskType::Repair)
        ->withNote('Screen replacement')
        ->billable(80.00)
        ->create();

    // TaskObserver automatically updates invoice task_total
    $invoice->refresh();
    expect($invoice->task_total)->toBe(80.0);
    expect($invoice->subtotal)->toBe(80.0);

    // Labor task ($20)
    $laborTask = Task::factory()
        ->forTicket($ticket)
        ->ofType(TaskType::Repair)
        ->withNote('Assembly labor')
        ->billable(20.00)
        ->create();

    // TaskObserver automatically updates invoice task_total
    $invoice->refresh();
    expect($invoice->task_total)->toBe(100.0); // 80 + 20
    expect($invoice->subtotal)->toBe(100.0);

    // STEP 3: Add parts order
    // New screen part ($45)
    $screenOrder = Order::factory()
        ->forTicket($ticket)
        ->namedAs('iPhone 14 screen part')
        ->suppliedBy('iFixit Parts Co.')
        ->billable(45.00)
        ->create();

    // OrderObserver automatically updates invoice order_total
    $invoice->refresh();
    expect($invoice->order_total)->toBe(45.0);
    expect($invoice->subtotal)->toBe(145.0); // 100 + 45
    expect($invoice->net_amount)->toBe(145.0); // No discount yet

    // STEP 4: Apply first-time customer discount
    // 10% discount = $14.50
    $firstTimeDiscount = Adjustment::factory()
        ->forInvoice($invoice)
        ->ofReason(AdjustmentReason::Promotion)
        ->withPercentage(10.0)
        ->create();

    // AdjustmentObserver automatically updates invoice adjustment amounts
    $invoice->refresh();
    expect($invoice->discount_amount)->toBe(14.50); // 10% of 145.0

    // Computed attributes should update
    expect($invoice->subtotal)->toBe(145.0); // task_total + order_total
    expect($invoice->net_amount)->toBe(130.5); // subtotal - discount_amount

    // STEP 5: Sync total with net_amount (standard automatic mode)
    $invoice->syncTotal()->save();

    expect($invoice->total)->toBe(130.5); // Synced with net_amount
    expect($invoice->balance)->toBe(130.5); // total - paid + refunded = 130.5 - 0 + 0

    // STEP 6: Send invoice to customer (Day 2)
    $invoice->update(['status' => InvoiceStatus::Sent]);
    expect($invoice->status)->toBe(InvoiceStatus::Sent);

    // STEP 7: Customer payment (Day 3)
    // Customer pays full amount in cash
    $payment = Transaction::factory()
        ->forInvoice($invoice)
        ->payment(130.50)
        ->ofMethod(TransactionMethod::Cash)
        ->withNote('Full payment for iPhone screen repair')
        ->create();

    // TransactionObserver automatically updates invoice financial amounts
    $invoice->refresh();
    expect($invoice->paid_amount)->toBe(130.5);
    expect($invoice->refunded_amount)->toBe(0.0);

    // Balance should be zero (fully paid)
    expect($invoice->balance)->toBe(0.0); // 130.5 - 130.5 + 0

    // STEP 8: Verify automatic status calculation
    expect($invoice->status)->toBe(InvoiceStatus::Paid);

    // FINAL VERIFICATION: Complete invoice state
    expect($invoice->task_total)->toBe(100.0); // Screen replacement + Labor
    expect($invoice->order_total)->toBe(45.0); // Screen part
    expect($invoice->subtotal)->toBe(145.0); // Total before discount
    expect($invoice->discount_amount)->toBe(14.5); // 10% first-time customer discount
    expect($invoice->net_amount)->toBe(130.5); // After discount
    expect($invoice->total)->toBe(130.5); // Final amount (auto-synced)
    expect($invoice->paid_amount)->toBe(130.5);
    expect($invoice->refunded_amount)->toBe(0.0);
    expect($invoice->balance)->toBe(0.0);
    expect($invoice->status)->toBe(InvoiceStatus::Paid);

    // Verify all components
    expect($ticket->tasks)->toHaveCount(2); // screen replacement + assembly
    expect($ticket->orders)->toHaveCount(1); // screen part
    expect($invoice->transactions)->toHaveCount(1); // cash payment
    expect($invoice->adjustments)->toHaveCount(1); // first-time discount

    // Verify relationships work correctly
    expect($invoice->ticket->id)->toBe($ticket->id);
    expect($invoice->customer()->first()->id)->toBe($customer->id);
    expect($invoice->transactions)->toHaveCount(1);
    expect($invoice->transactions->first()->amount)->toBe(130.5);
});
