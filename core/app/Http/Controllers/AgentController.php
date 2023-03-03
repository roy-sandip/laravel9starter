<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $agents = Agent::paginate(25);
        return view('admin.agents.index', compact('agents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('admin.agents.create');   
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
                
        $data = $this->validateRequest($request);
        $agent = Agent::create([
            'name'  => $request->input('name'),
            'company'  => $request->input('company'),
            'phone'  => $request->input('phone'),
            'email'  => $request->input('email'),
            'address'  => $request->input('address'),
        ]);
        return redirect()->route('admin.agents.show', $agent->id)->with('success', 'Agent Created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function show($agent_id)
    {
        $agent = Agent::query()
                        ->withCount('shipments')
                        ->with('users')
                        ->findOrFail($agent_id);

        return view('admin.agents.show')->with([
                                                'agent' => $agent
                                            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function edit(Agent $agent)
    {
        return view('admin.agents.edit', compact('agent'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Agent $agent)
    {
        $agent->update($this->validateRequest($request));
        return redirect()->route('admin.agents.show', $agent->id)
                         ->with('success', 'Agent Information Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Agent $agent)
    {
        if(auth()->user()->agent_id == $agent->id)
        {
            return redirect()->back()->with('error', 'You cannot delete self agent.');
        }

        $shipments  = $agent->shipments()->count();
        if($shipments > 0)
        {
                return redirect()->back()->with('error', 'This agent has shipments, cannot delete.');
        }

        $agent->delete();
        return redirect()->route('admin.agents.index')->with('success', 'Agent Deleted.');
    }

    public function validateRequest(Request $request)
    {
        return $request->validate([
            'name'      => ['required', 'string', 'min:4', 'max:191'],
            'company'   => ['nullable',  'string', 'min:4', 'max:191'],
            'phone'     => ['nullable',  'string', 'max:191'],
            'email'     => ['nullable', 'email', 'max:191'],
            'address'   => ['nullable', 'string', 'max:300'],
        ]);
    }
}
