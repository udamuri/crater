<?php

use Crater\Models\CompanySetting;
use Crater\Models\FileDisk;
use Crater\Models\Setting;
use Crater\Models\User;
use Illuminate\Database\Migrations\Migration;

class UpdateCraterVersion400 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // seed the file disk
        $this->fileDiskSeed();

        Setting::setSetting('version', '4.0.0');

        $user = User::where('role', 'admin')->first();

        if ($user && $user->role == 'admin') {
            $user->update([
                'role' => 'super admin',
            ]);

            // Update language
            $user->setSettings(['language' => CompanySetting::getSetting('language', $user->company_id)]);

            // Update user's addresses
            if ($user->addresses()->exists()) {
                foreach ($user->addresses as $address) {
                    $address->company_id = $user->company_id;
                    $address->user_id = null;
                    $address->save();
                }
            }

            // Update company settings
            $this->updateCompanySettings($user);

            // Update Creator
            $this->updateCreatorId($user);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

    private function fileDiskSeed()
    {
        $privateDisk = [
            'root' => config('filesystems.disks.local.root'),
            'driver' => 'local',
        ];

        $publicDisk = [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ];

        FileDisk::create([
            'credentials' => json_encode($publicDisk),
            'name' => 'local_public',
            'type' => 'SYSTEM',
            'driver' => 'local',
            'set_as_default' => false,
        ]);

        FileDisk::create([
            'credentials' => json_encode($privateDisk),
            'name' => 'local_private',
            'type' => 'SYSTEM',
            'driver' => 'local',
            'set_as_default' => true,
        ]);
    }

    private function updateCreatorId($user)
    {
        User::where('role', 'customer')->update(['creator_id' => $user->id]);
    }

    private function updateCompanySettings($user)
    {
        $settings = [
            'save_pdf_to_disk' => 'NO',
        ];

        CompanySetting::setSettings($settings, $user->company_id);
    }
}
