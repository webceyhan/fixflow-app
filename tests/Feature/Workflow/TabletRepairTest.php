<?php

use App\Enums\AdjustmentReason;
use App\Enums\DeviceType;
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
 * Feature Test: Tablet Repair
 *
 * Real-world scenario:
 * - Customer Emma's iPad Pro has charging issues and slow performance
 * - Tasks: Diagnostic ($35), Charging port repair ($85), Tuning/optimization ($40)
 * - Data Service: Backup customer's data before repair ($25)
 * - Orders: New charging port component ($30)
 * - Testing: Post-repair functionality verification ($20)
 * - Payment: Customer pays $235 via online payment
 *
 * Tests tablet-specific repair workflow with data backup and performance optimization.
 */
it('handles tablet repair workflow with data backup and optimization', function () {
    // SETUP: Create customer and device
    $customer = Customer::factory()->namedAs('Emma Wilson')->create();
    $device = Device::factory()
        ->forCustomer($customer)
        ->ofType(DeviceType::Tablet)
        ->namedAs('iPad Pro 12.9"', 'Apple')
        ->create();

    // STEP 1: Create ticket and invoice
    $ticket = Ticket::factory()
        ->forDevice($device)
        ->describedAs('Charging issues and slow performance')
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

    // STEP 2: Start with diagnostic and data backup
    // Diagnostic task ($35)
    $diagnostic = Task::factory()
        ->forTicket($ticket)
        ->ofType(TaskType::Diagnostic)
        ->withNote('Charging and performance analysis')
        ->billable(35.00)
        ->create();

    // Data backup task ($25)
    $backup = Task::factory()
        ->forTicket($ticket)
        ->ofType(TaskType::Backup)
        ->withNote('Customer data backup before repair')
        ->billable(25.00)
        ->create();

    // TaskObserver automatically updates invoice
    $invoice->refresh();
    expect($invoice->task_total)->toBe(60.0); // 35 + 25
    expect($invoice->subtotal)->toBe(60.0);

    // STEP 3: Add repair tasks and parts
    // Charging port repair ($85)
    $repair = Task::factory()
        ->forTicket($ticket)
        ->ofType(TaskType::Repair)
        ->withNote('Charging port replacement')
        ->billable(85.00)
        ->create();

    // Performance tuning ($40)
    $tuning = Task::factory()
        ->forTicket($ticket)
        ->ofType(TaskType::Tuning)
        ->withNote('System optimization and cleanup')
        ->billable(40.00)
        ->create();

    // Charging port component order ($30)
    $portOrder = Order::factory()
        ->forTicket($ticket)
        ->namedAs('iPad Pro charging port assembly')
        ->suppliedBy('Apple Certified Parts')
        ->billable(30.00)
        ->create();

    // OrderObserver automatically updates invoice
    $invoice->refresh();
    expect($invoice->task_total)->toBe(185.0); // 35 + 25 + 85 + 40
    expect($invoice->order_total)->toBe(30.0);
    expect($invoice->subtotal)->toBe(215.0); // 185 + 30

    // STEP 4: Add post-repair testing
    // Testing task ($20)
    $testing = Task::factory()
        ->forTicket($ticket)
        ->ofType(TaskType::Testing)
        ->withNote('Post-repair functionality verification')
        ->billable(20.00)
        ->create();

    // TaskObserver automatically updates invoice
    $invoice->refresh();
    expect($invoice->task_total)->toBe(205.0); // 185 + 20
    expect($invoice->subtotal)->toBe(235.0); // 205 + 30

    // STEP 5: Apply loyalty bonus for returning customer
    // Customer has been with us for 2+ years, apply loyalty bonus
    $loyaltyBonus = Adjustment::factory()
        ->forInvoice($invoice)
        ->ofReason(AdjustmentReason::Loyalty)
        ->withAmount(20.00)
        ->create();

    // AdjustmentObserver automatically updates invoice adjustment amounts
    $invoice->refresh();
    expect($invoice->bonus_amount)->toBe(20.00); // Loyalty bonus

    // STEP 6: Sync total and prepare for payment
    $invoice->syncTotal()->save();
    expect($invoice->subtotal)->toBe(235.0); // Task and order totals
    expect($invoice->net_amount)->toBe(215.0); // 235 - 20 bonus
    expect($invoice->total)->toBe(215.0); // Synced with net_amount
    expect($invoice->balance)->toBe(215.0);

    // STEP 7: Customer pays full amount via online payment
    $payment = Transaction::factory()
        ->forInvoice($invoice)
        ->payment(215.00)
        ->ofMethod(TransactionMethod::Online)
        ->withNote('Full payment for tablet repair with loyalty bonus')
        ->create();

    // TransactionObserver automatically updates invoice financial state
    $invoice->refresh();
    expect($invoice->paid_amount)->toBe(215.0);
    expect($invoice->balance)->toBe(0.0);
    expect($invoice->status)->toBe(InvoiceStatus::Paid);

    // STEP 8: Final verification - Invoice is complete
    expect($invoice->task_total)->toBe(205.0);
    expect($invoice->order_total)->toBe(30.0);
    expect($invoice->subtotal)->toBe(235.0);
    expect($invoice->discount_amount)->toBe(0.0);
    expect($invoice->bonus_amount)->toBe(20.0); // Loyalty bonus
    expect($invoice->net_amount)->toBe(215.0); // 235 - 20 bonus
    expect($invoice->total)->toBe(215.0);
    expect($invoice->paid_amount)->toBe(215.0);
    expect($invoice->refunded_amount)->toBe(0.0);
    expect($invoice->balance)->toBe(0.0);
    expect($invoice->status)->toBe(InvoiceStatus::Paid);

    // Verify all components
    expect($ticket->tasks)->toHaveCount(5); // diagnostic, backup, repair, tuning, testing
    expect($ticket->orders)->toHaveCount(1); // charging port
    expect($invoice->transactions)->toHaveCount(1); // payment
    expect($invoice->adjustments)->toHaveCount(1); // loyalty bonus
});
