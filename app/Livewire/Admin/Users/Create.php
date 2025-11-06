<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

class Create extends Component
{
    public ?User $user;

    public UserForm $form;

    public $show = false;

    #[On(['edit-user'])]
    public function edit(User $user) {
        $this->user = $user;
        $this->showForm();
    }

    public function showForm() {
        $this->form->reset();

        if (isset($this->user)) {
            $this->form->set($this->user);
        }

        $this->show = true;
    }

    public function save() {
        $generated_password = str()->password(15);
        $this->form->password = $generated_password;

        $this->form->save();
        $this->show = false;
        $this->form->reset();

        $this->js('alert("Password: ' . $generated_password . '")');

        // notify parents to refresh
        $this->dispatch('refresh-users');
    }

    public function render()
    {
        return view('livewire.admin.users.create');
    }
}
