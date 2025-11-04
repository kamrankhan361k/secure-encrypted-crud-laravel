<?php

namespace App\Services;

use Illuminate\Contracts\Encryption\EncryptException;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class AdvancedEncryptionService
{
    private $primaryKey;
    private $secondaryKey;
    private $iv;

    public function __construct()
    {
        $this->primaryKey = base64_decode(env('PRIMARY_ENCRYPTION_KEY'));
        $this->secondaryKey = base64_decode(env('SECONDARY_ENCRYPTION_KEY'));
        $this->iv = substr(base64_decode(env('ENCRYPTION_IV_KEY')), 0, 16);
    }

    /**
     * Multi-layer encryption with data obfuscation
     */
    public function complexEncrypt($data, $additionalSalt = null)
    {
        try {
            if (is_array($data)) {
                $data = json_encode($data, JSON_UNESCAPED_UNICODE);
            }

            // Layer 1: Standard Laravel encryption
            $layer1 = Crypt::encryptString($data);

            // Layer 2: Custom AES-256-CBC encryption
            $layer2 = $this->customAESEncrypt($layer1, $additionalSalt);

            // Layer 3: Base64 with character substitution
            $layer3 = $this->base64Obfuscate($layer2);

            // Layer 4: Add complex structure
            $final = $this->addComplexStructure($layer3, $additionalSalt);

            return $final;

        } catch (\Exception $e) {
            throw new EncryptException('Encryption failed: ' . $e->getMessage());
        }
    }

    /**
     * Multi-layer decryption
     */
    public function complexDecrypt($encryptedData, $additionalSalt = null)
    {
        try {
            // Reverse layer 4
            $layer3 = $this->removeComplexStructure($encryptedData, $additionalSalt);

            // Reverse layer 3
            $layer2 = $this->base64Deobfuscate($layer3);

            // Reverse layer 2
            $layer1 = $this->customAESDecrypt($layer2, $additionalSalt);

            // Reverse layer 1
            $data = Crypt::decryptString($layer1);

            $decoded = json_decode($data, true);
            return json_last_error() === JSON_ERROR_NONE ? $decoded : $data;

        } catch (\Exception $e) {
            throw new DecryptException('Decryption failed: ' . $e->getMessage());
        }
    }

    /**
     * Custom AES-256-CBC encryption
     */
    private function customAESEncrypt($data, $salt = null)
    {
        $key = $this->generateDerivedKey($salt);

        $encrypted = openssl_encrypt(
            $data,
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $this->iv
        );

        if ($encrypted === false) {
            throw new EncryptException('AES encryption failed');
        }

        return base64_encode($encrypted);
    }

    /**
     * Custom AES-256-CBC decryption
     */
    private function customAESDecrypt($data, $salt = null)
    {
        $key = $this->generateDerivedKey($salt);

        $decrypted = openssl_decrypt(
            base64_decode($data),
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $this->iv
        );

        if ($decrypted === false) {
            throw new DecryptException('AES decryption failed');
        }

        return $decrypted;
    }

    /**
     * Generate derived key using multiple keys and salt
     */
    private function generateDerivedKey($salt = null)
    {
        $baseKey = $this->primaryKey . $this->secondaryKey;
        if ($salt) {
            $baseKey .= $salt;
        }

        $derivedKey = hash('sha256', $baseKey, true);
        $derivedKey = hash('sha256', $derivedKey . $this->secondaryKey, true);

        return $derivedKey;
    }

    /**
     * Obfuscate base64 string
     */
    private function base64Obfuscate($data)
    {
        $base64 = base64_encode($data);

        $substitutionMap = [
            '=' => '~', '+' => '_', '/' => '-',
            '0' => 'z', '1' => 'y', '2' => 'x', '3' => 'w', '4' => 'v',
            '5' => 'u', '6' => 't', '7' => 's', '8' => 'r', '9' => 'q'
        ];

        return strtr($base64, $substitutionMap);
    }

    /**
     * Deobfuscate base64 string
     */
    private function base64Deobfuscate($data)
    {
        $substitutionMap = [
            '~' => '=', '_' => '+', '-' => '/',
            'z' => '0', 'y' => '1', 'x' => '2', 'w' => '3', 'v' => '4',
            'u' => '5', 't' => '6', 's' => '7', 'r' => '8', 'q' => '9'
        ];

        $normalized = strtr($data, $substitutionMap);
        return base64_decode($normalized);
    }

    /**
     * Add complex structure with random padding
     */
    private function addComplexStructure($data, $salt = null)
    {
        $timestamp = time();
        $randomPadding = Str::random(32);

        $segments = [
            'v' => '2.0',
            'ts' => $timestamp,
            'pad' => $randomPadding,
            'data' => $data,
            'checksum' => $this->generateChecksum($data, $timestamp, $salt),
            'end' => 'EOF'
        ];

        $structured = implode('|##|', $segments);
        $final = Str::random(16) . '||' . $structured . '||' . Str::random(16);

        return base64_encode($final);
    }

    /**
     * Remove complex structure
     */
    private function removeComplexStructure($data, $salt = null)
    {
        $decoded = base64_decode($data);
        $structured = substr($decoded, 18, -18);
        $segments = explode('|##|', $structured);

        if (count($segments) !== 6) {
            throw new DecryptException('Invalid data structure');
        }

        $checksum = $this->generateChecksum($segments[3], $segments[1], $salt);
        if (!hash_equals($checksum, $segments[4])) {
            throw new DecryptException('Data integrity check failed');
        }

        return $segments[3];
    }

    /**
     * Generate checksum for data integrity
     */
    private function generateChecksum($data, $timestamp, $salt = null)
    {
        $checkString = $data . $timestamp . $this->primaryKey . $salt;
        return hash('sha256', $checkString);
    }

    /**
     * Quick encryption for simple use cases
     */
    public function quickEncrypt($data)
    {
        return $this->complexEncrypt($data);
    }

    /**
     * Quick decryption for simple use cases
     */
    public function quickDecrypt($data)
    {
        return $this->complexDecrypt($data);
    }
}
