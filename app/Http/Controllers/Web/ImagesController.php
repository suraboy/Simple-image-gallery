<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\Image\ImageRepositories;

class ImagesController extends Controller
{
    protected $imageRepositories;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ImageRepositories $imageRepositories)
    {
        $this->imageRepositories = $imageRepositories;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('pages.images.gallery');
    }
}
