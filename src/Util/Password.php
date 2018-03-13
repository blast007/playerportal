<?php

namespace App\Util;

class Password {
    //private $prehash_algo;
    private $password_algo;
    private $password_options;

    public function __construct($settings) {
        /*if (isset($settings['prehash_algo'])) {
            if (!in_array($settings['prehash_algo'], hash_algos())) {
                throw new \Exception("Invalid prehash_algo.");
            } else {
                $this->prehash_algo = $settings['prehash_algo'];
            }
        } else {
            $this->prehash_algo = false;
        }*/

        // TODO: Support setting the password hash from the settings
        $this->password_algo = PASSWORD_DEFAULT;

        $this->password_options = $settings['options'] ?? [];
    }

    public function generateHash($password) {
        /*if ($this->prehash_algo) {
            $password = base64_encode(hash($this->prehash_algo, $password, true));
        }*/

        return password_hash($password, $this->password_algo, $this->password_options);
    }

    public function needsRehash($hash) {
        return password_needs_rehash($hash, $this->password_algo, $this->password_options);
    }

    public function verifyHash($password, $hash) {
        return password_verify($password, $hash);
    }
}