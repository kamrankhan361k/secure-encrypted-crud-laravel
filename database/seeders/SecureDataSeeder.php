<?php

namespace Database\Seeders;

use App\Models\SecureData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class SecureDataSeeder extends Seeder
{
    public function run()
    {
        $sampleData = [
            [
                'name' => 'John Alexander Doe',
                'email' => 'john.doe@securecompany.com',
                'phone' => '+1-555-0101',
                'address' => '123 Main Street, Suite 100, New York, NY 10001, United States',
                'credit_card' => '4532-1234-5678-9012',
                'social_security_number' => '123-45-6789',
                'medical_info' => 'Patient has no known allergies. Blood type: O+. Regular medication: None.',
                'financial_info' => 'Primary account: ****1234. Investment portfolio: Moderate risk.',
                'security_level' => 'high',
                'is_active' => true,
            ],
            [
                'name' => 'Jane Marie Smith',
                'email' => 'jane.smith@techcorp.io',
                'phone' => '+1-555-0102',
                'address' => '456 Oak Avenue, Apartment 5B, Los Angeles, CA 90210, United States',
                'credit_card' => '5500-1234-5678-9012',
                'social_security_number' => '987-65-4321',
                'medical_info' => 'Allergic to penicillin. Blood type: A-. Current medication: Vitamin D supplements.',
                'financial_info' => 'Savings account: ****5678. Mortgage: $250,000 remaining.',
                'security_level' => 'critical',
                'is_active' => true,
            ],
            [
                'name' => 'Robert James Wilson',
                'email' => 'robert.wilson@financegroup.com',
                'phone' => '+1-555-0103',
                'address' => '789 Pine Road, Building C, Chicago, IL 60601, United States',
                'credit_card' => '3714-123456-78901',
                'social_security_number' => '456-78-9123',
                'medical_info' => 'History of high blood pressure. Blood type: B+. Medication: Lisinopril 10mg daily.',
                'financial_info' => 'Business account: ****9012. Credit score: 780.',
                'security_level' => 'medium',
                'is_active' => true,
            ],
            [
                'name' => 'Sarah Elizabeth Johnson',
                'email' => 'sarah.johnson@healthcare.org',
                'phone' => '+1-555-0104',
                'address' => '321 Elm Street, Floor 2, Miami, FL 33101, United States',
                'credit_card' => '6011-1234-5678-9012',
                'social_security_number' => '789-12-3456',
                'medical_info' => 'Diabetic patient. Blood type: AB-. Insulin dependent. Regular checkups required.',
                'financial_info' => 'Health savings account: ****3456. Insurance: Premium plan.',
                'security_level' => 'high',
                'is_active' => false,
            ],
            [
                'name' => 'Michael Thomas Brown',
                'email' => 'michael.brown@consultingfirm.biz',
                'phone' => '+1-555-0105',
                'address' => '654 Maple Drive, Unit 12, Seattle, WA 98101, United States',
                'credit_card' => '4111-1234-5678-9012',
                'social_security_number' => '234-56-7891',
                'medical_info' => 'No significant medical history. Blood type: O+. Annual physical completed.',
                'financial_info' => 'Investment account: ****7890. Retirement fund: 401(k) maxed.',
                'security_level' => 'low',
                'is_active' => true,
            ]
        ];

        foreach ($sampleData as $data) {
            try {
                SecureData::create($data);
                $this->command->info("Created record for: " . $data['name']);
            } catch (\Exception $e) {
                Log::error('Error creating sample record: ' . $e->getMessage());
                $this->command->error("Failed to create record for: " . $data['name']);
            }
        }

        $this->command->info('Successfully created ' . count($sampleData) . ' encrypted records.');
    }
}
