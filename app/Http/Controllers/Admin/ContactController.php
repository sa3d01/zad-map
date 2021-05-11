<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bank;
use App\Models\Category;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;

class ContactController extends MasterController
{
    public function __construct(Contact $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    public function index()
    {
        $rows = $this->model->latest()->get();
        foreach ($rows as $row){
            $row->update([
               'read'=>true
            ]);
        }
        return view('Dashboard.contact.index', compact('rows'));
    }


}
