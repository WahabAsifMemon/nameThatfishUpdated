<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Term;
use App\Models\About;
use App\Models\Setting;
use App\Models\PrivacyPolicy;

use App\Http\Controllers\Controller;

class MainController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('dashboard.index', compact('users'));
    }

    public function privacy()
    {
        return view('dashboard.privacy');
    }

    public function savePrivacy(Request $request)
    {
        $description = $request->input('description');

        $privacyPolicy = PrivacyPolicy::first();

        if ($privacyPolicy) {
            $privacyPolicy->update([
                'description' => $description
            ]);
        } else {
            PrivacyPolicy::create([
                'description' => $description
            ]);
        }
        return redirect()->route('privacy.from');
    }

    public function setting()
    {
        return view('dashboard.setting');
    }

    public function saveSetting(Request $request)
    {
        $app_url = $request->input('app_url');

        $setting = Setting::first();

        if ($setting) {
            $setting->update([
                'app_url' => $app_url,
                'app_icon' => $this->uploadAppIcon($request)
            ]);
        } else {
            Setting::create([
                'app_url' => $app_url,
                'app_icon' => $this->uploadAppIcon($request)
            ]);
        }
        

        return redirect()->route('setting.form');
    }

    private function uploadAppIcon(Request $request)
    {
        if ($request->hasFile('app_icon')) {
            $appIcon = $request->file('app_icon');
            $filename = time() . '.' . $appIcon->getClientOriginalExtension();
            $appIcon->move(public_path('uploads'), $filename);
            return $filename;
        }
    
        return null;
    }

    public function term()
    {
        return view('dashboard.term');
    }

    public function saveTerm(Request $request)
    {
        $description = $request->input('description');

        $term = Term::first();

        if ($term) {
            $term->update([
                'description' => $description
            ]);
        } else {
            Term::create([
                'description' => $description
            ]);
        }
        return redirect()->route('term.from');
    }

    public function about()
    {
        return view('dashboard.about');
    }

    public function saveAbout(Request $request)
    {
        $description = $request->input('description');

        $about = About::first();

        if ($about) {
            $about->update([
                'description' => $description
            ]);
        } else {
            About::create([
                'description' => $description
            ]);
        }
        return redirect()->route('about.from');
    }
    
}