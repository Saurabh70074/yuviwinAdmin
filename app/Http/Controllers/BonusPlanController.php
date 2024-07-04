<?php

namespace App\Http\Controllers;

use App\Models\CircleBetting;
use App\Models\FastParityBetting;
use App\Models\JetBetting;
use App\Models\ParityBetting;
use App\Models\Ranks;
use App\Models\Recharge;
use App\Models\Transaction;
use App\Models\Setting;
use App\Models\BousPlan;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BonusPlanController extends Controller
{
    public function index(){
        $pagetitle = "Bonus Plans";
        $plans = BousPlan::paginate(10);
        return view('bonusPlan.index',compact('pagetitle','plans'));
    }
    public function create(){
        $pagetitle = 'Bonus Plan Create';
        return view('bonusPlan.create',compact('pagetitle'));
    }
    public function store(Request $request){
        $request->validate([
            'amount' => ['required', 'numeric'],
            'plan_type' => ['required', 'in:1,2,3'],
            'game_count' => ($request->plan_type == 2) ? ['required', 'numeric'] : ['nullable'], 
            'recharge_amount' => ($request->plan_type == 3) ? ['required', 'numeric'] : ['nullable'] 
        ]);
        $plan = new BousPlan();
        $plan->amount = $request->amount;
        $plan->type = $request->plan_type;
        if($plan->type == 2){
            $plan->game_count = $request->game_count;
        }
        if($plan->type == 3){
            $plan->rechare_value = $request->recharge_amount;
        }
        if( $plan->save()){
            return redirect()->route('admin.bonusplans.index')->with('success','New Bonus Plan Added');
        }else{
            return redirect()->back()->with('error','Unable to add Bonus Plan.');
        }
    }
    public function destroy($id){
        $plan = BousPlan::find($id);
        if($plan){
            $plan->delete();
            return redirect()->route('admin.bonusplans.index')->with('success','Bonus Plan Deleted');
        }else{
            return redirect()->back()->with('error','Unable to find Bonus Plan.');
        }
    }
    public function edit($id){
        $pagetitle = 'Edit Bonus Plan';
        $plan = BousPlan::find($id);
        if($plan){
            return view('bonusPlan.edit',compact('pagetitle','plan'));
        }else{
            return redirect()->back()->with('error','Unable to find Bonus Plan.');
        }
    }
    public function update(Request $request,$id){
        $plan = BousPlan::find($id);
        if($plan){
            $request->validate([
                'amount' => ['required', 'numeric'],
                'plan_type' => ['required', 'in:1,2,3'],
                'game_count' => ($request->plan_type == 2) ? ['required', 'numeric'] : ['nullable'], 
                'recharge_amount' => ($request->plan_type == 3) ? ['required', 'numeric'] : ['nullable'] 
            ]);
            $plan->amount = $request->amount;
            $plan->type = $request->plan_type;
            if($plan->type == 2){
                $plan->game_count = $request->game_count;
            }
            if($plan->type == 3){
                $plan->rechare_value = $request->recharge_amount;
            }
            if( $plan->update()){
                return redirect()->route('admin.bonusplans.index')->with('success','Bonus Plan Updated successfully');
            }else{
                return redirect()->back()->with('error','Unable to update Bonus Plan.');
            }
        }else{
            return redirect()->back()->with('error','Unable to find Bonus Plan.');
        }
    }
    
}
