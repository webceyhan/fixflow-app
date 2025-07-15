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
 * Feature Test: Wearable Repair
 *
 * Real-world scenario:
 * - Customer Lisa's Apple Watch Series 8 has battery drain and water damage
 * - Tasks: Diagnostic ($25), Water damage cleaning ($40), Battery replacement ($65), Sensor tuning ($30)
 * - Recovery: Health data recovery from corrupted watch ($35)
 * - Orders: New battery ($25), waterproof seals ($15)
 * - Testing: Fitness tracking and water resistance testing ($20)
 * - Customer concern: Watch is still under warranty but water damage voids it
 * - Resolution: Charge for water damage repair only, waive other fees
 * - Payment: Customer pays $115 via card after negotiation
 *
 * Tests wearable-specific workflow with warranty considerations and fee waivers.
 */
it('handles wearable repair workflow with warranty considerations', function () {
    // SETUP: Create customer and device
    $customer = Customer::factory()->namedAs('Lisa Rodriguez')->create();
    $device = Device::factory()
        ->forCustomer($customer)
        ->ofType(DeviceType::Wearable)
        ->withWarranty()
        ->namedAs('Apple Watch Series 8', 'Apple')
        ->create();

    // STEP 1: Create ticket and invoice
    $ticket = Ticket::factory()
        ->forDevice($device)
        ->describedAs('Battery draining quickly, suspected water damage')
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

    // STEP 2: Initial diagnostic and assessment
    // Diagnostic task ($25)
    $diagnostic = Task::factory()
        ->forTicket($ticket)
        ->ofType(TaskType::Diagnostic)
        ->withNote('Water damage assessment and battery analysis')
        ->billable(25.00)
        ->create();

    // Water damage cleaning ($40)
    $cleaning = Task::factory()
        ->forTicket($ticket)
        ->ofType(TaskType::Cleaning)
        ->withNote('Water damage cleaning and corrosion removal')
        ->billable(40.00)
        ->create();

    // TaskObserver automatically updates invoice
    $invoice->refresh();
    expect($invoice->task_total)->toBe(65.0); // 25 + 40
    expect($invoice->subtotal)->toBe(65.0);

    // STEP 3: Repair services
    // Battery replacement ($65)
    $batteryRepair = Task::factory()
        ->forTicket($ticket)
        ->ofType(TaskType::Repair)
        ->withNote('Battery replacement due to water damage')
        ->billable(65.00)
        ->create();

    // Data recovery ($35)
    $recovery = Task::factory()
        ->forTicket($ticket)
        ->ofType(TaskType::Recovery)
        ->withNote('Health and fitness data recovery from damaged sectors')
        ->billable(35.00)
        ->create();

    // Sensor tuning ($30)
    $tuning = Task::factory()
        ->forTicket($ticket)
        ->ofType(TaskType::Tuning)
        ->withNote('Heart rate and fitness sensor recalibration')
        ->billable(30.00)
        ->create();

    // STEP 4: Add replacement parts
    // New battery ($25)
    $batteryOrder = Order::factory()
        ->forTicket($ticket)
        ->namedAs('Apple Watch Series 8 Battery')
        ->suppliedBy('Apple Parts Direct')
        ->billable(25.00)
        ->create();

    // Waterproof seals ($15)
    $sealOrder = Order::factory()
        ->forTicket($ticket)
        ->namedAs('Waterproof gaskets and seals')
        ->suppliedBy('Watch Repair Supplies Co.')
        ->billable(15.00)
        ->create();

    // OrderObserver automatically updates invoice
    $invoice->refresh();
    expect($invoice->task_total)->toBe(195.0); // 25 + 40 + 65 + 35 + 30
    expect($invoice->order_total)->toBe(40.0); // 25 + 15
    expect($invoice->subtotal)->toBe(235.0); // 195 + 40

    // STEP 5: Add testing
    // Testing task ($20)
    $testing = Task::factory()
        ->forTicket($ticket)
        ->ofType(TaskType::Testing)
        ->withNote('Water resistance and fitness tracking verification')
        ->billable(20.00)
        ->create();

    // TaskObserver automatically updates invoice
    $invoice->refresh();
    expect($invoice->task_total)->toBe(215.0); // 195 + 20
    expect($invoice->subtotal)->toBe(255.0); // 215 + 40

    // STEP 6: Business decision - Warranty consideration
    // Water damage voids warranty, but waive some fees for customer satisfaction
    // Make diagnostic and tuning non-billable (warranty-like treatment)
    $diagnostic->update(['is_billable' => false]);
    $tuning->update(['is_billable' => false]);

    // TaskObserver automatically updates invoice
    $invoice->refresh();
    expect($invoice->task_total)->toBe(160.0); // 215 - 25 - 30
    expect($invoice->subtotal)->toBe(200.0); // 160 + 40

    // STEP 7: Apply additional goodwill discount
    $goodwillDiscount = Adjustment::factory()
        ->forInvoice($invoice)
        ->ofReason(AdjustmentReason::ServiceDelay)
        ->withAmount(85.00)
        ->create();

    // AdjustmentObserver automatically updates invoice adjustment amounts
    $invoice->refresh();
    expect($invoice->compensation_amount)->toBe(85.00); // Service delay compensation

    // STEP 8: Sync total
    $invoice->syncTotal()->save();
    expect($invoice->total)->toBe(115.0); // 200 - 85
    expect($invoice->net_amount)->toBe(115.0);
    expect($invoice->balance)->toBe(115.0);

    // STEP 9: Customer pays negotiated amount
    $payment = Transaction::factory()
        ->forInvoice($invoice)
        ->payment(115.00)
        ->ofMethod(TransactionMethod::Card)
        ->withNote('Payment after warranty consideration and goodwill discount')
        ->create();

    // TransactionObserver automatically updates invoice
    $invoice->refresh();
    expect($invoice->paid_amount)->toBe(115.0);
    expect($invoice->balance)->toBe(0.0);
    expect($invoice->status)->toBe(InvoiceStatus::Paid);

    // STEP 10: Final verification - Invoice is complete
    expect($invoice->task_total)->toBe(160.0); // Only billable tasks
    expect($invoice->order_total)->toBe(40.0);
    expect($invoice->subtotal)->toBe(200.0);
    expect($invoice->discount_amount)->toBe(0.0); // No discount applied
    expect($invoice->compensation_amount)->toBe(85.0); // Service delay compensation
    expect($invoice->net_amount)->toBe(115.0); // 200 - 85 compensation
    expect($invoice->total)->toBe(115.0);
    expect($invoice->paid_amount)->toBe(115.0);
    expect($invoice->refunded_amount)->toBe(0.0);
    expect($invoice->balance)->toBe(0.0);
    expect($invoice->status)->toBe(InvoiceStatus::Paid);

    // Verify all components
    expect($ticket->tasks)->toHaveCount(6); // diagnostic, cleaning, repair, recovery, tuning, testing
    expect($ticket->orders)->toHaveCount(2); // battery, seals
    expect($invoice->transactions)->toHaveCount(1); // payment
    expect($invoice->adjustments)->toHaveCount(1); // service delay compensation

    // Verify warranty device handling
    expect($device->hasWarranty())->toBeTrue();

    // Verify non-billable tasks (warranty consideration)
    expect($ticket->tasks->where('is_billable', false))->toHaveCount(2); // diagnostic, tuning
    expect($ticket->tasks->where('is_billable', true))->toHaveCount(4); // cleaning, repair, recovery, testing
});
