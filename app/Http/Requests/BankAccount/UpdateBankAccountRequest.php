<?php

namespace App\Http\Requests\BankAccount;

use App\Enums\BankAccountTypeEnum;
use App\Models\BankAccount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBankAccountRequest extends FormRequest
{
    public function __construct(
        public readonly BankAccount $bankAccount,
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null
    ) {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    public function rules(): array
    {
        return [
            'bankAccount.bank_name' => ['required', 'string', 'max:100'],
            'bankAccount.type'      => [
                'required',
                Rule::in(BankAccountTypeEnum::getValues()),
            ],
            'bankAccount.number' => [
                'required',
                'numeric',
                Rule::unique('bank_accounts', 'number')->ignore($this->bankAccount->id, 'id'),
                'min_digits:5',
                'max_digits:20',
            ],
            'bankAccount.digit'         => ['required', 'numeric', 'max_digits:1'],
            'bankAccount.agency_number' => ['required', 'numeric', 'min_digits:4', 'max_digits:4'],
            'bankAccount.agency_digit'  => ['nullable', 'numeric', 'max_digits:1'],
            'bankAccount.balance'       => ['required', 'numeric'],
        ];
    }
}
