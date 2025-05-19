<?php

namespace App\Http\Controllers\Admin;

use Alert;
use Backpack\CRUD\app\Http\Requests\AccountInfoRequest;
use Backpack\CRUD\app\Http\Requests\ChangePasswordRequest;
use Backpack\CRUD\app\Http\Controllers\MyAccountController as BackpackMyAccountController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MyAccountController extends BackpackMyAccountController
{
    /**
     * Show the user a form to change their personal information & password.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function getAccountInfoForm()
    {
        $this->data['title'] = trans('backpack::base.my_account');
        $this->data['user'] = $this->guard()->user();

        // Load client data if user has client role
        if ($this->data['user']->hasRole('klien')) {
            $this->data['klien'] = $this->data['user']->klien;
        }

        return view(backpack_view('my_account'), $this->data);
    }

    /**
     * Save the modified personal information for a user.
     */
    public function postAccountInfoForm(AccountInfoRequest $request)
    {
        $user = $this->guard()->user();

        // Handle client data update
        if ($user->hasRole('klien')) {
            $request->validate([
                'nama_klien' => 'required|string|max:255',
                'no_telp' => 'required|string|max:20',
                'nik' => 'required|string|max:20',
                'tgl_lahir' => 'required|date',
                'alamat' => 'required|string'
            ]);

            // Update user email
            $user->email = $request->email;
            $user->save();

            // Update client data
            $user->klien->update([
                'nama_klien' => $request->nama_klien,
                'no_telp' => $request->no_telp,
                'nik' => $request->nik,
                'tgl_lahir' => $request->tgl_lahir,
                'alamat' => $request->alamat
            ]);

            Alert::success('Data klien berhasil diperbarui')->flash();
            return redirect()->back();
        }

        // Default behavior for admin/other roles
        $result = $user->update($request->except(['_token']));

        if ($result) {
            Alert::success(trans('backpack::base.account_updated'))->flash();
        } else {
            Alert::error(trans('backpack::base.error_saving'))->flash();
        }

        return redirect()->back();
    }

    /**
     * Save the new password for a user.
     */
    public function postChangePasswordForm(ChangePasswordRequest $request)
    {
        // Default behavior (same for both roles)
        return parent::postChangePasswordForm($request);
    }

    /**
     * Get the guard to be used for account manipulation.
     * Override if you need different guard for clients
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return backpack_auth(); // Default backpack guard
    }
}
