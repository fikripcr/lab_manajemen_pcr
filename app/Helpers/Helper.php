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
