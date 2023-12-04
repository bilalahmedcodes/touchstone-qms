<?php

namespace App\Http\Controllers\SolarLtEvaluation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SolarLtCategoryRequest;
use App\Models\SolarLtCategory;
use Illuminate\Support\Facades\Session;

class SolarLtCategoryController extends Controller
{

    public function create()
    {
        return view('solar-lts.voice-evaluations.categories.create');
    }
    public function store(SolarLtCategoryRequest $request)
    {
        SolarLtCategory::create($request->all());
        Session::flash('success', 'Category added successfully!');
        return redirect()->route('solar-lts.voice-evaluations.index');
    }
    public function edit(SolarLtCategory $category)
    {
        return view('solar-lts.voice-evaluations.categories.edit', compact('category'));
    }

    public function update(SolarLtCategoryRequest $request, SolarLtCategory $category)
    {
        $category->update($request->all());
        Session::flash('success', 'Category updated successfully!');
        return redirect()->route('solar-lts.voice-evaluations.index');
    }

    public function destroy(SolarLtCategory $category)
    {
        $category->delete();
        Session::flash('success', 'Category deleted successfully!');
        return redirect()->route('solar-lts.voice-evaluations.index');
    }

}
