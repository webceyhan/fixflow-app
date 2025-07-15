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
 * Feature Test: Desktop Repair
 *
 * Real-world scenario:
 * - Customer David's gaming PC randomly shuts down during games
 * - Tasks: Diagnostic ($45), Deep cleaning ($60), PSU repair ($120), Performance tuning ($50)
 * - Upgrades: New graphics card ($450), Additional RAM ($180)
 * - Installation: GPU and RAM installation ($80)
 * - Testing: Stress testing and benchmarking ($40)
 * - Discount: Bulk service discount 8% ($83.20)
 * - Payment: Partial payment $500 cash, remaining $522.80 via card
 *
 * Tests desktop-specific workflow with upgrades, multiple payments, and bulk discount.
 */
it('handles desktop repair workflow with upgrades and multiple payments', function () {
    // SETUP: Create customer and device
    $customer = Customer::factory()->namedAs('David Chen')->create();
    $device = Device::factory()
        ->forCustomer($customer)
        ->ofType(DeviceType::Desktop)
        ->namedAs('Custom Gaming PC', 'Custom Build')
        ->create();

    // STEP 1: Create ticket and invoice
    $ticket = Ticket::factory()
        ->forDevice($device)
        ->describedAs('Random shutdowns during gaming, possible PSU issue')
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

    // STEP 2: Diagnostic and cleaning services
    // Diagnostic task ($45)
    $diagnostic = Task::factory()
        ->forTicket($ticket)
        ->ofType(TaskType::Diagnostic)
        ->withNote('Gaming performance analysis and component testing')
        ->billable(45.00)
        ->create();

    // Deep cleaning task ($60)
    $cleaning = Task::factory()
        ->forTicket($ticket)
        ->ofType(TaskType::Cleaning)
        ->withNote('Complete system cleaning and dust removal')
        ->billable(60.00)
        ->create();

    // TaskObserver automatically updates invoice
    $invoice->refresh();
    expect($invoice->task_total)->toBe(105.0); // 45 + 60
    expect($invoice->subtotal)->toBe(105.0);

    // STEP 3: Repair and upgrade services
    // PSU repair ($120)
    $psuRepair = Task::factory()
        ->forTicket($ticket)
        ->ofType(TaskType::Repair)
        ->withNote('Power supply unit repair and capacitor replacement')
        ->billable(120.00)
        ->create();

    // Performance tuning ($50)
    $tuning = Task::factory()
        ->forTicket($ticket)
        ->ofType(TaskType::Tuning)
        ->withNote('System optimization and overclocking setup')
        ->billable(50.00)
        ->create();

    // GPU and RAM installation ($80)
    $installation = Task::factory()
        ->forTicket($ticket)
        ->ofType(TaskType::Installation)
        ->withNote('Graphics card and RAM installation with driver setup')
        ->billable(80.00)
        ->create();

    // System upgrade planning and consultation ($35)
    $upgrade = Task::factory()
        ->forTicket($ticket)
        ->ofType(TaskType::Upgrade)
        ->withNote('Hardware upgrade consultation and system compatibility analysis')
        ->billable(35.00)
        ->create();

    // STEP 4: Add upgrade components
    // Graphics card upgrade ($450)
    $gpuOrder = Order::factory()
        ->forTicket($ticket)
        ->namedAs('NVIDIA RTX 4070 Graphics Card')
        ->suppliedBy('NVIDIA Authorized Dealer')
        ->billable(450.00)
        ->create();

    // RAM upgrade ($180)
    $ramOrder = Order::factory()
        ->forTicket($ticket)
        ->namedAs('32GB DDR4 RAM Kit (2x16GB)')
        ->suppliedBy('Corsair Memory Solutions')
        ->billable(180.00)
        ->create();

    // OrderObserver automatically updates invoice
    $invoice->refresh();
    expect($invoice->task_total)->toBe(390.0); // 45 + 60 + 120 + 50 + 80 + 35
    expect($invoice->order_total)->toBe(630.0); // 450 + 180
    expect($invoice->subtotal)->toBe(1020.0); // 390 + 630

    // STEP 5: Add final testing
    // Stress testing ($40)
    $testing = Task::factory()
        ->forTicket($ticket)
        ->ofType(TaskType::Testing)
        ->withNote('Stress testing and gaming performance benchmarking')
        ->billable(40.00)
        ->create();

    // TaskObserver automatically updates invoice
    $invoice->refresh();
    expect($invoice->task_total)->toBe(430.0); // 390 + 40
    expect($invoice->subtotal)->toBe(1060.0); // 430 + 630

    // STEP 6: Apply bulk service discount (8%)
    $bulkDiscount = Adjustment::factory()
        ->forInvoice($invoice)
        ->ofReason(AdjustmentReason::BulkService)
        ->withPercentage(8.0)
        ->create();

    // AdjustmentObserver automatically updates invoice adjustment amounts
    $invoice->refresh();
    expect($invoice->discount_amount)->toBe(84.80); // 8% of 1060 = 84.80

    // STEP 7: Sync total
    $invoice->syncTotal()->save();
    expect($invoice->total)->toBe(975.20); // 1060 - 84.80
    expect($invoice->net_amount)->toBe(975.20);
    expect($invoice->balance)->toBe(975.20);

    // STEP 8: Customer makes partial payment ($500 cash)
    $partialPayment = Transaction::factory()
        ->forInvoice($invoice)
        ->payment(500.00)
        ->ofMethod(TransactionMethod::Cash)
        ->withNote('Partial payment - cash')
        ->create();

    // TransactionObserver automatically updates invoice
    $invoice->refresh();
    expect($invoice->paid_amount)->toBe(500.0);
    expect(round($invoice->balance, 2))->toBe(475.20); // 975.20 - 500
    expect($invoice->status)->toBe(InvoiceStatus::Sent); // Partial payment

    // STEP 9: Customer pays remaining balance ($475.20 via card)
    $finalPayment = Transaction::factory()
        ->forInvoice($invoice)
        ->payment(475.20)
        ->ofMethod(TransactionMethod::Card)
        ->withNote('Final payment - remaining balance')
        ->create();

    // TransactionObserver automatically updates invoice
    $invoice->refresh();
    expect($invoice->paid_amount)->toBe(975.20);
    expect($invoice->balance)->toBe(0.0);
    expect($invoice->status)->toBe(InvoiceStatus::Paid);

    // STEP 10: Final verification - Invoice is complete
    expect($invoice->task_total)->toBe(430.0);
    expect($invoice->order_total)->toBe(630.0);
    expect($invoice->subtotal)->toBe(1060.0);
    expect($invoice->discount_amount)->toBe(84.80);
    expect($invoice->net_amount)->toBe(975.20);
    expect($invoice->total)->toBe(975.20);
    expect($invoice->paid_amount)->toBe(975.20);
    expect($invoice->refunded_amount)->toBe(0.0);
    expect($invoice->balance)->toBe(0.0);
    expect($invoice->status)->toBe(InvoiceStatus::Paid);

    // Verify all components
    expect($ticket->tasks)->toHaveCount(7); // diagnostic, cleaning, repair, tuning, installation, upgrade, testing
    expect($ticket->orders)->toHaveCount(2); // GPU, RAM
    expect($invoice->transactions)->toHaveCount(2); // partial + final payment
    expect($invoice->adjustments)->toHaveCount(1); // bulk service discount
});
