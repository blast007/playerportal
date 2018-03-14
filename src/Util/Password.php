<?php declare(strict_types=1);
/*
 * BZFlag Player Portal provides an interface for managing BZFlag
 * organizations, groups, and hosting keys.
 * Copyright (C) 2018  BZFlag & Associates
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

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