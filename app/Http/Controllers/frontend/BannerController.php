<?php

namespace App\Http\Controllers\frontend;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BannerController extends Controller
{
    public function click()
    {
        $banner = Banner::findOrFail(1); // Correct way to get a record by ID

        // Increment click count
        $banner->increment('click_count');

        // Redirect back or to a specific banner link
        return redirect()->back(); // or redirect()->to($banner->link);
    }

}
