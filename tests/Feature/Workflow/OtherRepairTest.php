<?php

use App\Enums\AdjustmentReason;
use App\Enums\InvoiceStatus;
use App\Enums\TaskType;
use App\Enums\TransactionMethod;
use App\Enums\TransactionType;
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
 * Feature Test: Other Device Repair (Gaming Console)
 *
 * Real-world scenario:
 * - Customer Mike's PlayStation 5 overheating
 * - Initial: Deep cleaning ($40), Fan replacement ($60), System optimization ($25), New cooling fan ($35)
 * - Customer pays full amount ($160) via card
 * - Problem: Customer returns - PS5 still overheats
 * - Discovery: Defective fan from supplier
 * - Resolution: Re-repair with new fan, no additional charge
 * - Goodwill: Refund $25 for inconvenience, adjust total to $135
 *
 * Tests full payment, problem discovery, refunds, and total adjustments.
 */
it('handles other device repair with defective parts and goodwill refund', function () {
    // SETUP: Create customer and device
    $customer = Customer::factory()->namedAs('Mike Wilson')->create();
    $device = Device::factory()
        ->forCustomer($customer)
        ->namedAs('PlayStation 5', 'Sony')
        ->create();

    // STEP 1: Create ticket and invoice
    $ticket = Ticket::factory()
        ->forDevice($device)
        ->describedAs('Overheating during gameplay, loud fan noise')
        ->create();
    $invoice = Invoice::factory()
        ->forTicket($ticket)
        ->draft()
        ->create();

    // STEP 2: Initial repair plan
    // Deep cleaning task ($40)
    $cleaning = Task::factory()
        ->forTicket($ticket)
        ->ofType(TaskType::Cleaning)
        ->withNote('Remove dust buildup')
        ->billable(40.00)
        ->create();

    // Fan replacement task ($60)
    $fanReplacement = Task::factory()
        ->forTicket($ticket)
        ->ofType(TaskType::Repair)
        ->withNote('Cooling fan replacement')
        ->billable(60.00)
        ->create();

    // System optimization task ($25)
    $optimization = Task::factory()
        ->forTicket($ticket)
        ->ofType(TaskType::Other)
        ->withNote('Firmware update and system optimization')
        ->billable(25.00)
        ->create();

    // New cooling fan order ($35)
    $fanOrder = Order::factory()
        ->forTicket($ticket)
        ->namedAs('PS5 cooling fan assembly')
        ->suppliedBy('Sony Gaming Parts')
        ->billable(35.00)
        ->create();

    // Verify initial totals
    $invoice->refresh();
    expect($invoice->task_total)->toBe(125.0); // 40 + 60 + 25
    expect($invoice->order_total)->toBe(35.0); // 35
    expect($invoice->subtotal)->toBe(160.0); // 125 + 35
    expect($invoice->net_amount)->toBe(160.0); // No discount

    // STEP 3: Set final amount and send invoice
    $invoice->syncTotal()->save(); // total = net_amount = 160.00

    $invoice->update(['status' => InvoiceStatus::Sent]);

    expect($invoice->total)->toBe(160.0);
    expect($invoice->balance)->toBe(160.0);
    expect($invoice->status)->toBe(InvoiceStatus::Sent);

    // STEP 4: Customer payment (Day 1)
    // Customer pays full amount upfront via card
    $payment = Transaction::factory()
        ->forInvoice($invoice)
        ->payment(160.00)
        ->ofMethod(TransactionMethod::Card)
        ->withNote('Full payment for PS5 overheating repair')
        ->create();

    // TransactionObserver updates invoice
    $invoice->refresh();
    expect($invoice->paid_amount)->toBe(160.0);
    expect($invoice->refunded_amount)->toBe(0.0);
    expect($invoice->balance)->toBe(0.0); // 160 - 160 + 0

    // Status should be Paid
    expect($invoice->status)->toBe(InvoiceStatus::Paid);

    // STEP 5: Problem discovery (Day 3)
    // Customer returns - PS5 still overheats after repair
    // This represents a real-world scenario where the initial repair fails

    // STEP 6: Root cause analysis (Day 4)
    // Discovery: The replacement fan was defective from the supplier
    // This is a common issue in repair shops - parts can be DOA (Dead On Arrival)

    // STEP 7: Resolution with goodwill gesture (Day 5)
    // Business decision: Apply damage incident compensation and provide goodwill refund
    // Re-repair with new fan at no additional charge

    // Add damage incident compensation for the defective part
    $damageCompensation = Adjustment::factory()
        ->forInvoice($invoice)
        ->ofReason(AdjustmentReason::DamageIncident)
        ->withAmount(25.00)
        ->create();

    // AdjustmentObserver automatically updates invoice adjustment amounts
    $invoice->refresh();
    expect($invoice->compensation_amount)->toBe(25.00); // Damage incident compensation

    $refund = Transaction::factory()
        ->forInvoice($invoice)
        ->refund(25.00)
        ->ofMethod(TransactionMethod::Card)
        ->withNote('Goodwill refund for defective part inconvenience')
        ->create();

    // TransactionObserver automatically updates invoice
    $invoice->refresh();
    expect($invoice->paid_amount)->toBe(160.0); // Original payment unchanged
    expect($invoice->refunded_amount)->toBe(25.0); // Goodwill refund

    // Balance calculation: total - paid_amount + refunded_amount
    expect($invoice->balance)->toBe(25.0); // 160 - 160 + 25 = 25 (credit to customer)

    // Status should automatically change to Refunded (any refund = Refunded status)
    expect($invoice->status)->toBe(InvoiceStatus::Refunded);

    // STEP 8: Sync total with net amount (no manual adjustment needed)
    // The adjustment system already calculated the proper amounts
    $invoice->syncTotal()->save();

    // STEP 9: Final verification of financial state
    $invoice->refresh();

    // Verify all financial amounts
    expect($invoice->subtotal)->toBe(160.0); // Original service costs unchanged
    expect($invoice->net_amount)->toBe(135.0); // 160 - 25 compensation
    expect($invoice->total)->toBe(135.0); // Synced with net_amount
    expect($invoice->paid_amount)->toBe(160.0); // Customer paid this much
    expect($invoice->refunded_amount)->toBe(25.0); // We refunded this much

    // Balance calculation: total - paid_amount + refunded_amount
    // balance = 135 - 160 + 25 = 0 (perfectly balanced)
    expect($invoice->balance)->toBe(0.0);

    expect($invoice->status)->toBe(InvoiceStatus::Refunded);

    // FINAL VERIFICATION: Complete business scenario analysis

    // 1. Service provided was worth $160 (subtotal/net_amount)
    // 2. Business decided final charge should be $135 (total)
    // 3. Customer paid $160 (paid_amount)
    // 4. Business refunded $25 (refunded_amount)
    // 5. Final settlement: $135 - $160 + $25 = $0 (balanced)

    // Verify the business math makes sense:
    $serviceValue = $invoice->subtotal; // 160.00
    $finalCharge = $invoice->total; // 135.00
    $goodwillDiscount = $serviceValue - $finalCharge; // 25.00
    $customerPaid = $invoice->paid_amount; // 160.00
    $refundIssued = $invoice->refunded_amount; // 25.00
    $netCustomerPaid = $customerPaid - $refundIssued; // 135.00

    expect($goodwillDiscount)->toBe(25.0);
    expect($netCustomerPaid)->toBe($finalCharge); // Customer ultimately paid exactly the final charge
    expect($invoice->balance)->toBe(0.0); // No money owed either way

    // Verify audit trail is complete
    expect($invoice->transactions)->toHaveCount(2);

    $paymentTransaction = $invoice->transactions->where('type', TransactionType::Payment)->first();
    expect($paymentTransaction->amount)->toBe(160.0);
    expect($paymentTransaction->method)->toBe(TransactionMethod::Card);

    $refundTransaction = $invoice->transactions->where('type', TransactionType::Refund)->first();
    expect($refundTransaction->amount)->toBe(25.0);
    expect($refundTransaction->method)->toBe(TransactionMethod::Card);

    // Verify service components and adjustments
    expect($invoice->task_total)->toBe(125.0); // Service tasks unchanged
    expect($invoice->order_total)->toBe(35.0); // Parts cost unchanged
    expect($invoice->subtotal)->toBe(160.0); // Total before adjustments
    expect($invoice->discount_amount)->toBe(0.0); // No discount applied
    expect($invoice->compensation_amount)->toBe(25.0); // Damage incident compensation
    expect($invoice->net_amount)->toBe(135.0); // 160 - 25 compensation

    // Verify relationships and components
    expect($invoice->ticket->id)->toBe($ticket->id);
    expect($invoice->customer()->first()->id)->toBe($customer->id);
    expect($ticket->tasks)->toHaveCount(3);
    expect($ticket->orders)->toHaveCount(1);
    expect($invoice->transactions)->toHaveCount(2); // payment + refund
    expect($invoice->adjustments)->toHaveCount(1); // damage incident compensation
});
