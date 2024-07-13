<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Imports\ActivitiesImport;
use App\Models\Activity;
use App\Models\MK;
use App\Models\RPS;
use Illuminate\Http\Request;
use Illuminate\Notifications\Action;
use Maatwebsite\Excel\Facades\Excel;

class ActivitiesController extends Controller
{
    public function List()
    {
        $rpss = RPS::where('pengembang', auth()->user()->name)->get();
        $rps_id = [];
        foreach($rpss as $rps){
            $rps_id[]= $rps->id;
        }
        $activities = Activity::whereIn('id_rps',$rps_id)->get();
        // dd($activities);
        return view('dosen.Activities.list',compact('activities'));
    }

    public function Add()
    {
        $rpss = RPS::where('pengembang', auth()->user()->name)->get();
        // $rpss = RPS::all();
        return view('dosen.Activities.add',compact('rpss'));
    }

    public function Edit($id)
    {
        $activity = Activity::findOrFail($id);
        $rpss = RPS::where('pengembang', auth()->user()->name)->get();
        return view('dosen.Activities.edit', compact('activity', 'rpss'));
    }

    public function Store(Request $request){
        $request->validate([
            'minggu' => ['required', 'unique:activities', 'regex:/^[0-9-]+$/'],
            'sub_cpmk' => 'required',
            'materi' => 'required',
            'id_rps' => 'required',
        ]);
        $indikator = '<ul>';
        foreach($request->indikator as $key => $ind){
            if($ind != null){
                $indikator .= ' <li>'.$ind.'</li>';
            }
        }
        $indikator .= ' </ul>';

        $activity = new Activity($request->all());
        $activity['indikator'] = $indikator;
        $activity->save();
        return redirect('/dosen/activities/list-activity')->with('success', 'New Activities successfully added!');
    }

    public function Update(Request $request, $id){
        $request->validate([
            'minggu' => ['required', 'regex:/^[0-9-]+$/'],
            'sub_cpmk' => 'required',
            'materi' => 'required',
            'id_rps' => 'required',
        ]);
        $indikator = '<ul>';
        foreach($request->indikator as $key => $ind){
            if($ind != null){
                $indikator .= ' <li>'.$ind.'</li>';
            }
        }
        $indikator .= ' </ul>';

        $activity = Activity::find($id);
        $activity->update($request->all());
        $activity['indikator'] = $indikator;
        $activity->save();
        return redirect('/dosen/activities/list-activity')->with('success', 'New Activities successfully added!');
    }

    public function Delete($id)
    {
        $activity = Activity::find($id);
        $activity->delete();
        return redirect('/dosen/activities/list-activity')->with('success', 'Activity successfully deleted!');
    }

    public function create_wfile(Request $request)
    {
        try {
            $excel = $request->file('excel');
            $excelPath = round(microtime(true) * 1000) . '-' . str_replace(' ', '-', $excel->getClientOriginalName());
            $excel->move(public_path('../public/assets/excel/'), $excelPath);
            Excel::import(new ActivitiesImport, public_path('../public/assets/excel/' . $excelPath));
            return redirect('/dosen/activities/list-activity')->with('success', 'Activities successfully added!');
        } catch (\Exception $e) {
            dd($e);
            return redirect('/dosen/activities/add-activity')->with('error', "Terjadi kesalahan, silahkan periksa kembali data dalam excel anda!");
        }
    }
}
