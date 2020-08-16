<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Image\ImageRepositories;
use Illuminate\Support\Facades\Auth;

/**
 * Class ImagesController
 * @package App\Http\Controllers\Api
 */
class ImagesController extends Controller
{
    /**
     * @var ImageRepositories
     */
    protected $imageRepositories;

    /**
     * @var int
     */
    protected $file_size = 0;

    /**
     * @var array
     */
    protected $groupType = [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ImageRepositories $imageRepositories)
    {
        $this->imageRepositories = $imageRepositories;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDiskImagesInfo()
    {
        $images = $this->imageRepositories->all();
        foreach ($images as $val) {
            $this->file_size += $val->image_size;
            if (isset($this->groupType[$val->image_type])) {
                $imageSizeMb = number_format($val->image_size / 1048576, 2);
                $this->groupType[$val->image_type]['total'] = $this->groupType[$val->image_type]['total'] + 1;
                $this->groupType[$val->image_type]['size_megabyte'] += (float)$imageSizeMb;
                $this->groupType[$val->image_type]['size_byte'] += $val->image_size;
            } else {
                $imageSizeMb = number_format($val->image_size / 1048576, 2);
                $this->groupType[$val->image_type] = [
                    'name' => strtoupper($val->image_type),
                    'total' => 1,
                    'size_megabyte' => (float)$imageSizeMb,
                    'size_byte' => $val->image_size
                ];
            }
        }
        $fileSizeMb = number_format($this->file_size / 1048576, 2);
        $response = [
            'data' => [
                'size_megabyte' => (float)$fileSizeMb,
                'size_byte' => $this->file_size,
                'count' => $images->count(),
                'conpositions' => $this->groupType
            ]
        ];

        return response()->json($response);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function imageUploadPost(Request $request)
    {
        $imageName = time() . '.' . $request->file->getClientOriginalExtension();
        $path = $request->file->move(public_path('images'), $imageName);

        $imgSizes = $path->getSize();

        $requestData = [
            'image_url' => env('APP_URL', false) . '/images/' . $imageName,
            'image_type' => $request->file->getClientOriginalExtension(),
            'image_size' => $imgSizes,
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
        ];
        $images = $this->imageRepositories->create($requestData);

        return response()->json(['success' => 'You have successfully upload file.', 'data' => $images]);

    }

    /**
     * @param Request $requset
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function removeImage(Request $requset, $id)
    {
        try{
            $img = $this->imageRepositories->where('id',$id)->first();
            $image_path = $img->image_url;
            $path = explode('http://localhost:8000/images/',$image_path);
            unlink(public_path().'/images/'.$path[1]);
            $this->imageRepositories->delete($id);
            return response()->json([]);
        }catch (\Exception $e){
            return response()->json([]);
        }
    }
}
