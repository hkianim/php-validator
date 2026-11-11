<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Hkianim\PhpValidator\Validation;
use Illuminate\Validation\Validator;

$validation = new Validation();

/**
 * Assert that validation succeeds.
 */
function assertValid(array $result, string $test): void
{
    if (!$result['valid']) {
        throw new RuntimeException(
            $test . ' failed: ' .
            json_encode($result['errors'], JSON_UNESCAPED_UNICODE)
        );
    }
}

/**
 * Assert that validation fails with the expected message.
 */
function assertInvalid(
    array $result,
    string $field,
    string $expectedMessage,
    string $test
): void {
    if ($result['valid']) {
        throw new RuntimeException($test . ': invalid data was accepted.');
    }

    if (
        !isset($result['errors'][$field]) ||
        !in_array(
            $expectedMessage,
            $result['errors'][$field],
            true
        )
    ) {
        throw new RuntimeException(
            $test . ': expected message not found. Actual: ' .
            json_encode($result['errors'], JSON_UNESCAPED_UNICODE)
        );
    }
}

// Test 1: Successful validation.
$result = $validation->validate(
    [
        'name' => 'حسین',
        'mobile' => '09123456789',
    ],
    [
        'name' => ['required', 'string', 'max:25'],
        'mobile' => ['required', 'mobile'],
    ],
    [
        'name' => 'نام',
        'mobile' => 'شماره همراه',
    ]
);

assertValid($result, 'Successful validation');

if ($result['data']['mobile'] !== '09123456789') {
    throw new RuntimeException('Validated data is incorrect.');
}

// Test 2: Required.
$result = $validation->validate(
    ['name' => ''],
    ['name' => ['required']],
    ['name' => 'نام']
);

assertInvalid(
    $result,
    'name',
    'وارد کردن فیلد نام الزامی است.',
    'Required rule'
);

// Test 3: String.
$result = $validation->validate(
    ['name' => 123],
    ['name' => ['string']],
    ['name' => 'نام']
);

assertInvalid(
    $result,
    'name',
    'فیلد نام باید متن باشد.',
    'String rule'
);

// Test 4: Integer.
$result = $validation->validate(
    ['age' => 'abc'],
    ['age' => ['integer']],
    ['age' => 'سن']
);

assertInvalid(
    $result,
    'age',
    'فیلد سن باید عدد صحیح باشد.',
    'Integer rule'
);

// Test 5: Numeric.
$result = $validation->validate(
    ['amount' => 'abc'],
    ['amount' => ['numeric']],
    ['amount' => 'مبلغ']
);

assertInvalid(
    $result,
    'amount',
    'فیلد مبلغ باید عددی باشد.',
    'Numeric rule'
);

// Test 6: Boolean.
$result = $validation->validate(
    ['active' => 'yes'],
    ['active' => ['boolean']],
    ['active' => 'وضعیت']
);

assertInvalid(
    $result,
    'active',
    'فیلد وضعیت باید مقدار درست یا نادرست داشته باشد.',
    'Boolean rule'
);

// Test 7: Array.
$result = $validation->validate(
    ['items' => 'item'],
    ['items' => ['array']],
    ['items' => 'اقلام']
);

assertInvalid(
    $result,
    'items',
    'فیلد اقلام باید آرایه باشد.',
    'Array rule'
);

// Test 8: Email.
$result = $validation->validate(
    ['email' => 'invalid-email'],
    ['email' => ['email']],
    ['email' => 'ایمیل']
);

assertInvalid(
    $result,
    'email',
    'فرمت ایمیل واردشده برای فیلد ایمیل معتبر نیست.',
    'Email rule'
);

// Test 9: Minimum string length.
$result = $validation->validate(
    ['name' => 'علی'],
    ['name' => ['string', 'min:5']],
    ['name' => 'نام']
);

assertInvalid(
    $result,
    'name',
    'فیلد نام باید حداقل 5 کاراکتر باشد.',
    'Minimum string length'
);

// Test 10: Maximum numeric value.
$result = $validation->validate(
    ['amount' => 150],
    ['amount' => ['numeric', 'max:100']],
    ['amount' => 'مبلغ']
);

assertInvalid(
    $result,
    'amount',
    'مقدار فیلد مبلغ نباید بیشتر از 100 باشد.',
    'Maximum numeric value'
);

// Test 11: Between numeric values.
$result = $validation->validate(
    ['age' => 50],
    ['age' => ['numeric', 'between:18,40']],
    ['age' => 'سن']
);

assertInvalid(
    $result,
    'age',
    'مقدار فیلد سن باید بین 18 تا 40 باشد.',
    'Between numeric values'
);

// Test 12: In.
$result = $validation->validate(
    ['status' => 'unknown'],
    ['status' => ['in:active,inactive']],
    ['status' => 'وضعیت']
);

assertInvalid(
    $result,
    'status',
    'مقدار انتخاب‌شده برای فیلد وضعیت معتبر نیست.',
    'In rule'
);

// Test 13: Invalid mobile.
$result = $validation->validate(
    ['mobile' => '123456'],
    ['mobile' => ['required', 'mobile']],
    ['mobile' => 'شماره همراه']
);

assertInvalid(
    $result,
    'mobile',
    'شماره همراه واردشده معتبر نیست.',
    'Mobile rule'
);

// Test 14: Custom validation rule.
$validation->addRule(
    'bank_account_type',
    static function ($attribute, $value): bool {
        return in_array($value, [
            'htBank',
            'htCash',
            'htOthers',
        ], true);
    },
    'نوع حساب معتبر نیست.'
);

// Valid custom rule value.
$result = $validation->validate(
    ['hsbType' => 'htBank'],
    ['hsbType' => ['required', 'bank_account_type']]
);

assertValid($result, 'Valid custom rule');

// Invalid custom rule value.
$result = $validation->validate(
    ['hsbType' => 'invalidType'],
    ['hsbType' => ['required', 'bank_account_type']]
);

assertInvalid(
    $result,
    'hsbType',
    'نوع حساب معتبر نیست.',
    'Invalid custom rule'
);

// Test 15: Custom message for a field.
$result = $validation->validate(
    ['mobile' => '123456'],
    ['mobile' => ['mobile']],
    ['mobile' => 'شماره همراه'],
    [
        'mobile.mobile' => 'شماره تماس واردشده صحیح نیست.',
    ]
);

assertInvalid(
    $result,
    'mobile',
    'شماره تماس واردشده صحیح نیست.',
    'Custom field message'
);

// Test 16: Custom rules must not leak between instances.
$anotherValidation = new Validation();

try {
    $anotherValidation->validate(
        ['hsbType' => 'htBank'],
        ['hsbType' => ['bank_account_type']]
    );

    throw new RuntimeException(
        'Custom rule leaked into another Validation instance.'
    );
} catch (BadMethodCallException $exception) {
    if (
        strpos(
            $exception->getMessage(),
            'validateBankAccountType'
        ) === false
    ) {
        throw new RuntimeException(
            'Unexpected exception: ' . $exception->getMessage()
        );
    }
}

// Test 17: Custom rule receives all four arguments.
$receivedArguments = [];

$validation->addRule(
    'custom_test',
    static function ($attribute, $value, $parameters, $validator) use (&$receivedArguments): bool {
        $receivedArguments = [
            'attribute' => $attribute,
            'value' => $value,
            'parameters' => $parameters,
            'validator' => $validator,
        ];

        return true;
    },
    'مقدار نامعتبر است.'
);

$result = $validation->validate(
    ['status' => 'active'],
    ['status' => ['custom_test:first,second']]
);

assertValid($result, 'Custom rule arguments');

if ($receivedArguments['attribute'] !== 'status') {
    throw new RuntimeException('Custom rule attribute is incorrect.');
}

if ($receivedArguments['value'] !== 'active') {
    throw new RuntimeException('Custom rule value is incorrect.');
}

if ($receivedArguments['parameters'] !== ['first', 'second']) {
    throw new RuntimeException('Custom rule parameters are incorrect.');
}

if (!($receivedArguments['validator'] instanceof Validator)) {
    throw new RuntimeException('Custom rule validator is incorrect.');
}

echo "All validation tests passed.\n";
