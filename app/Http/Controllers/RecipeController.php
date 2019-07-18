<?php

namespace App\Http\Controllers;

use App\Recipe;
use App\User;
use function compact;
use function dump;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
        //
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
                Rule::unique('recipe'),
            ],
            'category' => 'required',
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
                Rule::unique('recipe')->ignore($recipe->id),
            ],
            'category' => 'required',
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

            // redirect
            Session::flash('success', 'Successfully created Recipe!');

            return redirect('recipe');
            //
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
        //
    }
}
