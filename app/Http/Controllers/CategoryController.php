<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class CategoryController extends Controller
{
    public function index()
    {
        $role = Role::find(Auth::user()->role_id);
        $company_id = auth()->user()->company_id;

        if ($role->hasPermissionTo('category')) {
            $lims_categories = Category::where('is_active', true)->where('company_id', $company_id)->pluck('name', 'id');
            $lims_category_all = Category::where('is_active', true)
                ->where('company_id', $company_id)->get();
            // dd($lims_categories);
            return view('category.create', compact('lims_categories', 'lims_category_all'));
        } else {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
        }

    }

    public function categoryData(Request $request)
    {
        $columns = array(
            0 => 'id',
            2 => 'name',
            3 => 'parent_id',
            4 => 'is_active',
        );
        $company_id = auth()->user()->company_id;

        $totalData = Category::where('is_active', true)
            ->where('company_id', $company_id)
            ->count();
        $totalFiltered = $totalData;

        if ($request->input('length') != -1) {
            $limit = $request->input('length');
        } else {
            $limit = $totalData;
        }

        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        if (empty($request->input('search.value'))) {
            $categories = Category::offset($start)
                ->where('is_active', true)
                ->where('company_id', $company_id)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $categories = Category::where([
                ['name', 'LIKE', "%{$search}%"],
                ['is_active', true],
            ])->where('company_id', $company_id)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)->get();

            $totalFiltered = Category::where([
                ['name', 'LIKE', "%{$search}%"],
                ['is_active', true],
            ])->where('company_id', $company_id)
                ->count();
        }
        $data = array();
        if (!empty($categories)) {
            foreach ($categories as $key => $category) {
                $nestedData['id'] = $category->id;
                $nestedData['key'] = $key;

                if ($category->image) {
                    $nestedData['image'] = '<img src="' . url('public/images/category', $category->image) . '" height="70" width="70">';
                } else {
                    $nestedData['image'] = '<img src="' . url('public/images/product/zummXD2dvAtI.png') . '" height="80" width="80">';
                }

                $nestedData['name'] = $category->name;

                if ($category->parent_id) {
                    $nestedData['parent_id'] = Category::find($category->parent_id)
                        ->name;
                } else {
                    $nestedData['parent_id'] = "N/A";
                }

                $nestedData['number_of_product'] = $category->product()->where('is_active', true)
                    ->where('company_id', $company_id)
                    ->count();
                $nestedData['stock_qty'] = $category->product()->where('is_active', true)
                    ->where('company_id', $company_id)
                    ->sum('qty');
                $total_price = $category->product()->where('is_active', true)
                    ->where('company_id', $company_id)
                    ->sum(DB::raw('price * qty'));
                $total_cost = $category->product()->where('is_active', true)
                    ->where('company_id', $company_id)
                    ->sum(DB::raw('cost * qty'));

                if (config('currency_position') == 'prefix') {
                    $nestedData['stock_worth'] = config('currency') . ' ' . $total_price . ' / ' . config('currency') . ' ' . $total_cost;
                } else {
                    $nestedData['stock_worth'] = $total_price . ' ' . config('currency') . ' / ' . $total_cost . ' ' . config('currency');
                }

                $nestedData['options'] = '<div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . trans("file.action") . '
                              <span class="caret"></span>
                              <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                                <li>
                                    <button type="button" data-id="' . $category->id . '" class="open-EditCategoryDialog btn btn-link" data-toggle="modal" data-target="#editModal" ><i class="dripicons-document-edit"></i> ' . trans("file.edit") . '</button>
                                </li>
                                <li class="divider"></li>' .
                \Form::open(["route" => ["category.destroy", $category->id], "method" => "DELETE"]) . '
                                <li>
                                  <button type="submit" class="btn btn-link" onclick="return confirmDelete()"><i class="dripicons-trash"></i> ' . trans("file.delete") . '</button>
                                </li>' . \Form::close() . '
                            </ul>
                        </div>';
                $data[] = $nestedData;
            }
        }
        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );

        echo json_encode($json_data);
    }

    public function store(Request $request)
    {
        $company_id = auth()->user()->company_id;
        $request->name = preg_replace('/\s+/', ' ', $request->name);
        $this->validate($request, [
            'name' => [
                'max:255',
                Rule::unique('categories')->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ],
            'image' => 'image|mimes:jpg,jpeg,png,gif',
        ]);
        $image = $request->image;
        if ($image) {
            $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
            $imageName = date("Ymdhis");
            $imageName = $imageName . '.' . $ext;
            $image->move('public/images/category', $imageName);

            $lims_category_data['image'] = $imageName;
        }
        $lims_category_data['name'] = $request->name;
        $lims_category_data['company_id'] = $company_id;
        $lims_category_data['parent_id'] = $request->parent_id;
        $lims_category_data['is_active'] = true;
        Category::create($lims_category_data);
        return redirect('category')->with('message', 'Category inserted successfully');
    }

    public function edit($id)
    {

        $company_id = auth()->user()->company_id;
        $lims_category_data = Category::findOrFail($id)->where('company_id', $company_id);
        $lims_parent_data = Category::where('id', $lims_category_data['parent_id'])
            ->where('company_id', $company_id)
            ->first();
        if ($lims_parent_data) {
            $lims_category_data['parent'] = $lims_parent_data['name'];
        }

        return $lims_category_data;
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => [
                'max:255',
                Rule::unique('categories')->ignore($request->category_id)->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ],
            'image' => 'image|mimes:jpg,jpeg,png,gif',
        ]);

        $input = $request->except('image');
        $image = $request->image;
        if ($image) {
            $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
            $imageName = date("Ymdhis");
            $imageName = $imageName . '.' . $ext;
            $image->move('public/images/category', $imageName);
            $input['image'] = $imageName;
        }
        $lims_category_data = Category::findOrFail($request->category_id);
        $lims_category_data->update($input);
        return redirect('category')->with('message', 'Category updated successfully');
    }

    public function import(Request $request)
    {
        //get file
        $upload = $request->file('file');
        $ext = pathinfo($upload->getClientOriginalName(), PATHINFO_EXTENSION);
        if ($ext != 'csv') {
            return redirect()->back()->with('not_permitted', 'Please upload a CSV file');
        }

        $filename = $upload->getClientOriginalName();
        $filePath = $upload->getRealPath();
        //open and read
        $file = fopen($filePath, 'r');
        $header = fgetcsv($file);
        $escapedHeader = [];
        //validate
        foreach ($header as $key => $value) {
            $lheader = strtolower($value);
            $escapedItem = preg_replace('/[^a-z]/', '', $lheader);
            array_push($escapedHeader, $escapedItem);
        }
        //looping through othe columns
        while ($columns = fgetcsv($file)) {
            if ($columns[0] == "") {
                continue;
            }

            foreach ($columns as $key => $value) {
                $value = preg_replace('/\D/', '', $value);
            }
            $data = array_combine($escapedHeader, $columns);
            $category = Category::firstOrNew(['name' => $data['name'], 'is_active' => true]);
            if ($data['parentcategory']) {
                $parent_category = Category::firstOrNew(['name' => $data['parentcategory'], 'is_active' => true]);
                $parent_id = $parent_category->id;
            } else {
                $parent_id = null;
            }

            $category->parent_id = $parent_id;
            $category->is_active = true;
            $category->save();
        }
        return redirect('category')->with('message', 'Category imported successfully');
    }

    public function deleteBySelection(Request $request)
    {
        $category_id = $request['categoryIdArray'];
        foreach ($category_id as $id) {
            $lims_product_data = Product::where('category_id', $id)->get();
            foreach ($lims_product_data as $product_data) {
                $product_data->is_active = false;
                $product_data->save();
            }
            $lims_category_data = Category::findOrFail($id);
            if ($lims_category_data->image) {
                unlink('public/images/category/' . $lims_category_data->image);
            }

            $lims_category_data->is_active = false;
            $lims_category_data->save();
        }
        return 'Category deleted successfully!';
    }

    public function destroy($id)
    {
        $lims_category_data = Category::findOrFail($id);
        $lims_category_data->is_active = false;
        $lims_product_data = Product::where('category_id', $id)->get();
        foreach ($lims_product_data as $product_data) {
            $product_data->is_active = false;
            $product_data->save();
        }
        if ($lims_category_data->image) {
            unlink('public/images/category/' . $lims_category_data->image);
        }

        $lims_category_data->save();
        return redirect('category')->with('not_permitted', 'Category deleted successfully');
    }
}