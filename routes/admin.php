<?php

// todo: admin middleware
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('users', \App\Livewire\Admin\Users\Index::class)->name('admin.users');
});
