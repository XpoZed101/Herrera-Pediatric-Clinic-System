<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Client\PatientController as ClientPatientController;
use App\Http\Controllers\Admin\ConsultationController as AdminConsultationController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\MedicalRecordController as AdminMedicalRecordController;
use App\Http\Controllers\Admin\DiagnosisController as AdminDiagnosisController;
use App\Http\Controllers\Admin\PrescriptionController as AdminPrescriptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\VisitTypeController as AdminVisitTypeController;
use App\Http\Controllers\Client\BillingController as ClientBillingController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

// Public site pages
Route::view('/', 'pages.home')->name('home');
Route::view('/about', 'pages.about')->name('about');
Route::view('/features', 'pages.features')->name('features');
Route::view('/sections', 'pages.sections')->name('sections');
// Ensure /login points to Volt auth page to avoid Fortify view binding error
Route::redirect('/login', '/auth/login');

// Patient registration flow
use App\Http\Controllers\PatientRegistrationController;
use App\Http\Controllers\Admin\PatientController as AdminPatientController;

Route::get('/register', [PatientRegistrationController::class, 'show'])->name('register.show');
Route::get('/register/step/{step}', [PatientRegistrationController::class, 'show'])->name('register.step.show');
Route::post('/register/step/1', [PatientRegistrationController::class, 'storeStep1'])->name('register.step1.store');
Route::post('/register/step/2', [PatientRegistrationController::class, 'storeStep2'])->name('register.step2.store');
Route::post('/register/step/3', [PatientRegistrationController::class, 'storeStep3'])->name('register.step3.store');
Route::post('/register/step/4', [PatientRegistrationController::class, 'storeStep4'])->name('register.step4.store');
// OTP endpoints for step 1 verification
Route::post('/register/verify-otp', [PatientRegistrationController::class, 'verifyOtp'])->name('register.verify-otp');
Route::post('/register/resend-otp', [PatientRegistrationController::class, 'resendOtp'])->name('register.resend-otp');

Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::post('dashboard/reminders/send', [\App\Http\Controllers\DashboardController::class, 'sendReminders'])
    ->middleware(['auth'])
    ->name('dashboard.reminders.send');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');
    // Patient-only contact info settings page
    Volt::route('settings/contact-info', 'settings.contact-info')->name('contact-info.edit');
    Volt::route('settings/clinic-rules', 'settings.clinic-rules')->name('clinic-rules.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    // Staff welcome route
    Route::get('/staff', [\App\Http\Controllers\StaffController::class, 'welcome'])->name('staff.welcome');

    // Staff routes
    Route::prefix('staff')->name('staff.')->group(function () {
        // Appointments
        Route::get('/appointments', [\App\Http\Controllers\Staff\AppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/{appointment}', [\App\Http\Controllers\Staff\AppointmentController::class, 'show'])->name('appointments.show')->whereNumber('appointment');
        Route::post('/appointments/{appointment}/check-in', [\App\Http\Controllers\Staff\AppointmentController::class, 'checkIn'])->name('appointments.check-in')->whereNumber('appointment');
        Route::post('/appointments/{appointment}/check-out', [\App\Http\Controllers\Staff\AppointmentController::class, 'checkOut'])->name('appointments.check-out')->whereNumber('appointment');
        Route::post('/appointments/{appointment}/vitals', [\App\Http\Controllers\Staff\AppointmentController::class, 'storeVitals'])->name('appointments.vitals')->whereNumber('appointment');
Route::post('/appointments/{appointment}/status', [\App\Http\Controllers\Staff\AppointmentController::class, 'updateStatus'])->name('appointments.update-status')->whereNumber('appointment');
Route::post('/appointments/{appointment}/email', [\App\Http\Controllers\Staff\AppointmentController::class, 'email'])->name('appointments.email')->whereNumber('appointment');

        // Billing & Payments
        Route::get('/billing', [\App\Http\Controllers\Staff\BillingController::class, 'index'])->name('billing.index');
        Route::get('/billing/payments/{payment}', [\App\Http\Controllers\Staff\BillingController::class, 'show'])->name('billing.payments.show')->whereNumber('payment');
        Route::post('/billing/payments/{payment}/mark-paid', [\App\Http\Controllers\Staff\BillingController::class, 'markPaid'])->name('billing.payments.mark-paid')->whereNumber('payment');
        Route::post('/billing/appointments/{appointment}/create-payment', [\App\Http\Controllers\Staff\BillingController::class, 'createPayment'])->name('billing.appointments.create-payment')->whereNumber('appointment');

        // Patient registration and handling
        Route::get('/patients', [\App\Http\Controllers\Staff\PatientController::class, 'index'])->name('patients.index');
        Route::get('/patients/create', [\App\Http\Controllers\Staff\PatientController::class, 'create'])->name('patients.create');
        Route::post('/patients', [\App\Http\Controllers\Staff\PatientController::class, 'store'])->name('patients.store');
        Route::get('/patients/{patient}', [\App\Http\Controllers\Staff\PatientController::class, 'show'])->name('patients.show')->whereNumber('patient');
        Route::get('/patients/{patient}/edit', [\App\Http\Controllers\Staff\PatientController::class, 'edit'])->name('patients.edit')->whereNumber('patient');
        Route::put('/patients/{patient}', [\App\Http\Controllers\Staff\PatientController::class, 'update'])->name('patients.update')->whereNumber('patient');

        // Reports (PDF)
        Route::get('/reports/appointments/pdf', [\App\Http\Controllers\Staff\ReportController::class, 'appointmentsPdf'])->name('reports.appointments.pdf');
        Route::get('/reports/payments/pdf', [\App\Http\Controllers\Staff\ReportController::class, 'paymentsPdf'])->name('reports.payments.pdf');

        // Phone Inquiries
        Route::get('/phone-inquiries', [\App\Http\Controllers\Staff\PhoneInquiryController::class, 'index'])->name('phone-inquiries.index');
        Route::get('/phone-inquiries/create', [\App\Http\Controllers\Staff\PhoneInquiryController::class, 'create'])->name('phone-inquiries.create');
        Route::post('/phone-inquiries', [\App\Http\Controllers\Staff\PhoneInquiryController::class, 'store'])->name('phone-inquiries.store');
        Route::get('/phone-inquiries/{phoneInquiry}', [\App\Http\Controllers\Staff\PhoneInquiryController::class, 'show'])->name('phone-inquiries.show')->whereNumber('phoneInquiry');
        Route::post('/phone-inquiries/{phoneInquiry}/status', [\App\Http\Controllers\Staff\PhoneInquiryController::class, 'updateStatus'])->name('phone-inquiries.update-status')->whereNumber('phoneInquiry');
        Route::post('/phone-inquiries/{phoneInquiry}/convert-to-appointment', [\App\Http\Controllers\Staff\PhoneInquiryController::class, 'convertToAppointment'])->name('phone-inquiries.convert-to-appointment')->whereNumber('phoneInquiry');

        // Queue management
        Route::get('/queue', [\App\Http\Controllers\Staff\QueueController::class, 'index'])->name('queue.index');
        Route::post('/queue/reorder', [\App\Http\Controllers\Staff\QueueController::class, 'reorder'])->name('queue.reorder');

        // Waitlist management
        Route::get('/waitlist', [\App\Http\Controllers\Staff\WaitlistController::class, 'index'])->name('waitlist.index');
        Route::post('/waitlist', [\App\Http\Controllers\Staff\WaitlistController::class, 'store'])->name('waitlist.store');
        Route::post('/waitlist/{entry}/status', [\App\Http\Controllers\Staff\WaitlistController::class, 'updateStatus'])->name('waitlist.update-status')->whereNumber('entry');
        Route::delete('/waitlist/{entry}', [\App\Http\Controllers\Staff\WaitlistController::class, 'destroy'])->name('waitlist.destroy')->whereNumber('entry');

        // Inventory management
        Route::get('/inventory', [\App\Http\Controllers\Staff\InventoryController::class, 'index'])->name('inventory.index');
        Route::get('/inventory/create', [\App\Http\Controllers\Staff\InventoryController::class, 'create'])->name('inventory.create');
        Route::post('/inventory', [\App\Http\Controllers\Staff\InventoryController::class, 'store'])->name('inventory.store');
        Route::get('/inventory/{item}', [\App\Http\Controllers\Staff\InventoryController::class, 'show'])->name('inventory.show')->whereNumber('item');
        Route::get('/inventory/{item}/edit', [\App\Http\Controllers\Staff\InventoryController::class, 'edit'])->name('inventory.edit')->whereNumber('item');
        Route::put('/inventory/{item}', [\App\Http\Controllers\Staff\InventoryController::class, 'update'])->name('inventory.update')->whereNumber('item');
        Route::post('/inventory/{item}/adjust', [\App\Http\Controllers\Staff\InventoryController::class, 'adjust'])->name('inventory.adjust')->whereNumber('item');


    });

    // Grouped admin routes
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        // Admin dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        // Charts polling endpoint
        Route::get('/dashboard/stats', [\App\Http\Controllers\Admin\DashboardController::class, 'stats'])->name('dashboard.stats');
        Route::get('/dashboard/stats', [\App\Http\Controllers\Admin\DashboardController::class, 'stats'])->name('dashboard.stats');

        // Doctor queue view
        Route::get('/doctor-queue', \App\Livewire\Admin\DoctorQueue::class)->name('doctor.queue');

        // Staff management
        Route::get('/staff', [\App\Http\Controllers\Admin\StaffController::class, 'index'])->name('staff.index');
        Route::get('/staff/create', [\App\Http\Controllers\Admin\StaffController::class, 'create'])->name('staff.create');
        Route::post('/staff', [\App\Http\Controllers\Admin\StaffController::class, 'store'])->name('staff.store');
        Route::get('/staff/{id}/edit', [\App\Http\Controllers\Admin\StaffController::class, 'edit'])->name('staff.edit');
        Route::put('/staff/{id}', [\App\Http\Controllers\Admin\StaffController::class, 'update'])->name('staff.update');
        Route::delete('/staff/{id}', [\App\Http\Controllers\Admin\StaffController::class, 'destroy'])->name('staff.destroy');
        // Appointments management for admin
        Route::get('/appointments', [AdminAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/{appointment}', [AdminAppointmentController::class, 'show'])->name('appointments.show');
        Route::get('/appointments/{appointment}/edit', [AdminAppointmentController::class, 'edit'])->name('appointments.edit');
        Route::put('/appointments/{appointment}', [AdminAppointmentController::class, 'update'])->name('appointments.update');
        Route::post('/appointments/{appointment}/status', [AdminAppointmentController::class, 'updateStatus'])->name('appointments.update-status');
        Route::post('/appointments/{appointment}/email', [AdminAppointmentController::class, 'email'])->name('appointments.email');
        // Medical records under appointments
        Route::get('/appointments/{appointment}/medical-record/create', [AdminMedicalRecordController::class, 'create'])
            ->name('appointments.medical-record.create')
            ->whereNumber('appointment');
        Route::post('/appointments/{appointment}/medical-record', [AdminMedicalRecordController::class, 'store'])
            ->name('appointments.medical-record.store')
            ->whereNumber('appointment');
        Route::get('/medical-records/{medicalRecord}', [AdminMedicalRecordController::class, 'show'])
            ->name('medical-records.show')
            ->whereNumber('medicalRecord');
        Route::get('/medical-records/{medicalRecord}/edit', [AdminMedicalRecordController::class, 'edit'])
            ->name('medical-records.edit')
            ->whereNumber('medicalRecord');
        Route::put('/medical-records/{medicalRecord}', [AdminMedicalRecordController::class, 'update'])
            ->name('medical-records.update')
            ->whereNumber('medicalRecord');
        // PDF documents for medical records
        Route::get('/medical-records/{medicalRecord}/certificate/pdf', [AdminMedicalRecordController::class, 'certificatePdf'])
            ->name('medical-records.certificate.pdf')
            ->whereNumber('medicalRecord');
        Route::get('/medical-records/{medicalRecord}/clearance/pdf', [AdminMedicalRecordController::class, 'clearancePdf'])
            ->name('medical-records.clearance.pdf')
            ->whereNumber('medicalRecord');

        // Index pages
        Route::get('/medical-records', [AdminMedicalRecordController::class, 'index'])->name('medical-records.index');
        Route::get('/medical-records/create', [AdminMedicalRecordController::class, 'createSelector'])->name('medical-records.create');
        Route::post('/medical-records/start', [AdminMedicalRecordController::class, 'startCreate'])->name('medical-records.start');
        Route::get('/diagnoses', [AdminDiagnosisController::class, 'index'])->name('diagnoses.index');
        Route::get('/prescriptions', [AdminPrescriptionController::class, 'index'])->name('prescriptions.index');
        Route::get('/prescriptions/create', [AdminPrescriptionController::class, 'create'])->name('prescriptions.create');
        Route::post('/prescriptions', [AdminPrescriptionController::class, 'store'])->name('prescriptions.store');
        Route::get('/prescriptions/{prescription}/edit', [AdminPrescriptionController::class, 'edit'])->name('prescriptions.edit');
        Route::put('/prescriptions/{prescription}', [AdminPrescriptionController::class, 'update'])->name('prescriptions.update');
        Route::delete('/prescriptions/{prescription}', [AdminPrescriptionController::class, 'destroy'])->name('prescriptions.destroy');
        Route::get('/prescriptions/{prescription}/pdf', [AdminPrescriptionController::class, 'pdf'])->name('prescriptions.pdf');
        Route::get('/patients', [AdminPatientController::class, 'index'])->name('patients.index');
        Route::delete('/patients/{patient}', [AdminPatientController::class, 'destroy'])->name('patients.destroy');
        Route::get('/patients/{patient}', [AdminPatientController::class, 'show'])->name('patients.show');

        // Patient consultations
        Route::get('/patients/{patient}/consultations/create', [AdminConsultationController::class, 'create'])->name('patients.consultations.create');
        Route::post('/patients/{patient}/consultations', [AdminConsultationController::class, 'store'])->name('patients.consultations.store');

        // Consultations index and start
        Route::get('/consultations', [AdminConsultationController::class, 'index'])->name('consultations.index');
        Route::post('/consultations/start', [AdminConsultationController::class, 'start'])->name('consultations.start');

        // Visit types management
        Route::resource('/visit-types', AdminVisitTypeController::class);
    });
});

require __DIR__ . '/auth.php';
// Grouped client routes
Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {
    Route::view('/home', 'client.home')->name('home');
    // Add Billing history route
    Route::get('/billing', [ClientBillingController::class, 'history'])->name('billing.history');
    // Appointments
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/available-times', [AppointmentController::class, 'availableTimes'])->name('appointments.available-times');
    // Client-driven reschedule and cancellation
    Route::get('/appointments/{appointment}/reschedule', [AppointmentController::class, 'rescheduleForm'])->name('appointments.reschedule')->whereNumber('appointment');
    Route::put('/appointments/{appointment}/reschedule', [AppointmentController::class, 'rescheduleUpdate'])->name('appointments.reschedule.update')->whereNumber('appointment');
    Route::post('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel')->whereNumber('appointment');

    // Payments (PayMongo Checkout)
    Route::get('/appointments/{appointment}/pay', [PaymentController::class, 'checkout'])->name('payments.checkout')->whereNumber('appointment');
    Route::get('/payments/{payment}/success', [PaymentController::class, 'success'])->name('payments.success')->whereNumber('payment');
    Route::get('/payments/{payment}/cancel', [PaymentController::class, 'cancel'])->name('payments.cancel')->whereNumber('payment');

    // Child records
    Route::get('/medical-history', [ClientPatientController::class, 'medicalHistory'])->name('medical-history');
    // Preview page for printable medical history
    Route::get('/medical-history/preview', [ClientPatientController::class, 'medicalHistoryPreview'])->name('medical-history.preview');
    // PDF download for medical history
    Route::get('/medical-history/pdf', [ClientPatientController::class, 'medicalHistoryPdf'])->name('medical-history.pdf');
    Route::get('/immunizations', [ClientPatientController::class, 'immunizations'])->name('immunizations');

    // New: Prescriptions page
    Route::get('/prescriptions', [ClientPatientController::class, 'prescriptions'])->name('prescriptions');
    // PDF download for prescriptions
    Route::get('/prescriptions/pdf', [ClientPatientController::class, 'prescriptionsPdf'])->name('prescriptions.pdf');

    // Client documents: certificates and clearances
    Route::get('/documents/certificates', [ClientPatientController::class, 'certificates'])->name('certificates');
    Route::get('/documents/clearances', [ClientPatientController::class, 'clearances'])->name('clearances');
    Route::get('/medical-records/{medicalRecord}/certificate/pdf', [ClientPatientController::class, 'certificatePdf'])->name('medical-records.certificate.pdf')->whereNumber('medicalRecord');
    Route::get('/medical-records/{medicalRecord}/clearance/pdf', [ClientPatientController::class, 'clearancePdf'])->name('medical-records.clearance.pdf')->whereNumber('medicalRecord');

    // New: Visit Summary details page for a specific medical record
    Route::get('/medical-records/{medicalRecord}/summary', [ClientPatientController::class, 'visitSummary'])->name('medical-records.summary')->whereNumber('medicalRecord');

    // New: Appointment history page
    Route::get('/appointments/history', [ClientPatientController::class, 'appointmentHistory'])->name('appointments.history');

    // Contact information (guardian/contact details)
    Route::get('/contact-info', [ClientPatientController::class, 'contactInfo'])->name('contact-info');
    Route::put('/contact-info', [ClientPatientController::class, 'updateContactInfo'])->name('contact-info.update');

    // Messaging: non-urgent queries to clinic
    Route::get('/messages', [ClientPatientController::class, 'messages'])->name('messages');
    Route::post('/messages', [ClientPatientController::class, 'sendMessage'])->name('messages.send');


});
