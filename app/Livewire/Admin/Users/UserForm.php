<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UserForm extends Form
{
    public ?User $user;

    #[Validate(['required', 'string', 'max:255'])]
    public $name;

    #[Validate(['required', 'email', 'max:255'])]
    public $email;

    #[Validate(['required', 'max:255'])]
    public $password;

    #[Validate(['nullable', 'exists:roles,id'])]
    public $role_id;

    public function set(User $user) {
        $this->user = $user;
        $this->fill($user->toArray());

        // set users role
        $this->role_id = $user->roles->first()->id;
    }

    public function save() {
        // ensure email isnt taken by another user
        $other_email = User::where('email', $this->email)->first();

        if (isset($other_email)) {
            if ((isset($this->user) && $this->user->id !== $other_email->id) || !isset($this->user)) {
                $this->addError('email', 'Email already taken');
                return;
            }
        }

        $this->validate();

        if (!isset($this->user)) {
            $this->user = new User();
        }

        $this->user->fill($this->all());

        $this->user->save();

        // save role
        if (isset($this->role_id)) {
            $this->user->roles()->sync([$this->role_id]);
        } else {
            $this->user->roles()->detach();
        }
    }
}
