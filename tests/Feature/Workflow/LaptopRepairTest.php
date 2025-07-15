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
 * Feature Test: Laptop Repair with Changes
 *
 * Real-world scenario:
 * - Customer Sarah's MacBook won't boot
 * - Initial: Diagnostic ($50), Motherboard repair ($150), RAM upgrade ($80), Thermal paste ($15)
 * - Complications: Motherboard beyond repair, needs replacement ($250)
 * - Discovery: RAM is fine, cancel RAM order
 * - Business decision: Waive diagnostic fee for inconvenience
 * - Payment: Partial payment ($200), then final payment ($65)
 *
 * Tests invoice changes, manual total adjustments, and partial payments.
 */
it('handles laptop repair with service changes and manual adjustments', function () {
    // SETUP: Create customer and device
    $customer = Customer::factory()->namedAs('Sarah Johnson')->create();
    $device = Device::factory()
        ->forCustomer($customer)
        ->namedAs('MacBook Pro 13"')
        ->create();

    // STEP 1: Create ticket and invoice
    $ticket = Ticket::factory()
        ->forDevice($device)
        ->describedAs('Won\'t boot, possible motherboard issue')
        ->create();
    $invoice = Invoice::factory()
        ->forTicket($ticket)
        ->draft()
        ->create();

    // STEP 2: Initial service assessment
    // Diagnostic task ($50)
    $diagnostic = Task::factory()
        ->forTicket($ticket)
        ->ofType(TaskType::Diagnostic)
        ->withNote('Boot failure analysis')
        ->billable(50.00)
        ->create();

    // Initial motherboard repair task ($150)
    $repair = Task::factory()
        ->forTicket($ticket)
        ->ofType(TaskType::Repair)
        ->withNote('Motherboard repair')
        ->billable(150.00)
        ->create();

    // RAM upgrade order ($80)
    $ramOrder = Order::factory()
        ->forTicket($ticket)
        ->namedAs('RAM upgrade 16GB')
        ->suppliedBy('Crucial Memory Store')
        ->billable(80.00)
        ->create();

    // Thermal paste order ($15)
    $thermalPaste = Order::factory()
        ->forTicket($ticket)
        ->namedAs('Thermal paste')
        ->suppliedBy('Arctic Cooling Supplies')
        ->billable(15.00)
        ->create();

    // Verify initial totals
    $invoice->refresh();
    expect($invoice->task_total)->toBe(200.0); // 50 + 150
    expect($invoice->order_total)->toBe(95.0); // 80 + 15
    expect($invoice->subtotal)->toBe(295.0); // 200 + 95
    expect($invoice->net_amount)->toBe(295.0); // No discount

    // STEP 3: Sync initial total
    $invoice->syncTotal()->save();
    expect($invoice->total)->toBe(295.0);

    // STEP 4: Real-world complication - Motherboard beyond repair
    // Day 1: Diagnostic reveals motherboard needs replacement, not repair
    $repair->update([
        'note' => 'Motherboard replacement',
        'cost' => 250.00, // Higher cost for replacement
    ]);

    // TaskObserver automatically updates invoice
    $invoice->refresh();
    expect($invoice->task_total)->toBe(300.0); // 50 + 250
    expect($invoice->subtotal)->toBe(395.0); // 300 + 95
    expect($invoice->net_amount)->toBe(395.0);

    // STEP 5: Another discovery - RAM is fine, cancel RAM order
    // Day 2: During repair, discover existing RAM is perfectly fine
    $ramOrder->update(['is_billable' => false]); // Cancel RAM order

    // OrderObserver automatically updates invoice
    $invoice->refresh();
    expect($invoice->order_total)->toBe(15.0); // Only thermal paste now
    expect($invoice->subtotal)->toBe(315.0); // 300 + 15
    expect($invoice->net_amount)->toBe(315.0);

    // STEP 6: Business decision - Waive diagnostic fee for customer inconvenience
    // The multiple changes and complications warrant waiving the diagnostic fee
    // Apply service delay compensation for the complications
    $serviceDelayCompensation = Adjustment::factory()
        ->forInvoice($invoice)
        ->ofReason(AdjustmentReason::ServiceDelay)
        ->withAmount(50.00)
        ->create();

    // AdjustmentObserver automatically updates invoice adjustment amounts
    $invoice->refresh();
    expect($invoice->compensation_amount)->toBe(50.00); // Service delay compensation

    // System calculated amounts
    expect($invoice->subtotal)->toBe(315.0); // Unchanged subtotal
    expect($invoice->net_amount)->toBe(265.0); // 315 - 50 compensation

    // STEP 7: Sync total with net amount
    $invoice->syncTotal()->save();
    expect($invoice->total)->toBe(265.0); // Synced with net_amount
    expect($invoice->balance)->toBe(265.0);

    // STEP 8: Send invoice to customer
    $invoice->update(['status' => InvoiceStatus::Sent]);

    // STEP 9: Partial payment process
    // Day 4: Customer makes partial payment
    $payment1 = Transaction::factory()
        ->forInvoice($invoice)
        ->payment(200.00)
        ->ofMethod(TransactionMethod::Card)
        ->withNote('Partial payment - MacBook repair')
        ->create();

    // TransactionObserver updates invoice
    $invoice->refresh();
    expect($invoice->paid_amount)->toBe(200.0);
    expect($invoice->balance)->toBe(65.0); // 265 - 200 + 0

    // Status should be Sent (partial payment)
    expect($invoice->status)->toBe(InvoiceStatus::Sent);

    // STEP 9: Final payment
    // Day 10: Customer pays remaining balance
    $payment2 = Transaction::factory()
        ->forInvoice($invoice)
        ->payment(65.00)
        ->ofMethod(TransactionMethod::Card)
        ->withNote('Final payment - MacBook repair completion')
        ->create();

    // TransactionObserver updates invoice
    $invoice->refresh();
    expect($invoice->paid_amount)->toBe(265.0); // 200 + 65
    expect($invoice->refunded_amount)->toBe(0.0);
    expect($invoice->balance)->toBe(0.0); // 265 - 265 + 0

    // Status should be Paid (fully paid)
    expect($invoice->status)->toBe(InvoiceStatus::Paid);

    // FINAL VERIFICATION: Complete invoice state after all changes
    expect($invoice->task_total)->toBe(300.0); // Diagnostic + Motherboard replacement
    expect($invoice->order_total)->toBe(15.0); // Only thermal paste (RAM cancelled)
    expect($invoice->subtotal)->toBe(315.0); // Total before any adjustments
    expect($invoice->discount_amount)->toBe(0.0); // No discount applied
    expect($invoice->compensation_amount)->toBe(50.0); // Service delay compensation
    expect($invoice->net_amount)->toBe(265.0); // 315 - 50 compensation
    expect($invoice->total)->toBe(265.0); // Synced with net_amount
    expect($invoice->paid_amount)->toBe(265.0); // Full payment received
    expect($invoice->refunded_amount)->toBe(0.0); // No refunds
    expect($invoice->balance)->toBe(0.0); // Fully settled
    expect($invoice->status)->toBe(InvoiceStatus::Paid);

    // Verify components
    expect($invoice->transactions)->toHaveCount(2); // partial + final payment
    expect($invoice->adjustments)->toHaveCount(1); // service delay compensation
    expect($invoice->transactions->sum('amount'))->toBe(265.0);

    // Verify final task/order states
    $finalTasks = $ticket->tasks;
    expect($finalTasks)->toHaveCount(2);
    expect($finalTasks->where('is_billable', true)->sum('cost'))->toBe(300.0);

    $finalOrders = $ticket->orders;
    expect($finalOrders)->toHaveCount(2);
    expect($finalOrders->where('is_billable', true)->sum('cost'))->toBe(15.0); // Only thermal paste
    expect($finalOrders->where('is_billable', false))->toHaveCount(1); // RAM cancelled
});
