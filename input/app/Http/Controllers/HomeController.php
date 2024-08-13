<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TagList; // Assuming the model is named Certificate
use Validator;

class HomeController extends Controller
{
    /**
     * Show the form for adding certificates.
     *
     * @return \Illuminate\View\View
     */
    public function addMore()
    {
        return view('addMore'); 
    }

    /**
     * Handle the form submission.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addMorePost(Request $request)
    {
        // Define validation rules for dynamic fields
        $rules = [];
        foreach ($request->input('certificate', []) as $key => $value) {
            $rules["certificate.{$key}"] = 'required|string|max:255';
            $rules["date.{$key}"] = 'required|date';
            $rules["score.{$key}"] = 'required|integer|min:0|max:10';
        }

        // Validate the form data
        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            // Process and save the data
            $certificates = $request->input('certificate', []);
            $dates = $request->input('date', []);
            $scores = $request->input('score', []);

            foreach ($certificates as $key => $certificate) {
                TagList::create([
                    'certificate' => $certificate,
                    'date' => $dates[$key],
                    'score' => $scores[$key],
                ]);
            }

            return response()->json(['success' => 'Record Inserted Successfully.']);
        }

        // Return validation errors
        return response()->json(['error' => $validator->errors()->all()]);
    }
}
