<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RequestFacility;
use App\Enums\FacilityStatus;
use App\Enums\FacilityType;
use App\Enums\RequestFacilityStatus;
use Carbon\Carbon;

class UpdateRequestFacilitiesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-request-facilities-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Facilities';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $requestFacilities = RequestFacility::where('status', RequestFacilityStatus::Approved)->get();


        foreach ($requestFacilities as $requestFacility) {
            $reservationDate = Carbon::parse("{$requestFacility->reservation_date} {$requestFacility->reservation_time}");
           if ($requestFacility->facility->type != FacilityType::Equipment && Carbon::now()->gt($reservationDate)) {
               $requestFacility->facility()->update(['status' => FacilityStatus::Available]);
               if ($requestFacility->status == RequestFacilityStatus::Pending) {
                   $requestFacility->update(['status' => RequestFacilityStatus::Rejected]);
               }
           }
        }
    }
}
