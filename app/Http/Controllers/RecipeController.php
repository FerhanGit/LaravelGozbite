<?php

namespace App\Http\Controllers;

use App\Recipe;
use App\RecipeImage;
use App\User;
use function compact;
use function dump;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use function storage_path;

class RecipeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function lists(Request $request, $category = null, $user = null)
    {
        $where = [];
        if (!empty($category)) {
            $where['category'] = $category;
        }
        if (!empty($user)) {
            $where['user_id'] = $user;
        }
        $recipes = Recipe::where($where)->orderBy('created_at', 'desc')->paginate(5);
        $categories = Recipe::distinct()->get(['category']);
        $users = User::distinct()->get(['id', 'name']);

        //$recipes = $request->user()->recipes->sortByDesc('created_at');

        return view('recipe.list', ['recipes' => $recipes, 'categories' => $categories, 'users' => $users]);
    }

    public function listsByUser(Request $request, $user = null)
    {
        $where = [];
        if (!empty($user)) {
            $where['user_id'] = $user;
        }

        $recipes = Recipe::where($where)->orderBy('created_at', 'desc')->paginate(5);
        $categories = Recipe::distinct()->get(['category']);
        $users = User::distinct()->get(['id', 'name']);

        return view('recipe.list', ['recipes' => $recipes, 'categories' => $categories, 'users' => $users]);
    }

    public function listsByCategory(Request $request, $category = null)
    {
        $where = [];
        if (!empty($category)) {
            $where['category'] = $category;
        }

        $recipes = Recipe::where($where)->orderBy('created_at', 'desc')->paginate(5);
        $categories = Recipe::distinct()->get(['category']);
        $users = User::distinct()->get(['id', 'name']);

        return view('recipe.list', ['recipes' => $recipes, 'categories' => $categories, 'users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('recipe.create');
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'title' => [
                'required',
                Rule::unique('recipe', 'id'),
            ],
            'category' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'content' => 'nullable'
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            // store
            $recipe = new Recipe();
            $recipe->title       = Input::get('title');
            $recipe->category      = Input::get('category');
            $recipe->content = Input::get('content');
            $recipe->user_id = Auth::user()->id;
            $recipe->save();

            $this->uploadImages($request, $recipe);

            // redirect
            Session::flash('success', 'Successfully created Recipe!');

            return redirect('recipe');
        }

        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Recipe $recipe)
    {
        return view('recipe.show', compact('recipe'));
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Recipe $recipe)
    {
        return view('recipe.edit', compact('recipe'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recipe $recipe)
    {
        $rules = array(
            'title' => [
                'required',
                Rule::unique('recipe', 'id')->ignore($recipe->id),
            ],
            'category' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'content' => 'nullable'
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            // store
            $recipe->title = Input::get('title');
            $recipe->category = Input::get('category');
            $recipe->content = Input::get('content');
            $recipe->user_id = Auth::user()->id;
            $recipe->save();

            $this->uploadImages($request, $recipe);

            // redirect
            Session::flash('success', 'Successfully created Recipe!');

            return redirect()->route('recipe.show', ['recipe' => $recipe->id]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Recipe  $recipe
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Recipe $recipe)
    {
        $recipe->delete();
        return redirect('recipe');
    }


    public function deleteImage(Request $request, RecipeImage $recipeImage)
    {
        Storage::delete(public_path('storage/public/images/recipe/thumbnail/' . $recipeImage->name));
        Storage::delete(public_path('storage/public/images/recipe/thumbnail/' . $recipeImage->name_thumb));

        $recipe_id = $recipeImage->recipe_id;
        $recipeImage->delete();

        return redirect()->route('recipe.show', ['recipe' => $recipe_id]);
    }

    /**
     * Create a thumbnail of specified size
     *
     * @param string $path path of thumbnail
     * @param int $width
     * @param int $height
     */
    public function createThumbnail($path, $width, $height)
    {
        $img = Image::make($path)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save($path);
    }



    private function uploadImages(Request $request, Recipe $recipe)
    {
        if($request->hasFile('image')) {

            //get filename with extension
            $filenameWithExtension = $request->file('image')->getClientOriginalName();

            //get filename without extension
            $fileName = pathinfo($filenameWithExtension, PATHINFO_FILENAME);

            //get file extension
            $extension = $request->file('image')->getClientOriginalExtension();

            //filename to store
            $imageName = $fileName.'_'.time().'.'.$extension;

            //small thumbnail name
            $imageNameThumb = $fileName.'_small_'.time().'.'.$extension;

            //Upload File
            $request->file('image')->storeAs('public/images/recipe', $imageName);
            $request->file('image')->storeAs('public/images/recipe/thumbnail', $imageNameThumb);

            //create small thumbnail
            $imageNameThumbPath = public_path('storage/public/images/recipe/thumbnail/'.$imageNameThumb);
            $this->createThumbnail($imageNameThumbPath, 150, 93);

            $recipeImages = new \App\RecipeImage();
            $recipeImages->recipe_id = $recipe->id;
            $recipeImages->name = $imageName;
            $recipeImages->name_thumb = $imageNameThumb ?? $imageName;
            $recipeImages->save();
        }
    }
}
