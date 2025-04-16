<?php

namespace App\Observers;

use App\Models\Company;
use App\Models\Image;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CompanyObserver
{
    /**
     * Handle the Company "created" event.
     *
     * @param Company $company
     * @return void
     */
    public function created(Company $company)
    {

    }

    /**
     * Handle the Company "updated" event.
     *
     * @param Company $company
     * @return void
     */
    public function updating(Company $company)
    {
        $document = $company->getOriginal('document');
        if ($company->document !== $document) {
            $company->document = $this->saveFile($company->document);
            $this->deleteFile($document);
        }

        $audio = $company->getOriginal('audio');
        if ($company->audio !== $audio) {
            $company->audio = $this->saveFile($company->audio);
            $this->deleteFile($audio);
        }
    }

    /**
     * Handle the Company "deleted" event.
     *
     * @param Company $company
     * @return void
     */
    public function deleted(Company $company)
    {
//        $this->deleteFile($company->document);
//        $this->deleteFile($company->audio);
    }

    /**
     * Handle the Company "restored" event.
     *
     * @param Company $company
     * @return void
     */
    public function restored(Company $company)
    {
        //
    }

    /**
     * Handle the Company "force deleted" event.
     *
     * @param Company $company
     * @return void
     */
    public function forceDeleted(Company $company)
    {
        $this->deleteFile($company->document);
        $this->deleteFile($company->audio);

        Image::destroy($company->images->pluck('id'));
    }


    /**
     * @param $file
     * @return string|null
     */
    public function saveFile($file)
    {
        if ($file->isFile()) {
            $filename = time() . '_' . Str::slug($file->getClientOriginalName());
            $date = new Carbon();
            $date->locale('en');
            $folder = "storage/CompanyDocuments/" . $date->monthName . $date->year;
            $file->move($folder, $filename);
            return $folder . '/' . $filename;
        }
        return null;
    }

    /**
     * @param $file
     * @return bool
     */
    public function deleteFile($file)
    {
        if (isset($file))
            return File::delete(public_path($file));
        return false;

    }
}
