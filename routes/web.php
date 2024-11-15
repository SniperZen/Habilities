<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EditProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TherapistController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ShareUserData;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InquiryController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Http\Controllers\NotificationController;
use App\Models\Appointment;



Route::post('/mark-notification-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

    Route::middleware(['auth'])->group(function () {
        Route::get('/get-more-notifications/{offset}', [NotificationController::class, 'getMoreNotifications'])
            ->name('notifications.more');
    });
    

// Apply ShareUserData middleware to all routes
Route::middleware([ShareUserData::class])->group(function () {
    // Public routes
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/account-type', function () {
        return view('auth.account_type_selection');
    })->middleware('guest')->name('account.type.selection');

    Route::get('/link', function(){
        Artisan::call('storage:link');
    });

    // Authentication required routes
    Route::middleware(['auth'])->group(function () {
        // Dashboard route (moved outside of 'verified' middleware)
        Route::get('/dashboard', [UserController::class, 'index'])->middleware('user')->name('dashboard');
        
        // Routes that require email verification
        Route::middleware(['verified'])->group(function () {

        Route::controller(AdminController::class)->middleware(['admin'])->group(function () {
            //Route::get('admin/dashboard', [HomeController::class, 'index'])->middleware('admin')->name('admin.dashboard');
            Route::get('/admin/dashboard', [AdminController::class, 'showDashboard'])->name('admin.dashboard');
            Route::get('/admin/dash', [AdminController::class, 'dash'])->name('admin.dash');
            Route::get('/admin/usersTherapist', [AdminController::class, 'usersTherapist'])->name('admin.usersTherapist');
            Route::get('/admin/usersPatient', [AdminController::class, 'usersPatient'])->name('admin.usersPatient');
            Route::get('/admin/report', [AdminController::class, 'report'])->name('admin.report');
            Route::get('therapist/dashboard', [TherapistController::class, 'index'])->middleware('therapist')->name('therapist.dashboard');
            Route::post('/admin/users/{id}/deactivate', [AdminController::class, 'deactivate'])->name('admin.users.deactivate');
            Route::post('/admin/users/{id}/activate', [AdminController::class, 'activate'])->name('admin.users.activate');
            Route::post('/admin/users/{id}/update-usertype', [AdminController::class, 'updateUsertype']);
            Route::patch('/admin/updateUser/{id}', [AdminController::class, 'updateUser'])->name('updateUser');
            //Route::get('/admin/chat', [AdminController::class,'chat'])->middleware('admin')->name('admin.chat');
            Route::get('/admin/chat/{id?}', [AdminController::class, 'chat'])->name('admin.chat');
            Route::get('/admin/therapycenter', [AdminController::class,'therapycenter'])->middleware('admin')->name('admin.therapycenter');
            //Route::get('/admin/editTCenter', [AdminController::class,'editTCenter'])->middleware('admin')->name('admin.editTCenter');
            Route::get('/admin/dashboard-counts', [AdminController::class, 'getDashboardCounts']);
            Route::get('/admin/onsite-appointments-data', [AdminController::class, 'getOnsiteAppointmentsData'])->name('admin.onsite-appointments-data');
            Route::get('/admin/teletherapy-appointments-data', [AdminController::class, 'getTeleAppointmentsData'])->name('admin.teletherapy-appointments-data');

            Route::get('/admin/report', [AdminController::class, 'showReport'])->name('admin.report');
            Route::get('/admin/system-usage', [AdminController::class, 'systemUsage'])->middleware(['auth', 'admin']);
            
            Route::get('/admin/edit-center', [AdminController::class, 'editTCenter'])->name('admin.editTCenter');
            Route::post('/admin/update-center', [AdminController::class, 'updateCenter'])->name('admin.updateCenter');

            
        });

        
            // Profile routes
            Route::controller(ProfileController::class)->group(function () {
                Route::get('/profile', 'show')->name('profile.show');
                Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
                Route::post('/profile/upload-image', [ProfileController::class, 'uploadImage'])->name('profile.upload-image');
                Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
            });
            
            Route::controller(EditProfileController::class)->group(function () {
                Route::get('/profile/edit-profile', 'edit')->name('edit-profile.edit');
                Route::patch('/profile', 'update')->name('edit-profile.update');
                Route::delete('/profile', 'destroy')->name('edit-profile.destroy');
                Route::put('/profile/password', 'updatePassword')->name('edit-profile.update-password');
            });


             // Patient routes
        Route::controller(PatientController::class)->middleware(['user'])->group(function () {
            Route::get('/patient/profile', 'profile')->name('patient.profile');
            Route::get('/patient/edit-profile', 'editprof')->name('patient.edit-profile');
            Route::get('/patient/AppReq', 'AppReq')->name('patient.AppReq');
            Route::get('/patient/CompApp', 'CompApp')->name('patient.CompApp');
            Route::get('/therapists', [PatientController::class, 'AppReq'])->name('therapists.index');
            Route::get('/appointment/{id}', [PatientController::class, 'CompApp'])->name('patient.CompApp');
            Route::get('/patient/wait-conf/{id}', [PatientController::class, 'WaitConf'])->name('patient.WaitConf');
            Route::get('/patient/chat', 'chat')->name('patient.chat');
            Route::get('/patient/inquiry', 'inquiry')->name('patient.inquiry');
            Route::get('/patient/inquiry01', 'inquiry01')->name('patient.inquiry01');
            Route::get('/patient/inquiry2', 'inquiry2')->name('patient.inquiry2');
            Route::get('/patient/inquiry3', 'inquiry3')->name('patient.inquiry3');
            Route::get('/patient/dash', 'dash')->name('patient.dash');
            Route::get('/patient/appntmnt', 'appntmnt')->name('patient.appntmnt');
            Route::get('/patient/appntmnt', [PatientController::class, 'showAppointments'])->name('patient.appntmnt');
            Route::get('/patient/dash', [PatientController::class, 'showAppointmentsDash'])->name('patient.dash');
            Route::get('/patient/notification', 'notification')->name('patient.notification');
            Route::get('/patient/p-feedback', [PatientController::class, 'feedback'])->name('patient.p-feedback');
            Route::get('/patient/p-help', [PatientController::class, 'help'])->name('patient.p-help');
            Route::get('/patient/p-about', [PatientController::class, 'about'])->name('patient.p-about');
            Route::get('/patient/appointments', [PatientController::class, 'showAppointments'])->name('patient.appointments');
            Route::get('/patient/changespass', [PatientController::class, 'changespass'])->name('patient.changespass');
            Route::post('/patient/feedback', [PatientController::class, 'store'])->name('patient.feedback.store');
            Route::post('/patient/cancel-appointment/{id}', [PatientController::class, 'cancelAppointment'])->name('patient.cancelAppointment');
            Route::get('/patient/history', [PatientController::class, 'myHistory'])->name('patient.myHistory');


        

            

        });

        


        Route::middleware(['auth'])->group(function () {
            //Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
            Route::post('/appointments/store', [AppointmentController::class, 'store'])->name('appointments.store');
            Route::get('/appointments/create/{therapistId}', [AppointmentController::class, 'create'])->name('appointments.create');
            Route::get('/appointments/{id}', 'AppointmentController@show');
            Route::get('/get-appointments', [AppointmentController::class, 'getAppointments'])->name('get.appointments');
            Route::get('/get-therapist_appointments', [AppointmentController::class, 'getTherapistAppointments'])->name('get.therapist_appointments');
            

            Route::middleware(['update.appointments'])->group(function () {
            Route::get('/appointments', [AppointmentController::class, 'index']);
            Route::get('/appointments/{id}', [AppointmentController::class, 'view']);
        
        


            });
        });
       


        

        //Therapist route
        Route::controller(TherapistController::class)->middleware(['therapist'])->group(function () {
            Route::get('/therapist/AppReq', 'AppReq')->name('therapist.AppReq');
            Route::get('/therapist/AppReq2', 'AppReq2')->name('therapist.AppReq2');
            Route::get('/therapist/AppSched', 'AppSched')->name('therapist.AppSched');
            Route::get('/therapist/app-req', [TherapistController::class, 'appReq'])->name('therapist.AppReq');
            Route::get('/therapist/appreq2/{id}', [TherapistController::class, 'AppReq2'])->name('therapist.AppReq2');
            Route::post('/therapist/appreq2/{id}/decline', [TherapistController::class, 'declineRequest'])->name('therapist.declineRequest');
            Route::post('/therapist/appreq2/{id}/add', [TherapistController::class, 'addAppointment'])->name('therapist.addAppointment');
            Route::post('/therapist/cancel-appointment/{id}', [TherapistController::class, 'cancelAppointment'])->name('therapist.cancelAppointment');
            Route::post('/appointments/{id}/finish', [TherapistController::class, 'finishAppointment'])->name('appointments.finish');            
            Route::get('/therapist/profile', 'profile')->name('therapist.profile');
            Route::get('/therapist/profile', [TherapistController::class, 'profile'])->name('therapist.profile');
            Route::get('/therapist/edit', 'edit')->name('therapist.edit');
            //Route::get('/therapist/chat', 'chat')->name('therapist.chat');
            Route::get('/therapist/chat/{id?}', [TherapistController::class, 'chat'])->name('therapist.chat');

            Route::get('/therapist/inquiry', 'inquiry')->name('therapist.inquiry');
            Route::get('/therapist/inquirymess', 'inquirymess')->name('therapist.inquirymess');
            Route::get('/therapist/dash', 'dash')->name('therapist.dash');
            Route::get('/therapist/feedback', 'feedback')->name('therapist.feedback');
            Route::get('/therapist/feedback2', 'feedback2')->name('therapist.feedback2');
            Route::get('/therapist/feedback3', 'feedback3')->name('therapist.feedback3');
            Route::get('/therapist/notification', 'notification')->name('therapist.notification');
            Route::get('/search-users', [TherapistController::class, 'searchUsers']);
            Route::get('/therapist/changespass', 'changespass')->name('therapist.changespass');
            Route::middleware('auth')->group(function () {
            Route::post('/feedback', [TherapistController::class, 'store'])->name('feedback.store');
            Route::get('/therapist/feedback', [TherapistController::class, 'fbview'])->name('therapist.feedback');
            Route::put('/therapist/appointments/{appointmentId}', [TherapistController::class, 'updateAppointment'])->name('therapist.updateAppointment');
            Route::delete('/feedback/{id}', [TherapistController::class, 'destroy'])->name('feedback.destroy');
            Route::get('/therapist/t-feedback', 'feedbackt')->name('therapist.t-feedback');
            Route::get('/therapist/t-help', 'help')->name('therapist.t-help');
            Route::get('/therapist/t-about', 'about')->name('therapist.t-about');
            Route::get('/therapist/feedback2', [TherapistController::class, 'feedback2'])->name('therapist.feedback2');
            Route::get('/therapist/myHistory', [TherapistController::class, 'myHistory'])->name('therapist.myHistory');
            Route::get('/therapist/inquiry', [InquiryController::class, 'showInquiries'])->name('therapist.inquiry');
            Route::post('/therapist/feedback', [TherapistController::class, 'stores'])->name('therapist.feedback.stores');
            Route::get('/therapist/my-history', [TherapistController::class, 'myHistory'])->name('therapist.myHistory');




        });


            });
        });

        Route::middleware(['auth'])->group(function () {
            Route::post('/patient/inquiry-step1', [InquiryController::class, 'storeStep1'])->name('patient.storeInquiryStep1');
            Route::post('/patient/inquiry-step2', [InquiryController::class, 'storeInquiryStep2'])->name('patient.storeInquiryStep2');
            Route::post('/confirm-inquiry', [InquiryController::class, 'store'])->name('confirm.inquiry');
            Route::get('/inquiry/message/{id}', [InquiryController::class, 'showMessage'])->name('inquiry.message');
            Route::post('/inquiry/complete/{id}', [InquiryController::class, 'complete'])->name('inquiry.complete');
            Route::delete('/inquiry/{id}', [InquiryController::class, 'destroy'])->name('inquiry.delete');
            Route::get('/therapist/feedback/{id}', [TherapistController::class, 'showFeedback'])->name('therapist.feedback3');


            
        });
        


    });
    Route::get('/', [HomeController::class, 'home'])->name('home');



    // Email verification routes
    Route::middleware('auth')->group(function () {
        Route::get('/email/verify', function () {
            return view('auth.verify-email');
        })->name('verification.notice');

        Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
            $request->fulfill();
            return redirect('/dashboard');
        })->middleware('signed')->name('verification.verify');

        Route::post('/email/verification-notification', function (Request $request) {
            $request->user()->sendEmailVerificationNotification();
            return back()->with('message', 'Verification link sent!');
        })->middleware('throttle:6,1')->name('verification.send');
    });
});


// Include authentication routes
require __DIR__.'/auth.php';