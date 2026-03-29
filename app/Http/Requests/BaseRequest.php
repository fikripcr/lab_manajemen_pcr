<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

/**
 * Base Request untuk semua Form Request di aplikasi.
 *
 * Menyediakan:
 * - Pesan validasi Bahasa Indonesia lengkap
 * - Auto-resolve attribute names dari field name
 * - Helper methods untuk common validation patterns
 *
 * @package App\Http\Requests
 */
abstract class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string|array>
     */
    public function messages(): array
    {
        return [
            // Basic validation
            'accepted' => ':attribute harus diterima.',
            'accepted_if' => ':attribute harus diterima ketika :other adalah :value.',
            'active_url' => ':attribute bukan URL yang valid.',
            'after' => ':attribute harus berisi tanggal setelah :date.',
            'after_or_equal' => ':attribute harus berisi tanggal setelah atau sama dengan :date.',
            'alpha' => ':attribute hanya boleh berisi huruf.',
            'alpha_dash' => ':attribute hanya boleh berisi huruf, angka, strip, dan garis bawah.',
            'alpha_num' => ':attribute hanya boleh berisi huruf dan angka.',
            'array' => ':attribute harus berupa array.',
            'ascii' => ':attribute hanya boleh berisi karakter single-byte alphanumeric dan simbol.',
            'before' => ':attribute harus berisi tanggal sebelum :date.',
            'before_or_equal' => ':attribute harus berisi tanggal sebelum atau sama dengan :date.',

            // Between validation
            'between' => [
                'numeric' => ':attribute harus di antara :min dan :max.',
                'file' => ':attribute harus berukuran antara :min dan :max kilobita.',
                'string' => ':attribute harus terdiri dari :min sampai :max karakter.',
                'array' => ':attribute harus memiliki :min sampai :max item.',
            ],

            // Boolean, confirmed, date
            'boolean' => ':attribute harus berupa true atau false.',
            'confirmed' => 'Konfirmasi :attribute tidak cocok.',
            'date' => ':attribute bukan tanggal yang valid.',
            'date_equals' => ':attribute harus berisi tanggal yang sama dengan :date.',
            'date_format' => ':attribute tidak cocok dengan format :format.',

            // Declined
            'declined' => ':attribute harus ditolak.',
            'declined_if' => ':attribute harus ditolak ketika :other adalah :value.',

            // Different, digits
            'different' => ':attribute dan :other harus berbeda.',
            'digits' => ':attribute harus terdiri dari :digits digit.',
            'digits_between' => ':attribute harus terdiri dari :min sampai :max digit.',

            // Dimensions, distinct
            'dimensions' => ':attribute memiliki dimensi gambar yang tidak valid.',
            'distinct' => ':attribute memiliki nilai yang duplikat.',

            // Doesnt start with, doesnt end with
            'doesnt_start_with' => ':attribute tidak boleh diawali dengan: :values.',
            'doesnt_end_with' => ':attribute tidak boleh diakhiri dengan: :values.',

            // Email
            'email' => ':attribute harus berupa alamat email yang valid.',

            // Ends with, starts with
            'ends_with' => ':attribute harus diakhiri dengan salah satu dari: :values.',
            'starts_with' => ':attribute harus diawali salah satu dari: :values.',

            // Exists, file, filled
            'exists' => ':attribute yang dipilih tidak valid.',
            'file' => ':attribute harus berupa file.',
            'filled' => ':attribute harus memiliki nilai.',

            // Greater than, greater than or equal
            'gt' => [
                'numeric' => ':attribute harus lebih besar dari :value.',
                'file' => ':attribute harus berukuran lebih besar dari :value kilobita.',
                'string' => ':attribute harus lebih dari :value karakter.',
                'array' => ':attribute harus memiliki lebih dari :value item.',
            ],
            'gte' => [
                'numeric' => ':attribute harus lebih besar dari atau sama dengan :value.',
                'file' => ':attribute harus berukuran lebih besar dari atau sama dengan :value kilobita.',
                'string' => ':attribute minimal :value karakter.',
                'array' => ':attribute harus memiliki :value item atau lebih.',
            ],

            // Image, in, in_array
            'image' => ':attribute harus berupa gambar.',
            'in' => ':attribute yang dipilih tidak valid.',
            'in_array' => ':attribute tidak ada di dalam :other.',

            // Integer, IP, JSON
            'integer' => ':attribute harus berupa bilangan bulat.',
            'ip' => ':attribute harus berupa alamat IP yang valid.',
            'ipv4' => ':attribute harus berupa alamat IPv4 yang valid.',
            'ipv6' => ':attribute harus berupa alamat IPv6 yang valid.',
            'json' => ':attribute harus berupa string JSON yang valid.',

            // Less than, less than or equal
            'lt' => [
                'numeric' => ':attribute harus kurang dari :value.',
                'file' => ':attribute harus berukuran kurang dari :value kilobita.',
                'string' => ':attribute harus kurang dari :value karakter.',
                'array' => ':attribute harus memiliki kurang dari :value item.',
            ],
            'lte' => [
                'numeric' => ':attribute harus kurang dari atau sama dengan :value.',
                'file' => ':attribute harus berukuran kurang dari atau sama dengan :value kilobita.',
                'string' => ':attribute maksimal :value karakter.',
                'array' => ':attribute harus memiliki paling banyak :value item.',
            ],

            // Max validation
            'max' => [
                'numeric' => ':attribute tidak boleh lebih dari :max.',
                'file' => ':attribute tidak boleh lebih dari :max kilobita.',
                'string' => ':attribute tidak boleh lebih dari :max karakter.',
                'array' => ':attribute tidak boleh lebih dari :max item.',
            ],

            // Max digits
            'max_digits' => ':attribute tidak boleh lebih dari :max digit.',

            // Mimes, mimetypes
            'mimes' => ':attribute harus berupa file berjenis: :values.',
            'mimetypes' => ':attribute harus berupa file berjenis: :values.',

            // Min validation
            'min' => [
                'numeric' => ':attribute minimal :min.',
                'file' => ':attribute minimal :min kilobita.',
                'string' => ':attribute minimal :min karakter.',
                'array' => ':attribute minimal :min item.',
            ],

            // Min digits
            'min_digits' => ':attribute minimal :min digit.',

            // Missing, missing if, missing unless, missing with, missing with all
            'missing' => ':attribute harus hilang.',
            'missing_if' => ':attribute harus hilang ketika :other adalah :value.',
            'missing_unless' => ':attribute harus hilang kecuali :other adalah :value.',
            'missing_with' => ':attribute harus hilang bila :values ada.',
            'missing_with_all' => ':attribute harus hilang bila :values ada.',

            // Multiple of, not in, not regex
            'multiple_of' => ':attribute harus merupakan kelipatan dari :value.',
            'not_in' => ':attribute yang dipilih tidak valid.',
            'not_regex' => 'Format :attribute tidak valid.',

            // Numeric, password, present
            'numeric' => ':attribute harus berupa angka.',
            'password' => [
                'letters' => ':attribute harus mengandung minimal satu huruf.',
                'mixed' => ':attribute harus mengandung minimal satu huruf kapital dan satu huruf kecil.',
                'numbers' => ':attribute harus mengandung minimal satu angka.',
                'symbols' => ':attribute harus mengandung minimal satu simbol.',
                'uncompromised' => ':attribute sudah pernah bocor di data breach. Pilih :attribute yang lain.',
            ],
            'present' => ':attribute harus ada.',

            // Present if, present unless, present with, present with all
            'present_if' => ':attribute harus ada ketika :other adalah :value.',
            'present_unless' => ':attribute harus ada kecuali :other adalah :value.',
            'present_with' => ':attribute harus ada bila :values ada.',
            'present_with_all' => ':attribute harus ada bila :values ada.',

            // Prohibited, prohibited if, prohibited unless, prohibited with, prohibited with all, prohibited without
            'prohibited' => ':attribute dilarang.',
            'prohibited_if' => ':attribute dilarang ketika :other adalah :value.',
            'prohibited_unless' => ':attribute dilarang kecuali :other ada di :values.',
            'prohibited_with' => ':attribute dilarang bila :values ada.',
            'prohibited_with_all' => ':attribute dilarang bila :values ada.',
            'prohibited_without' => ':attribute dilarang bila :values tidak ada.',
            'prohibited_without_all' => ':attribute dilarang bila semua :values tidak ada.',

            // Regex, required
            'regex' => 'Format :attribute tidak valid.',
            'required' => ':attribute wajib diisi.',

            // Required if, required unless, required with, required with all, required without, required without all
            'required_if' => ':attribute wajib diisi ketika :other bernilai :value.',
            'required_if_accepted' => ':attribute wajib diisi ketika :other diterima.',
            'required_if_declined' => ':attribute wajib diisi ketika :other ditolak.',
            'required_unless' => ':attribute wajib diisi kecuali :other ada di :values.',
            'required_with' => ':attribute wajib diisi bila :values ada.',
            'required_with_all' => ':attribute wajib diisi bila :values ada.',
            'required_without' => ':attribute wajib diisi bila :values tidak ada.',
            'required_without_all' => ':attribute wajib diisi bila semua :values tidak ada.',

            // Same, size
            'same' => ':attribute dan :other harus sama.',
            'size' => [
                'numeric' => ':attribute harus berukuran :size.',
                'file' => ':attribute harus berukuran :size kilobita.',
                'string' => ':attribute harus terdiri dari :size karakter.',
                'array' => ':attribute harus mengandung :size item.',
            ],

            // String, timezone, unique, uploaded, url, uuid
            'string' => ':attribute harus berupa string.',
            'timezone' => ':attribute harus berupa zona waktu yang valid.',
            'unique' => ':attribute sudah digunakan.',
            'uploaded' => ':attribute gagal diunggah.',
            'uppercase' => ':attribute harus berupa huruf kapital.',
            'url' => 'Format :attribute tidak valid.',
            'ulid' => ':attribute harus berupa ULID yang valid.',
            'uuid' => ':attribute harus berupa UUID yang valid.',

            // Custom messages for common fields
            'phone' => ':attribute harus berupa nomor telepon yang valid.',
            'whatsapp' => ':attribute harus berupa nomor WhatsApp yang valid.',

            // Authentication messages (for login requests)
            'failed' => 'Kredensial yang Anda masukkan tidak valid.',
            'throttle' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam :seconds detik.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * Auto-resolve attribute names from field names if not explicitly defined.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        // Get default attributes from child class
        $attributes = $this->getDefaultAttributes();

        // Merge with custom attributes from child class
        return array_merge($attributes, $this->customAttributes());
    }

    /**
     * Get default attribute labels based on field names.
     *
     * @return array<string, string>
     */
    protected function getDefaultAttributes(): array
    {
        $attributes = [];
        $rules = $this->rules();

        foreach (array_keys($rules) as $field) {
            // Remove array notation (e.g., 'tags.*' -> 'tags')
            $fieldName = str_replace('.*', '', $field);

            // Convert snake_case to words (e.g., 'first_name' -> 'First Name')
            $label = ucwords(str_replace('_', ' ', $fieldName));

            // Convert kebab-case to words (e.g., 'first-name' -> 'First Name')
            $label = ucwords(str_replace('-', ' ', $label));

            $attributes[$field] = $label;
        }

        return $attributes;
    }

    /**
     * Override this method to provide custom attribute labels.
     *
     * @return array<string, string>
     */
    protected function customAttributes(): array
    {
        return [];
    }

    /**
     * Helper: Get decrypted ID from route parameter.
     *
     * @param  string  $parameterName  Name of the route parameter
     * @return int|null
     */
    protected function getDecryptedRouteId(string $parameterName): ?int
    {
        $value = $this->route($parameterName);

        if (!$value) {
            return null;
        }

        // If it's a model, get its primary key
        if (is_object($value) && method_exists($value, 'getKey')) {
            $value = $value->getKey();
        }

        return decryptIdIfEncrypted($value);
    }

    /**
     * Helper: Build unique rule with ignore.
     *
     * @param  string  $table  Table name
     * @param  string  $column  Column name (default: 'id')
     * @param  int|null  $ignoreId  ID to ignore (for updates)
     * @return string
     */
    protected function uniqueRule(string $table, string $column = 'id', ?int $ignoreId = null): string
    {
        if ($ignoreId) {
            return 'unique:' . $table . ',' . $column . ',' . $ignoreId;
        }

        return 'unique:' . $table . ',' . $column;
    }

    /**
     * Helper: Get common password validation rules.
     *
     * @param  bool  $requireUppercase  Require at least one uppercase letter
     * @param  bool  $requireLowercase  Require at least one lowercase letter
     * @param  bool  $requireNumbers  Require at least one number
     * @param  bool  $requireSymbols  Require at least one symbol
     * @return array
     */
    protected function passwordRules(
        bool $requireUppercase = true,
        bool $requireLowercase = true,
        bool $requireNumbers = true,
        bool $requireSymbols = false
    ): array {
        $rules = ['required', 'string', 'min:8', 'confirmed'];

        if ($requireUppercase || $requireLowercase) {
            $rules[] = 'mixed';
        }

        if ($requireNumbers) {
            $rules[] = 'numbers';
        }

        if ($requireSymbols) {
            $rules[] = 'symbols';
        }

        return $rules;
    }

    /**
     * Helper: Get common phone number validation rules.
     *
     * @param  bool  $required  Whether the field is required
     * @return array
     */
    protected function phoneRules(bool $required = true): array
    {
        $rules = ['string'];

        if ($required) {
            $rules[] = 'required';
        }

        $rules[] = 'regex:/^[\+]?[(]?[0-9]{1,4}[)]?[-\s\.]?[(]?[0-9]{1,4}[)]?[-\s\.]?[0-9]{1,9}$/';

        return $rules;
    }

    /**
     * Helper: Get common date validation rules.
     *
     * @param  bool  $required  Whether the field is required
     * @param  string|null  $min  Minimum date (Y-m-d format)
     * @param  string|null  $max  Maximum date (Y-m-d format)
     * @return array
     */
    protected function dateRules(bool $required = true, ?string $min = null, ?string $max = null): array
    {
        $rules = ['date'];

        if ($required) {
            $rules[] = 'required';
        } else {
            $rules[] = 'nullable';
        }

        if ($min) {
            $rules[] = 'after_or_equal:' . $min;
        }

        if ($max) {
            $rules[] = 'before_or_equal:' . $max;
        }

        return $rules;
    }

    /**
     * Helper: Get common file validation rules.
     *
     * @param  array  $allowedMimes  Allowed MIME types (e.g., ['jpg', 'png', 'pdf'])
     * @param  int|null  $maxSizeKb  Maximum file size in KB
     * @param  bool  $required  Whether the field is required
     * @return array
     */
    protected function fileRules(array $allowedMimes = [], ?int $maxSizeKb = null, bool $required = false): array
    {
        $rules = ['file'];

        if ($required) {
            $rules[] = 'required';
        } else {
            $rules[] = 'nullable';
        }

        if (!empty($allowedMimes)) {
            $rules[] = 'mimes:' . implode(',', $allowedMimes);
        }

        if ($maxSizeKb) {
            $rules[] = 'max:' . $maxSizeKb;
        }

        return $rules;
    }

    /**
     * Helper: Get common image validation rules.
     *
     * @param  int|null  $maxWidthKb  Maximum file size in KB
     * @param  int|null  $minWidth  Minimum width in pixels
     * @param  int|null  $maxWidth  Maximum width in pixels
     * @param  int|null  $minHeight  Minimum height in pixels
     * @param  int|null  $maxHeight  Maximum height in pixels
     * @param  bool  $required  Whether the field is required
     * @return array
     */
    protected function imageRules(
        ?int $maxWidthKb = null,
        ?int $minWidth = null,
        ?int $maxWidth = null,
        ?int $minHeight = null,
        ?int $maxHeight = null,
        bool $required = false
    ): array {
        $rules = ['image'];

        if ($required) {
            $rules[] = 'required';
        } else {
            $rules[] = 'nullable';
        }

        if ($maxWidthKb) {
            $rules[] = 'max:' . $maxWidthKb;
        }

        if ($minWidth) {
            $rules[] = 'min_width:' . $minWidth;
        }

        if ($maxWidth) {
            $rules[] = 'max_width:' . $maxWidth;
        }

        if ($minHeight) {
            $rules[] = 'min_height:' . $minHeight;
        }

        if ($maxHeight) {
            $rules[] = 'max_height:' . $maxHeight;
        }

        return $rules;
    }
}
