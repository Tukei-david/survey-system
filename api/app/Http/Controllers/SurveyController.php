<?php

namespace App\Http\Controllers;

use App\Models\survey;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\SurveyQuestion;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use App\Http\Resources\SurveyResource;
use App\Http\Requests\StoresurveyRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UpdatesurveyRequest;
use Illuminate\Support\Arr;

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
        return SurveyResource::collection(survey::where('user_id', $user->id)->paginate(5));
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

        // Create new questions
        foreach ($data['questions'] as $question) {
            $question['survey_id'] = $survey->id;
            $this->createQuestion($question);
        }

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
     * Display the specified resource.
     *
     * @param  \App\Models\survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function showForGuest(survey $survey)
    {
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
        $data = $request->validated();

        // Check if image was given and save on local file system

        if (isset($data['image'])) {
            // Save the link to relative path
            $relativepath = $this->saveImage($data['image']);
            $data['image'] = $relativepath;

            // var_dump($survey->image);

            // If there is an old imiage, delete it
            if ($survey->image) {
                $absolutePath = public_path($survey->image);
                File::delete($absolutePath);
            }
        }

        $survey->update($data);

        // Updating questions
        // Get ids as plain array of existing questions
        $existingIds = $survey->questions()->pluck('id')->toArray();
        // Get ids as plain array of new questions
        $newIds = Arr::pluck($data['questions'], 'id');
        // Find questions to delete
        $toDelete = array_diff($existingIds, $newIds);
        // Find questions to add
        $toAdd = array_diff($newIds, $existingIds);
        // Delete questions by $toDelete array
        SurveyQuestion::destroy($toDelete);
        // create new questions
        foreach ($data['questions'] as $question) {
            if (in_array($question['id'], $toAdd)) {
                $question['survey_id'] = $survey->id;
                $this->createQuestion($question);
            }
        }
        // Update existing questions
        $questionMap = collect($data['questions'])->keyBy('id');
        foreach ($survey->questions as $question ) {
            if (isset($questionMap[$question->id])) {
                $this->updateQuestion($question, $questionMap[$question->id]);
            }
        }

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

        // If there is an old imiage, delete it
        if ($survey->image) {
            $absolutePath = public_path($survey->image);
            File::delete($absolutePath);
        }

        return response('', 204);        
    }

    private function saveImage($image)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
            // Take out the base64 encoded text without mime type
            $image = substr($image, strpos($image, ','));
            // Get file extension
            $type = strtolower($type[1]);

            // check if file is an image
            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                throw new \Exception('invalid image type');
            }

            $image = str_replace(' ', '+', $image); // Replace white spaces with +
            $image = base64_decode($image);

            if ($image === false) {
                throw new \Exception('base64_decode failed');
            }

        }else {
            throw new \Exception('did not match data URI with image data');
        }

        $dir = 'images/';
        $file = Str::random() . '.' . $type;
        $absolutePath = public_path($dir);
        $relativepath = $dir . $file;

        // If the directory exists.. If it doesn't create directory
        if (!File::exists($absolutePath)) {
            File::makeDirectory($absolutePath, 8755, true);
        }

        file_put_contents($relativepath, $image);

        return $relativepath;
    }

    private function createQuestion($data)
    {
        // Check if data object (array in php) as data and convert it to json
        if (is_array($data['data'])) {
            $data['data'] = json_encode($data['data']);
        }

        $validator = Validator::make($data, [
            'question' => 'required|string',
            'type' => ['required', Rule::in([
                survey::TYPE_TEXT,
                survey::TYPE_TEXTAREA,
                survey::TYPE_SELECT,
                survey::TYPE_RADIO,
                survey::TYPE_CHECKBOX,
            ])],
            'description' => 'nullable|string',
            'data' => 'present',
            'survey_id' => 'exists:App\Models\Survey,id'
        ]);

        return SurveyQuestion::create($validator->validated());
    }

    private function updateQuestion( SurveyQuestion $question, $data)
    {
        if (is_array($data['data'])) {
            $data['data'] = json_encode($data['data']);
        }

        $validator = Validator::make($data, [
            'id' => 'exists:App\Models\SurveyQuestion,id',
            'question' => 'required|string',
            'type' => ['required', Rule::in([
                survey::TYPE_TEXT,
                survey::TYPE_TEXTAREA,
                survey::TYPE_SELECT,
                survey::TYPE_RADIO,
                survey::TYPE_CHECKBOX,
            ])],
            'description' => 'nullable|string',
            'data' => 'present',
            ]);
        
        return $question->update($validator->validated());
    }
}
