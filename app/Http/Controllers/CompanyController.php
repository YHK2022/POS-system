<?php

namespace App\Http\Controllers;
use App\Company;
use App\GeneralSetting;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lims_company_all = Company::where('is_active', true)->get();
            return view('master.company.index', compact('lims_company_all'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('master.company.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $company = Company::create($data);

        $request->request->add(['company_id' => $company->id]);
        $request->request->add(['site_title' => $company->name]);
        $request->request->add(['currency' => 1]);
        $request->request->add(['staff_access' => 'all']);
        $request->request->add(['date_format' => 'd-m-Y']);
        $request->request->add(['developed_by' => $company->name]);
        $request->request->add(['theme' => 'default.css']);
        $request->request->add(['currency_position' => 'suffix']);

        GeneralSetting::create($request->all());


        return redirect('master/company')->with('message', 'Company created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
           $lims_company_data = Company::find($id);
            return view('master.company.edit', compact('lims_company_data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        Company::find($id)->update($data);
        return redirect('master/company')->with('message', 'Currency updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
       $lims_company_data = Company::find($id);
        $lims_company_data->is_active = false;
        $lims_company_data->save();
        return redirect('master/company')->with('not_permitted','Data deleted successfully');
    }
}