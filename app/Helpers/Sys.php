<?php

if (!function_exists('encryptId')) {
    /**
     * Encrypt an ID using Hashids
     *
     * @param int $id
     * @return string
     */
    function encryptId($id)
    {
        return app('hashids')->encode($id);
    }
}

if (!function_exists('decryptId')) {
    /**
     * Decrypt a Hashid to get the original ID
     *
     * @param string $hash
     * @param bool $throwException Whether to throw exception on failure
     * @return int|null
     */
    function decryptId($hash, $throwException = true)
    {
        if (!$hash) {
            if ($throwException) {
                abort(403, 'Data tidak ditemukan.');
            }
            return null;
        }

        $decoded = app('hashids')->decode($hash);

        if (empty($decoded)) {
            if ($throwException) {
                abort(403, 'Data tidak ditemukan.');
            }
            return null;
        }

        return $decoded[0];
    }
}

if (!function_exists('logActivity')) {
    function logActivity($logName, $description, $subject = null, $properties = [])
    {
        // Get the current user
        $causer = auth()->user();

        // Get IP address and user agent from the request
        $request = request();
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();

        // Create activity log with specified logName
        $activity = activity($logName);

        if ($subject) {
            $activity->performedOn($subject);
        }

        if ($causer) {
            $activity->causedBy($causer);
        }

        // Add IP and user agent to properties
        $properties = array_merge($properties, [
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'user_id' => $causer?->id,
            'user_name' => $causer?->name,
        ]);

        $activity->withProperties($properties)->log($description);
    }
}

if (!function_exists('validation_messages_id')) {
    function validation_messages_id()
    {
        return [
            'accepted'        => ':attribute harus diterima.',
            'active_url'      => ':attribute bukan URL yang valid.',
            'after'           => ':attribute harus berisi tanggal setelah :date.',
            'after_or_equal'  => ':attribute harus berisi tanggal setelah atau sama dengan :date.',
            'alpha'           => ':attribute hanya boleh berisi huruf.',
            'alpha_dash'      => ':attribute hanya boleh berisi huruf, angka, strip, dan garis bawah.',
            'alpha_num'       => ':attribute hanya boleh berisi huruf dan angka.',
            'array'           => ':attribute harus berupa array.',
            'before'          => ':attribute harus berisi tanggal sebelum :date.',
            'before_or_equal' => ':attribute harus berisi tanggal sebelum atau sama dengan :date.',
            'between'         => [
                'numeric' => ':attribute harus di antara :min dan :max.',
                'file'    => ':attribute harus berukuran antara :min dan :max kilobita.',
                'string'  => ':attribute harus terdiri dari :min sampai :max karakter.',
                'array'   => ':attribute harus memiliki :min sampai :max item.',
            ],
            'boolean'         => ':attribute harus berupa true atau false.',
            'confirmed'       => 'Konfirmasi :attribute tidak cocok.',
            'date'            => ':attribute bukan tanggal yang valid.',
            'date_equals'     => ':attribute harus berisi tanggal yang sama dengan :date.',
            'date_format'     => ':attribute tidak cocok dengan format :format.',
            'different'       => ':attribute dan :other harus berbeda.',
            'digits'          => ':attribute harus terdiri dari :digits digit.',
            'digits_between'  => ':attribute harus terdiri dari :min sampai :max digit.',
            'dimensions'      => ':attribute memiliki dimensi gambar yang tidak valid.',
            'distinct'        => ':attribute memiliki nilai yang duplikat.',
            'email'           => ':attribute harus berupa alamat email yang valid.',
            'ends_with'       => ':attribute harus diakhiri dengan salah satu dari: :values.',
            'exists'          => ':attribute yang dipilih tidak valid.',
            'file'            => ':attribute harus berupa file.',
            'filled'          => ':attribute harus memiliki nilai.',
            'gt'              => [
                'numeric' => ':attribute harus lebih besar dari :value.',
                'file'    => ':attribute harus berukuran lebih besar dari :value kilobita.',
                'string'  => ':attribute harus lebih dari :value karakter.',
                'array'   => ':attribute harus memiliki lebih dari :value item.',
            ],
            'gte'             => [
                'numeric' => ':attribute harus lebih besar dari atau sama dengan :value.',
                'file'    => ':attribute harus berukuran lebih besar dari atau sama dengan :value kilobita.',
                'string'  => ':attribute harus minimal :value karakter.',
                'array'   => ':attribute harus memiliki :value item atau lebih.',
            ],
            'image'           => ':attribute harus berupa gambar.',
            'in'              => ':attribute yang dipilih tidak valid.',
            'in_array'        => ':attribute tidak ada di dalam :other.',
            'integer'         => ':attribute harus berupa bilangan bulat.',
            'ip'              => ':attribute harus berupa alamat IP yang valid.',
            'ipv4'            => ':attribute harus berupa alamat IPv4 yang valid.',
            'ipv6'            => ':attribute harus berupa alamat IPv6 yang valid.',
            'json'            => ':attribute harus berupa string JSON yang valid.',
            'lt'              => [
                'numeric' => ':attribute harus kurang dari :value.',
                'file'    => ':attribute harus berukuran kurang dari :value kilobita.',
                'string'  => ':attribute harus kurang dari :value karakter.',
                'array'   => ':attribute harus memiliki kurang dari :value item.',
            ],
            'lte'             => [
                'numeric' => ':attribute harus kurang dari atau sama dengan :value.',
                'file'    => ':attribute harus berukuran kurang dari atau sama dengan :value kilobita.',
                'string'  => ':attribute maksimal :value karakter.',
                'array'   => ':attribute harus memiliki paling banyak :value item.',
            ],
            'max'             => [
                'numeric' => ':attribute tidak boleh lebih dari :max.',
                'file'    => ':attribute tidak boleh lebih dari :max kilobita.',
                'string'  => ':attribute tidak boleh lebih dari :max karakter.',
                'array'   => ':attribute tidak boleh lebih dari :max item.',
            ],
            'mimes'           => ':attribute harus berupa file berjenis: :values.',
            'mimetypes'       => ':attribute harus berupa file berjenis: :values.',
            'min'             => [
                'numeric' => ':attribute minimal :min.',
                'file'    => ':attribute minimal :min kilobita.',
                'string'  => ':attribute minimal :min karakter.',
                'array'   => ':attribute minimal :min item.',
            ],
            'not_in'          => ':attribute yang dipilih tidak valid.',
            'not_regex'       => 'Format :attribute tidak valid.',
            'numeric'         => ':attribute harus berupa angka.',
            'password'        => 'Kata sandi salah.',
            'present'         => ':attribute harus ada.',
            'regex'           => 'Format :attribute tidak valid.',
            'required'        => ':attribute wajib diisi.',
            'required_if'     => ':attribute wajib diisi ketika :other bernilai :value.',
            'required_unless' => ':attribute wajib diisi kecuali :other ada di :values.',
            'required_with'   => ':attribute wajib diisi bila :values ada.',
            'required_with_all' => ':attribute wajib diisi bila :values ada.',
            'required_without' => ':attribute wajib diisi bila :values tidak ada.',
            'required_without_all' => ':attribute wajib diisi bila semua :values tidak ada.',
            'same'            => ':attribute dan :other harus sama.',
            'size'            => [
                'numeric' => ':attribute harus berukuran :size.',
                'file'    => ':attribute harus berukuran :size kilobita.',
                'string'  => ':attribute harus terdiri dari :size karakter.',
                'array'   => ':attribute harus mengandung :size item.',
            ],
            'starts_with'     => ':attribute harus diawali salah satu dari: :values.',
            'string'          => ':attribute harus berupa string.',
            'timezone'        => ':attribute harus berupa zona waktu yang valid.',
            'unique'          => ':attribute sudah digunakan.',
            'uploaded'        => ':attribute gagal diunggah.',
            'url'             => 'Format :attribute tidak valid.',
            'uuid'            => ':attribute harus berupa UUID yang valid.',

            // Custom attributes (opsional)
            'attributes' => [],
        ];
    }
}
