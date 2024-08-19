<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request = request();
        $query = Category::query();

        $categories = $query->SimplePaginate(4); // return Collection Object
        $categories = Category::with('parent')
            /* leftJoin('categories as parents', 'parents.id', '=', 'categories.parent_id')
            ->select([
                'categories.*',
                'parents.name as parent_name'
            ]) */
            ->select('categories.*')
            ->selectRaw("(SELECT COUNT(*) FROM products WHERE status = 'active' AND category_id = categories.id) as products_count")
            /*
            ->withCount([
            'products as prdcts_num' => function ($query) {
            $query->where('status' , '=', 'active');
            }
            ])
            */
            ->filter($request->query())
            // ->withTrashed() means return all results even those have value not null,
            // ->onlyTrashed() means return results of null value equal for deleted_at column.
            ->simplePaginate(4);
        return view('dashboard.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parents = Category::all();
        $category = new Category();
        return view('dashboard.categories.create', compact('parents', 'category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        /*

        - Mass Assignment means sending all preperties in one step,
          for security issues you need to declare fillable viriable contains array with all fillable inputs

        - against of of $fillable is $guarded

        - first calls white list    only mentioned inputs are allow to be filled
        - second calls black list   mentioned inputs are NOT allowed to be filled!

        - you use only one of them
        - using $guarded with empty array means all are allowed and this is a shortest way

        */

        $request->validate(Category::rules(), [
            'required' => 'This (:attribute) Is Required',
            'name.unique' => 'This Name Is Already Taken'
        ]);
        $request->merge([
            'slug' => Str::slug($request->post('name')),
        ]);

        $data = $request->except('image');
        $data['image'] = $this->uploadFile($request);

        Category::create($data);

        // PRG => Post Redirect Get
        return Redirect::route('dashboard.categories.index')
            ->with('success', 'Category Created!');
    }
    // $category = new Category([
    //     'name' => $request['name'],
    //     'parent_id' => $request['parent_id'],
    //     'slug' => Str::slug($request->post('name')),
    //     'description' => $request['description'],
    //     'image' => $request['image'],
    //     'status' => $request['status'],
    // ]);

    // $category->save();

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return view('dashboard.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $category = Category::findOrFail($id);
        } catch (Exception $e) {
            return redirect()->route('dashboard.categories.index')
                ->with('info', 'Record Not Found!');
        }

        $parents = Category::where('id', '<>', $id)
            ->where(function ($query) use ($id) {
                $query->whereNull('parent_id')
                    ->orwhere('parent_id', '<>', $id);
            })->get();
        return view('dashboard.categories.edit', compact('category', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        $request->validate(Category::rules($id));
        $category = Category::findOrFail($id);

        $old_image = $category->image;

        $data = $request->except('image');
        $new_image = $this->uploadFile($request);

        if ($new_image) {
            $data['image'] = $new_image;
        }

        $category->update($data);

        if ($old_image && isset($data['image'])) {
            /* here we use disk() because we change the default path,
            if we don't we can use delete() strait without disk()
            */
            Storage::disk('public')->delete($old_image);
        }

        return Redirect::route('dashboard.categories.index')
            ->with('success', 'Record Updated*');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        // Category::where('id', '=', $id)->delete();

        // Category::destroy($id);

        return redirect()->route('dashboard.categories.index')
            ->with('destroy', 'Record Deleted!');
    }

    protected function uploadFile(Request $request)
    {
        if (!$request->hasFile('image')) {
            return;
        }

        $file = $request->file('image');
        /*
        uploaded file object
        getClientOriginalName() you can use it with prefix or random num of date to keep original name And without overriding
        getSize() / getClientOriginalExtension() / getMimeType() Ex: image/png
        store will generate random name for file name
        use storeAs for custom path storeAs(store('uploads', $name,[])
         */
        $path = $file->store('uploads', [ // you don't need to send it with array
            'disk',
            'public'
        ]);
        return $path;
    }

    public function trash()
    {
        $categories = Category::onlyTrashed()->simplePaginate();
        return view('dashboard.categories.trash', compact('categories'));
    }

    public function restore(Request $request, $id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->route('dashboard.categories.trash')
            ->with('success', 'Category Restored!');
    }

    public function forceDelete($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->forceDelete();

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        return redirect()->route('dashboard.categories.trash')
            ->with('success', 'Category Deleted!');
    }
}
