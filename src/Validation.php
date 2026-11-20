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
            'validation.required_if' => 'وارد کردن فیلد :attribute الزامی است.',
            'validation.required_unless' => 'وارد کردن فیلد :attribute الزامی است.',
            'validation.required_with' => 'وارد کردن فیلد :attribute الزامی است.',
            'validation.required_with_all' => 'وارد کردن فیلد :attribute الزامی است.',
            'validation.required_without' => 'وارد کردن فیلد :attribute الزامی است.',
            'validation.required_without_all' => 'وارد کردن فیلد :attribute الزامی است.',
            'validation.present' => 'فیلد :attribute باید ارسال شود.',
            'validation.filled' => 'فیلد :attribute در صورت ارسال نباید خالی باشد.',
            'validation.accepted' => 'پذیرش فیلد :attribute الزامی است.',

            // Data types.
            'validation.string' => 'فیلد :attribute باید متن باشد.',
            'validation.integer' => 'فیلد :attribute باید عدد صحیح باشد.',
            'validation.numeric' => 'فیلد :attribute باید عددی باشد.',
            'validation.boolean' => 'فیلد :attribute باید مقدار درست یا نادرست داشته باشد.',
            'validation.array' => 'فیلد :attribute باید آرایه باشد.',
            'validation.file' => 'فیلد :attribute باید یک فایل معتبر باشد.',
            'validation.json' => 'فیلد :attribute باید یک متن JSON معتبر باشد.',

            // Email and pattern.
            'validation.email' => 'فرمت ایمیل واردشده معتبر نیست.',
            'validation.regex' => 'فرمت واردشده برای فیلد :attribute معتبر نیست.',
            'validation.not_regex' => 'فرمت واردشده برای فیلد :attribute معتبر نیست.',
            'validation.url' => 'آدرس اینترنتی واردشده معتبر نیست.',
            'validation.ip' => 'آدرس IP واردشده معتبر نیست.',
            'validation.uuid' => 'شناسه واردشده برای فیلد :attribute معتبر نیست.',
            'validation.alpha' => 'فیلد :attribute فقط باید شامل حروف باشد.',
            'validation.alpha_num' => 'فیلد :attribute فقط باید شامل حروف و اعداد باشد.',
            'validation.alpha_dash' => 'فیلد :attribute فقط باید شامل حروف، اعداد، خط تیره و زیرخط باشد.',

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

            // Exact size.
            'validation.size.string' => 'فیلد :attribute باید دقیقاً :size کاراکتر باشد.',
            'validation.size.numeric' => 'مقدار فیلد :attribute باید برابر با :size باشد.',
            'validation.size.array' => 'فیلد :attribute باید دقیقاً :size مورد داشته باشد.',
            'validation.size.file' => 'حجم فایل :attribute باید دقیقاً :size کیلوبایت باشد.',

            // Numeric comparisons.
            'validation.gt.numeric' => 'مقدار فیلد :attribute باید بیشتر از :value باشد.',
            'validation.gt.string' => 'طول فیلد :attribute باید بیشتر از :value کاراکتر باشد.',
            'validation.gt.array' => 'فیلد :attribute باید بیشتر از :value مورد داشته باشد.',
            'validation.gt.file' => 'حجم فایل :attribute باید بیشتر از :value کیلوبایت باشد.',

            'validation.gte.numeric' => 'مقدار فیلد :attribute باید حداقل :value باشد.',
            'validation.gte.string' => 'طول فیلد :attribute باید حداقل :value کاراکتر باشد.',
            'validation.gte.array' => 'فیلد :attribute باید حداقل :value مورد داشته باشد.',
            'validation.gte.file' => 'حجم فایل :attribute باید حداقل :value کیلوبایت باشد.',

            'validation.lt.numeric' => 'مقدار فیلد :attribute باید کمتر از :value باشد.',
            'validation.lt.string' => 'طول فیلد :attribute باید کمتر از :value کاراکتر باشد.',
            'validation.lt.array' => 'فیلد :attribute باید کمتر از :value مورد داشته باشد.',
            'validation.lt.file' => 'حجم فایل :attribute باید کمتر از :value کیلوبایت باشد.',

            'validation.lte.numeric' => 'مقدار فیلد :attribute نباید بیشتر از :value باشد.',
            'validation.lte.string' => 'طول فیلد :attribute نباید بیشتر از :value کاراکتر باشد.',
            'validation.lte.array' => 'فیلد :attribute نباید بیشتر از :value مورد داشته باشد.',
            'validation.lte.file' => 'حجم فایل :attribute نباید بیشتر از :value کیلوبایت باشد.',

            // Digits.
            'validation.digits' => 'فیلد :attribute باید دقیقاً :digits رقم باشد.',
            'validation.digits_between' => 'فیلد :attribute باید بین :min تا :max رقم باشد.',

            // Allowed values.
            'validation.in' => 'مقدار انتخاب‌شده برای فیلد :attribute معتبر نیست.',
            'validation.not_in' => 'مقدار انتخاب‌شده برای فیلد :attribute مجاز نیست.',
            'validation.in_array' => 'مقدار فیلد :attribute در اطلاعات ارسالی معتبر نیست.',

            // Comparison.
            'validation.confirmed' => 'تکرار فیلد :attribute با مقدار آن مطابقت ندارد.',
            'validation.same' => 'فیلد :attribute باید با :other یکسان باشد.',
            'validation.different' => 'فیلد :attribute باید با :other متفاوت باشد.',
            'validation.distinct' => 'فیلد :attribute دارای مقدار تکراری است.',

            // Date.
            'validation.date' => 'تاریخ واردشده برای فیلد :attribute معتبر نیست.',
            'validation.date_format' => 'فرمت تاریخ فیلد :attribute معتبر نیست.',
            'validation.date_equals' => 'تاریخ فیلد :attribute معتبر نیست.',
            'validation.after' => 'تاریخ فیلد :attribute باید بعد از :date باشد.',
            'validation.after_or_equal' => 'تاریخ فیلد :attribute باید برابر یا بعد از :date باشد.',
            'validation.before' => 'تاریخ فیلد :attribute باید قبل از :date باشد.',
            'validation.before_or_equal' => 'تاریخ فیلد :attribute باید برابر یا قبل از :date باشد.',
            'validation.timezone' => 'منطقه زمانی واردشده برای فیلد :attribute معتبر نیست.',

            // File types.
            'validation.image' => 'فایل انتخاب‌شده برای فیلد :attribute باید تصویر باشد.',
            'validation.mimes' => 'فرمت فایل :attribute مجاز نیست.',
            'validation.mimetypes' => 'نوع فایل :attribute مجاز نیست.',

            // Other.
            'validation.active_url' => 'آدرس واردشده برای فیلد :attribute معتبر نیست.',
            'validation.ends_with' => 'فیلد :attribute باید به یکی از مقادیر مجاز ختم شود.',
            'validation.starts_with' => 'فیلد :attribute باید با یکی از مقادیر مجاز شروع شود.',
            'validation.unique' => 'مقدار واردشده برای فیلد :attribute قبلاً ثبت شده است.',

            // Custom rules.
            'validation.mobile' => 'شماره همراه باید ۱۱ رقم باشد و با 09 شروع شود.',
            'validation.phone' => 'شماره تلفن باید با صفر شروع شود و بین ۶ تا ۱۱ رقم باشد.',
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

        $this->factory->extend(
            'phone',
            static function ($attribute, $value): bool {
                return is_string($value)
                    && preg_match('/^0[0-9]{5,10}$/', $value) === 1;
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
