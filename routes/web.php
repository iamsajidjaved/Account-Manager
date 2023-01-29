<?php
use App\Http\Controllers\EntryPerson\DepositTransactionController;
use App\Http\Controllers\EntryPerson\WithdrawalTransactionController;
use App\Http\Controllers\Approver\TransactionController as ApproverTransactionController;

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }
    return redirect()->route('admin.home');
});

Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth', '2fa']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::resource('permissions', 'PermissionsController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);
    Route::resource('roles', 'RolesController', ['except' => ['create', 'store', 'edit', 'update', 'show', 'destroy']]);
    Route::resource('users', 'UsersController');
    Route::resource('banks', 'BankController');
    Route::resource('transactions', 'TransactionController', ['except' => ['create','store']]);
    Route::resource('groups', 'GroupController');

    Route::get('messenger', 'MessengerController@index')->name('messenger.index');
    Route::get('messenger/create', 'MessengerController@createTopic')->name('messenger.createTopic');
    Route::post('messenger', 'MessengerController@storeTopic')->name('messenger.storeTopic');
    Route::get('messenger/inbox', 'MessengerController@showInbox')->name('messenger.showInbox');
    Route::get('messenger/outbox', 'MessengerController@showOutbox')->name('messenger.showOutbox');
    Route::get('messenger/{topic}', 'MessengerController@showMessages')->name('messenger.showMessages');
    Route::delete('messenger/{topic}', 'MessengerController@destroyTopic')->name('messenger.destroyTopic');
    Route::post('messenger/{topic}/reply', 'MessengerController@replyToTopic')->name('messenger.reply');
    Route::get('messenger/{topic}/reply', 'MessengerController@showReply')->name('messenger.showReply');
});

Route::group(['prefix' => 'entry-person', 'as' => 'entryperson.', 'namespace' => 'EntryPerson', 'middleware' => ['auth', '2fa']], function () {
    // Transaction
    Route::get('transactions/deposit/create/{bank_id}', [DepositTransactionController::class, 'create'])->name('transactions.deposit.create');
    Route::post('transactions/deposit/update', [DepositTransactionController::class, 'update'])->name('transactions.deposit.update');
    Route::post('transactions/deposit/store', [DepositTransactionController::class, 'store'])->name('transactions.deposit.store');

    Route::get('transactions/withdrawal/create/{bank_id}', 'WithdrawalTransactionController@create')->name('transactions.withdrawal.create');
    Route::post('transactions/withdrawal/update', [WithdrawalTransactionController::class, 'update'])->name('transactions.withdrawal.update');
    Route::post('transactions/withdrawal/store', [WithdrawalTransactionController::class, 'store'])->name('transactions.withdrawal.store');
});

Route::group(['prefix' => 'approver', 'as' => 'approver.', 'namespace' => 'Approver', 'middleware' => ['auth', '2fa']], function () {
    // Transaction
    Route::get('transactions/index/{bank_id}', [ApproverTransactionController::class, 'index'])->name('transactions.index');
    Route::post('transactions/update', [ApproverTransactionController::class, 'update'])->name('transactions.update');
});

Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth', '2fa']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
        Route::post('profile/two-factor', 'ChangePasswordController@toggleTwoFactor')->name('password.toggleTwoFactor');
    }
});
Route::group(['namespace' => 'Auth', 'middleware' => ['auth', '2fa']], function () {
    // Two Factor Authentication
    if (file_exists(app_path('Http/Controllers/Auth/TwoFactorController.php'))) {
        Route::get('two-factor', 'TwoFactorController@show')->name('twoFactor.show');
        Route::post('two-factor', 'TwoFactorController@check')->name('twoFactor.check');
        Route::get('two-factor/resend', 'TwoFactorController@resend')->name('twoFactor.resend');
    }
});
