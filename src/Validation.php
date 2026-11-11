<?php

declare(strict_types=1);

namespace Hkianim\PhpValidator;

use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;

/**
 * Shared validation service.
 */
class Validation
{
    /**
     * Laravel validation factory.
     *
     * @var Factory
     */
    private Factory $factory;

    /**
     * Create validation service.
     */
    public function __construct()
    {
        $translator = new Translator(new ArrayLoader(), 'fa');

        $translator->addLines([
            // Required.
            'validation.required' => 'وارد کردن فیلد :attribute الزامی است.',

            // Data types.
            'validation.string' => 'فیلد :attribute باید متن باشد.',
            'validation.integer' => 'فیلد :attribute باید عدد صحیح باشد.',
            'validation.numeric' => 'فیلد :attribute باید عددی باشد.',
            'validation.boolean' => 'فیلد :attribute باید مقدار درست یا نادرست داشته باشد.',
            'validation.array' => 'فیلد :attribute باید آرایه باشد.',

            // Email and pattern.
            'validation.email' => 'فرمت ایمیل واردشده برای فیلد :attribute معتبر نیست.',
            'validation.regex' => 'فرمت فیلد :attribute معتبر نیست.',

            // Minimum length or value.
            'validation.min.string' => 'فیلد :attribute باید حداقل :min کاراکتر باشد.',
            'validation.min.numeric' => 'مقدار فیلد :attribute نباید کمتر از :min باشد.',
            'validation.min.array' => 'فیلد :attribute باید حداقل :min مورد داشته باشد.',
            'validation.min.file' => 'حجم فایل :attribute باید حداقل :min کیلوبایت باشد.',

            // Maximum length or value.
            'validation.max.string' => 'فیلد :attribute نباید بیشتر از :max کاراکتر باشد.',
            'validation.max.numeric' => 'مقدار فیلد :attribute نباید بیشتر از :max باشد.',
            'validation.max.array' => 'فیلد :attribute نباید بیشتر از :max مورد داشته باشد.',
            'validation.max.file' => 'حجم فایل :attribute نباید بیشتر از :max کیلوبایت باشد.',

            // Between.
            'validation.between.string' => 'فیلد :attribute باید بین :min تا :max کاراکتر باشد.',
            'validation.between.numeric' => 'مقدار فیلد :attribute باید بین :min تا :max باشد.',
            'validation.between.array' => 'فیلد :attribute باید بین :min تا :max مورد داشته باشد.',
            'validation.between.file' => 'حجم فایل :attribute باید بین :min تا :max کیلوبایت باشد.',

            // Allowed values.
            'validation.in' => 'مقدار انتخاب‌شده برای فیلد :attribute معتبر نیست.',

            // Custom rules.
            'validation.mobile' => 'شماره همراه واردشده معتبر نیست.',
        ], 'fa');

        $this->factory = new Factory($translator);

        $this->registerRules();
    }

    /**
     * Register built-in validation rules.
     *
     * @return void
     */
    private function registerRules(): void
    {
        $this->factory->extend(
            'mobile',
            static function ($attribute, $value): bool {
                return is_string($value)
                    && preg_match('/^09[0-9]{9}$/', $value) === 1;
            }
        );
    }

    /**
     * Register a custom validation rule.
     *
     * @param string   $name
     * @param callable $callback
     * @param string   $message
     *
     * @return void
     */
    public function addRule(
        string $name,
        callable $callback,
        string $message
    ): void {
        $this->factory->extend(
            $name,
            $callback,
            $message
        );
    }

    /**
     * Validate input data.
     *
     * @param array $data
     * @param array $rules
     * @param array $attributes
     * @param array $messages
     *
     * @return array
     */
    public function validate(
        array $data,
        array $rules,
        array $attributes = [],
        array $messages = []
    ): array {
        $validator = $this->factory->make(
            $data,
            $rules,
            $messages,
            $attributes
        );

        if ($validator->fails()) {
            return [
                'valid' => false,
                'data' => [],
                'errors' => $validator->errors()->toArray(),
            ];
        }

        return [
            'valid' => true,
            'data' => $validator->validated(),
            'errors' => [],
        ];
    }
}
