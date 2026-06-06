<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\OperationalHour;
use App\Models\LandingPageContent;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Fetch All Menus (for filtering)
        $allMenus = Menu::orderBy('id', 'desc')->get();
        $featuredMenus = $allMenus->where('is_featured', true);
        
        // If no featured, use latest 6 as featured
        if ($featuredMenus->isEmpty()) {
            $featuredMenus = $allMenus->take(6);
        }

        // 2. Fetch Operational Hours
        $hours = OperationalHour::orderBy('day_index')->get();

        // 3. Fetch Landing Page Content
        $contents = LandingPageContent::all()->pluck('value', 'key');

        // 4. Fetch Gallery Photos
        $galleries = \App\Models\Gallery::orderBy('order')->get();

        // 5. Fetch Special Packages
        $specialPackages = \App\Models\SpecialPackage::orderBy('order')->get();

        return view('welcome', compact('featuredMenus', 'allMenus', 'hours', 'contents', 'galleries', 'specialPackages'));
    }
}
