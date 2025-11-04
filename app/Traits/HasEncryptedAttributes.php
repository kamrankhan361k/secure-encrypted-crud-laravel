<?php

namespace App\Traits;

use App\Services\AdvancedEncryptionService;

trait HasEncryptedAttributes
{
    protected $encryptionService;
    protected $encryptedAttributes = [];

    public function initializeHasEncryptedAttributes()
    {
        $this->encryptionService = new AdvancedEncryptionService();
    }

    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (in_array($key, $this->encryptedAttributes) && !empty($value)) {
            try {
                $salt = $this->getEncryptionSalt($key);
                return $this->encryptionService->complexDecrypt($value, $salt);
            } catch (\Exception $e) {
                \Log::error("Decryption failed for {$key}: " . $e->getMessage());
                return '[ENCRYPTED DATA]';
            }
        }

        return $value;
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encryptedAttributes) && !empty($value)) {
            $salt = $this->getEncryptionSalt($key);
            $value = $this->encryptionService->complexEncrypt($value, $salt);
        }

        return parent::setAttribute($key, $value);
    }

    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();

        foreach ($this->encryptedAttributes as $key) {
            if (isset($attributes[$key]) && !empty($attributes[$key])) {
                try {
                    $salt = $this->getEncryptionSalt($key);
                    $attributes[$key] = $this->encryptionService->complexDecrypt($attributes[$key], $salt);
                } catch (\Exception $e) {
                    $attributes[$key] = '[ENCRYPTED DATA]';
                }
            }
        }

        return $attributes;
    }

    /**
     * Get raw encrypted value from database
     */
    public function getRawEncryptedValue($key)
    {
        if (in_array($key, $this->encryptedAttributes)) {
            return $this->getRawOriginal($key);
        }

        return null;
    }

    /**
     * Get all raw encrypted values
     */
    public function getAllRawEncryptedValues()
    {
        $rawValues = [];
        foreach ($this->encryptedAttributes as $key) {
            $rawValues[$key] = $this->getRawOriginal($key);
        }

        return $rawValues;
    }

    protected function getEncryptionSalt($key)
    {
        return $this->getKey() ? $this->getKey() . '_' . $key : 'default_' . $key;
    }
}
