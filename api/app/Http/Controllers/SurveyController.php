<?php

namespace App\Http\Controllers;

use App\Models\survey;
use Illuminate\Http\Request;
use App\Http\Resources\SurveyResource;
use App\Http\Requests\StoresurveyRequest;
use App\Http\Requests\UpdatesurveyRequest;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $user = $request->user(); // Get current user
        return SurveyResource::collection(survey::where('user_id', $user->id)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoresurveyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoresurveyRequest $request)
    {
        //
        $data = $request->validated();

        // Check if image was given and save on local file system

        if (isset($data['image'])) {
            // Save the link to relative path
            $relativepath = $this->saveImage($data['image']);
            $data['image'] = $relativepath;
        }

        $survey = survey::create($data);

        return new SurveyResource($survey);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function show(survey $survey, Request $request)
    {
        // Check if the user is permitted to see the survey
        $user = $request->user();
        if ($user->id !== $survey->user_id) {
            return abort(403, 'Unauthorized action.');
        }

        return new SurveyResource($survey);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatesurveyRequest  $request
     * @param  \App\Models\survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatesurveyRequest $request, survey $survey)
    {
        //
        $survey->update($request->validated());

        return new SurveyResource($survey);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function destroy(survey $survey, Request $request)
    {
        // Check if the user is permitted to see the survey
        $user = $request->user();
        if ($user->id !== $survey->user_id) {
            return abort(403, 'Unauthorized action.');
        }

        $survey->delete();
        return response('', 204);        
    }

    private function saveImage($image)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
            // Take out the base64 encoded text without mime type
            $image = substr($image, strpos($image, ','));
            // Get file extension
            $type = strtolower($type[1]);

        }else {
            throw new \Exception('did not match data URI with image data');
        }
    }
}
