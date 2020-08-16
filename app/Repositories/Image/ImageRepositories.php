<?php

namespace App\Repositories\Image;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\Image;

/**
 * Class ImageRepositories
 * @package namespace App\Repositories;
 */
class ImageRepositories extends BaseRepository implements ImageInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Image::class;
    }
}

