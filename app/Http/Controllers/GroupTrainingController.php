<?php

namespace App\Http\Controllers;


use App\Candidate;
use App\CandidateTraining;
use App\GroupTraining;
use App\RecruitmentStory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupTrainingController extends Controller
{
    public function add_group_training()
    {
        $cadre = User::whereIN('user_type_id',[4,5,12])
            ->where('department_info_id',Auth::user()->department_info_id)
            ->where('status_work','=',1)
            ->get();

        return view('recruitment.addGroupTraining')
            ->with('cadre',$cadre);
    }
    public  function  datatableTrainingGroupList(Request $request)
    {
        $list_type = $request->list_type;
        $group_training = GroupTraining::select('group_training.*','users.first_name','users.last_name')->
        join('users','users.id','group_training.leader_id')->
        where('group_training.status','=',$list_type)
            ->where('group_training.department_info_id','=',Auth::user()->department_info_id);
        return datatables($group_training)->make(true);
    }
    public function getCandidateForGroupTrainingInfo(Request $request)
    {

        if($request->ajax())
        {
            $candidate = Candidate::where('attempt_status_id','=',5)
                ->where('department_info_id','=',Auth::user()->department_info_id)->get();
            return $candidate;
        }
    }

    public function deleteGroupTraining(Request $request)
    {
        if($request->ajax())
        {
            // zmiana statusu szkolenia na usuniete
            $training_id = $request->id_training_group_to_delete;
            $training_grou = GroupTraining::find($training_id);
            $training_grou->status = 0;
            $training_grou->edit_cadre_id = Auth::user()->id;
            if($training_grou->save()){

                 $all_candidate = CandidateTraining::where('training_id','=',$training_id)->get();
                 foreach ($all_candidate as $item)
                 {
                     $candidate = Candidate::find($item->candidate_id);
                     $candidate->attempt_status_id = 5;
                     $candidate->save();
                     $candidate_story = RecruitmentStory::where('candidate_id','=',$item->candidate_id)
                         ->orderBy('id', 'desc')->first();
                     $candidate_story->attempt_status_id = 5;
                     $candidate_story->save();
                 }
                 return 1;
            }
        }
    }
    public function getGroupTrainingInfo(Request $request)
    {
        if($request->ajax())
        {

            $group_training = GroupTraining::
            where('id','=',$request->id_training_group)->get();

            $candidate_avaible = Candidate::whereIn('attempt_status_id',[5])
                ->where('department_info_id','=',Auth::user()->department_info_id)->get()
                ->toArray();

            $candidate_choice = DB::table('candidate')
                ->select(DB::raw('
                candidate.*         
            '))
                ->join('candidate_training', 'candidate_training.candidate_id', 'candidate.id')
                ->join('group_training', 'group_training.id', 'candidate_training.training_id')
                ->where('group_training.id','=',$request->id_training_group)
                ->get()->toArray();
            $merge_array = array_merge($candidate_choice,$candidate_avaible);

            $object_array['group_training'] = $group_training ;
            $object_array['candidate'] = $merge_array ;
            return $object_array;
        }
    }
    public function saveGroupTraining (Request $request)
    {
        if($request->ajax()){
            $start_date_training = $request->start_date_training;
            $start_hour_training = $request->start_hour_training;
            $cadre_id = $request->cadre_id;
            $comment_about_training = $request->comment_about_training;
            $avaible_candidate = $request->avaible_candidate;
            $choice_candidate = $request->choice_candidate;
            $saving_type = $request->saving_type;
            $flag = true;

            // nowe szkolenie lub instniejące
            if($saving_type == 1 && $request->id_training_group == 0) // 1 - nowy wpisz, 0 - edycja
            {
                $training = new GroupTraining();

            }else if($request->id_training_group != 0){
                $training = GroupTraining::find($request->id_training_group);
            }
            // wypełnienie danych odnośnie szkolenia
            $training->cadre_id = Auth::user()->id;
            $training->leader_id = $cadre_id;
            $training->department_info_id = Auth::user()->department_info_id;
            $training->comment = $comment_about_training;
            $training->candidate_count = count($choice_candidate);
            $training->training_date = $start_date_training;
            $training->training_hour = $start_hour_training;
            $training->status = 1; // dotępne szkolenie 2 - zakończone 0 - anulowane

            // Próba zapisu
            if($training->save())
            {
                $flag = true;
            }else{
                return 0;
            }
            // Gdy szkolenie się zapisało
            if($flag)
            {   // Pobernie id szkolenia
                $id = $training->id;
                // usunięcie kandydatów zapisanych na szkolenie, jeśli tacy istnieją
                CandidateTraining::where('training_id','=',$id)->delete();
                // dodanie nowych kandydatów do szkolenia
                for($i = 0 ;$i < count($choice_candidate) ; $i++){

                    $candidate = Candidate::find($choice_candidate[$i]);
                    $candidate->attempt_status_id = 6;
                    $candidate->save();
                    $candidate_story = RecruitmentStory::where('candidate_id','=',$choice_candidate[$i])
                        ->orderBy('id', 'desc')->first();
                    $candidate_story->attempt_status_id = 6;
                    $candidate_story->save();
                    $new_relation = new CandidateTraining();
                    $new_relation->training_id = $id;
                    $new_relation->candidate_id = $choice_candidate[$i];
                    $new_relation->save();
                }
                for($i =  0 ;$i < count($avaible_candidate) ; $i++){// osoby które zostły zdjęce ze szkolenia( znowu dostepne
                    $candidate = Candidate::find($avaible_candidate[$i]);
                    $candidate->attempt_status_id = 5;
                    $candidate->save();
                    $candidate_story = RecruitmentStory::where('candidate_id','=',$avaible_candidate[$i])
                        ->orderBy('id', 'desc')->first();
                    $candidate_story->attempt_status_id = 5;
                    $candidate_story->save();
                }
                return 1;
            }else
                return 0;
        }
    }
}
