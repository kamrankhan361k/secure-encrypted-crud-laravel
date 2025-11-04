<?php

namespace App\Console\Commands;

use App\Models\SecureData;
use App\Services\AdvancedEncryptionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class EncryptionMaintenanceCommand extends Command
{
    protected $signature = 'encryption:maintenance
                          {--check-integrity : Check encryption integrity}
                          {--reencrypt : Re-encrypt all records with new parameters}
                          {--stats : Show encryption statistics}';

    protected $description = 'Perform maintenance tasks on encrypted data';

    protected $encryptionService;

    public function __construct()
    {
        parent::__construct();
        $this->encryptionService = new AdvancedEncryptionService();
    }

    public function handle()
    {
        if ($this->option('check-integrity')) {
            $this->checkEncryptionIntegrity();
        } elseif ($this->option('reencrypt')) {
            $this->reencryptAllRecords();
        } elseif ($this->option('stats')) {
            $this->showEncryptionStatistics();
        } else {
            $this->info("Available options:");
            $this->line("  --check-integrity  Check if all records can be decrypted properly");
            $this->line("  --reencrypt        Re-encrypt all records (use with caution)");
            $this->line("  --stats            Show encryption statistics");
        }

        return Command::SUCCESS;
    }

    protected function checkEncryptionIntegrity()
    {
        $this->info("Checking encryption integrity for all records...");

        $records = SecureData::all();
        $total = $records->count();
        $successful = 0;
        $failed = 0;

        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        foreach ($records as $record) {
            try {
                // Try to access encrypted fields to trigger decryption
                $record->name;
                $record->email;
                $record->phone;

                $successful++;
            } catch (\Exception $e) {
                $failed++;
                $this->error("Record {$record->id} decryption failed: " . $e->getMessage());
                Log::error("Encryption integrity check failed for record {$record->id}", [
                    'error' => $e->getMessage(),
                    'record_id' => $record->id
                ]);
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info("Encryption Integrity Check Results:");
        $this->line("Total records: {$total}");
        $this->line("Successful decryptions: <fg=green>{$successful}</>");
        $this->line("Failed decryptions: " . ($failed > 0 ? "<fg=red>{$failed}</>" : "<fg=green>{$failed}</>"));

        if ($failed === 0) {
            $this->info("✅ All records passed encryption integrity check!");
        } else {
            $this->error("❌ {$failed} records failed encryption integrity check.");
        }
    }

    protected function reencryptAllRecords()
    {
        if (!$this->confirm('This will re-encrypt ALL records. This operation cannot be undone. Continue?')) {
            $this->info('Operation cancelled.');
            return;
        }

        $this->info("Re-encrypting all records...");

        $records = SecureData::all();
        $total = $records->count();
        $successful = 0;
        $failed = 0;

        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        foreach ($records as $record) {
            try {
                // Get current decrypted values
                $attributes = $record->getAttributes();

                // Update the record to trigger re-encryption
                $record->save();

                $successful++;
            } catch (\Exception $e) {
                $failed++;
                $this->error("Record {$record->id} re-encryption failed: " . $e->getMessage());
                Log::error("Re-encryption failed for record {$record->id}", [
                    'error' => $e->getMessage(),
                    'record_id' => $record->id
                ]);
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info("Re-encryption Results:");
        $this->line("Total records: {$total}");
        $this->line("Successfully re-encrypted: <fg=green>{$successful}</>");
        $this->line("Failed: " . ($failed > 0 ? "<fg=red>{$failed}</>" : "<fg=green>{$failed}</>"));
    }

    protected function showEncryptionStatistics()
    {
        $this->info("Encryption Statistics");

        $totalRecords = SecureData::count();
        $activeRecords = SecureData::active()->count();

        $securityLevels = SecureData::getSecurityLevels();
        $levelCounts = [];

        foreach (array_keys($securityLevels) as $level) {
            $levelCounts[$level] = SecureData::securityLevel($level)->count();
        }

        $this->line("Total records: <comment>{$totalRecords}</comment>");
        $this->line("Active records: <comment>{$activeRecords}</comment>");
        $this->line("Inactive records: <comment>" . ($totalRecords - $activeRecords) . "</comment>");

        $this->info("\nSecurity Level Distribution:");
        foreach ($levelCounts as $level => $count) {
            $percentage = $totalRecords > 0 ? round(($count / $totalRecords) * 100, 2) : 0;
            $this->line("  {$level}: <comment>{$count}</comment> ({$percentage}%)");
        }

        $this->info("\nEncryption Information:");
        $this->line("  Algorithm: <comment>AES-256-CBC</comment>");
        $this->line("  Encryption Layers: <comment>4 (Laravel + Custom AES + Obfuscation + Structure)</comment>");
        $this->line("  Key Derivation: <comment>SHA-256 with multiple rounds</comment>");
        $this->line("  Data Integrity: <comment>SHA-256 checksums</comment>");
    }
}
