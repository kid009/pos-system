<?php

namespace App\Http\Controllers;

use App\Actions\Category\CreateCategoryAction;
use App\Actions\Category\UpdateCategoryAction;
use App\DTOs\Category\CategoryDTO;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::orderBy('sort_order')
            ->orderBy('name')
            ->with('parent')
            ->get();

        return view('category.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('category.create', [
            'category' => new Category,
            'parentCategories' => $this->getParentCategories(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request, CreateCategoryAction $action)
    {
        $dto = CategoryDTO::formRequest($request->validated());

        $category = $action->execute($dto);

        return redirect()->route('category.index')->with('success', 'Created Category: '.$category->name);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('category.edit', [
            'category' => $category,
            'parentCategories' => $this->getParentCategories($category),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category, UpdateCategoryAction $action)
    {
        $dto = CategoryDTO::formRequest($request->validated());

        $action->execute($category, $dto);

        return redirect()->route('category.index')->with('success', 'Updated Category: '.$category->fresh()->name);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $name = $category->name;
        $category->delete();

        return redirect()->route('category.index')->with('delete', 'Deleted Category: '.$name);
    }

    /**
     * @return Collection<int, Category>
     */
    private function getParentCategories(?Category $exclude = null)
    {
        $query = Category::orderBy('sort_order')->orderBy('name');

        if ($exclude) {
            $query->whereKeyNot($exclude->id);
        }

        return $query->get();
    }
}
