<div>
    <flux:modal wire:model="show" class="w-6xl">
        <flux:heading size="lg">Manage Constraints</flux:heading>

        <form wire:submit="addConstraint" class="mt-6 space-y-6">
            <flux:select
                label="Type *"
                wire:model="type"
                required
            >
                <flux:select.option value="">Choose type</flux:select.option>

                <flux:select.option :value="'co2_source'">
                    C02 Source
                </flux:select.option>

                <flux:select.option :value="'storage_method'">
                    Storage Method
                </flux:select.option>
            </flux:select>

            <flux:select
                label="Condition *"
                wire:model="condition"
                required
            >
                <flux:select.option value="">Choose condition</flux:select.option>

                @foreach(\App\Enums\ConstraintType::creditCompanyConstraints() as $constraint)
                    <flux:select.option :value="$constraint->value">
                        {{$constraint->display()}}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <x-form.footer />
        </form>

        @isset($this->credit_company)
            <flux:heading size="lg">Constraints</flux:heading>

            <table class="basic-table mt-4">
                <thead>
                <tr>
                    <th>Type</th>
                    <th>Condition</th>
                    <th class="w-8"></th>
                </tr>
                </thead>

                <tbody>
                @foreach($this->credit_company->constraints as $type => $condition)
                    <tr>
                        <td class="font-medium">{{ucwords(strtolower(str_replace('_', ' ', $type)))}}</td>
                        <td class="font-medium">{{\App\Enums\ConstraintType::from($condition)->display()}}</td>
                        <td class="font-medium">
                            <flux:button wire:click="delete('{{$type}}')">Delete</flux:button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endisset
    </flux:modal>
</div>
