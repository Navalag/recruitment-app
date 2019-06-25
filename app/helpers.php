<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

if (!function_exists('storeCVFile')) {
    /**
     * Store uploaded file in publicly accessible directory.
     *
     * @param Request $request
     * @return string
     */
    function storeCVFile(Request $request)
    {
        if ($request->cv_url) {
            $cvFileName = "CV_" . $request->first_name . '_' . $request->last_name . '_' . time() . '.' . request()->cv_url->getClientOriginalExtension();
            $request->cv_url->storeAs('cv_applicants', $cvFileName);

            return $cvFileName;
        }
        return '';
    }
}

if (!function_exists('updateCVFile')) {
    /**
     * Delete old file and store new one in publicly accessible directory.
     *
     * @param $requestData
     * @param $applicant
     * @return void
     */
    function updateCVFile(&$requestData, $applicant): void
    {
        if (array_key_exists('cv_url', $requestData)) {
            Storage::delete('cv_applicants/' . $applicant->cv_url);

            $cvFileName = "CV_" . $applicant->first_name . '_' . $applicant->last_name . '_' . time() . '.' . $requestData['cv_url']->getClientOriginalExtension();
            $requestData['cv_url']->storeAs('cv_applicants', $cvFileName);
            // rewrite appropriate name
            $requestData['cv_url'] = $cvFileName;
        }
    }
}
