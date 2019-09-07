<?php

namespace App\Http\Controllers;

use App\RuleSet;
use Illuminate\Http\Request;

class RuleSetController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Ruleset Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the Rulesets.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        // Além disso, só os admin deviam poder aceder aqui
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rules = RuleSet::all();
        return view('rules.index')->with([
            'rules' => $rules,
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RuleSet  $ruleSet
     * @return \Illuminate\Http\Response
     */
    public function show(RuleSet $ruleSet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RuleSet  $ruleSet
     * @return \Illuminate\Http\Response
     */
    public function edit(RuleSet $rule)
    {
        return view('rules.edit')->with([
            //'rule' => $rule,
            'id' => $rule->id,
            'name' => $rule->name,
            'material' => $rule->material,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RuleSet  $ruleSet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RuleSet $ruleSet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RuleSet  $ruleSet
     * @return \Illuminate\Http\Response
     */
    public function destroy(RuleSet $ruleSet)
    {
        //
    }
}
