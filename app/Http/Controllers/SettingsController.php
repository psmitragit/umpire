<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\PresetModel;
use Illuminate\Http\Request;
use App\Models\PayPresetModel;
use App\Models\GroundPresetModel;
use App\Models\Age_of_PlayersModel;
use App\Models\DayofWeekModel;
use App\Models\SchedulePresetModel;
use App\Models\TimePresetModel;
use App\Models\TotalGamePresetModel;
use App\Models\UmpireDurationPresetModel;
use App\Models\UmpirePositionModel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function add_preset()
    {
        $title = 'Presets';
        $admin_data = session('admin_data');
        $page_data = PresetModel::get();
        $data = compact('title', 'page_data', 'admin_data');
        return view('admin.presets')->with($data);
    }
    public function save_preset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            PresetModel::create(array('presetname' => $request->name));
            Session::flash('message', 'Success');
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            return response()->json(['errors' => 'Something went wrong']);
        }
    }
    public function update_preset(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            PresetModel::find($id)->update(array('presetname' => $request->name));
            Session::flash('message', 'Success');
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            return response()->json(['errors' => 'Something went wrong']);
        }
    }
    public function delete_preset($id)
    {
        try {
            $preset_row = PresetModel::find($id);
            $preset_row->schedule()->delete();
            $preset_row->age_of_players()->delete();
            $preset_row->locations()->delete();
            $preset_row->pay()->delete();
            $preset_row->time()->delete();
            $preset_row->umpire_duration()->delete();
            $preset_row->total_game()->delete();
            $preset_row->umpire_position()->delete();
            $preset_row->day_of_week()->delete();
            $preset_row->delete();
            Session::flash('message', 'Success');
        } catch (Exception $e) {
            Session::flash('error_message', 'Something went wrong.');
        }
        return redirect()->back();
    }
    public function view_point_settings_based_on_preset($type)
    {
        $admin_data = session('admin_data');
        $all_presets = PresetModel::get();
        if (!$all_presets->isEmpty()) {
            if (!isset($_GET['preset'])) {
                $preset = $all_presets[0];
                $selected_preset = $preset->presetid;
            } else {
                $selected_preset = $_GET['preset'];
                $preset = PresetModel::find($selected_preset);
            }
            if ($type == 'schedule-on-any-game') {
                $title = 'Presets - Schedule on Any Game';
                $page_data = $preset->schedule[0] ?? array();
                $template = 'base_point';
            } elseif ($type == 'age-of-players') {
                $title = 'Presets - Age of Players';
                $page_data = $preset->age_of_players ?? array();
                $template = 'age_of_players';
            } elseif ($type == 'location') {
                $title = 'Presets - Location';
                $page_data = $preset->locations ?? array();
                $template = 'preset_location';
            } elseif ($type == 'pay') {
                $title = 'Presets - Pay';
                $page_data = $preset->pay ?? array();
                $template = 'pay_preset';
            } elseif ($type == 'time') {
                $title = 'Presets - Time';
                $page_data = $preset->time ?? array();
                $template = 'time_preset';
            } elseif ($type == 'umpire-duration') {
                $title = 'Presets - Umpire Duration';
                $page_data = $preset->umpire_duration ?? array();
                $template = 'umpire_duration';
            } elseif ($type == 'total-game') {
                $title = 'Presets - Total Game';
                $page_data = $preset->total_game ?? array();
                $template = 'total_game';
            } elseif ($type == 'umpire-position') {
                $title = 'Presets - Umpire Position';
                $page_data = $preset->umpire_position ?? array();
                $template = 'umpire_position';
            } elseif ($type == 'day-of-week') {
                $title = 'Presets - Day Of Week';
                $page_data = $preset->day_of_week ?? array();
                $template = 'day_of_week';
            } else {
                Session::flash('error_message', 'Not Authorized.');
                return redirect('admin/add_preset');
            }
        } else {
            Session::flash('error_message', 'Please create a preset first.');
            return redirect('admin/add_preset');
        }
        $data = compact('title', 'all_presets', 'admin_data', 'page_data', 'preset', 'selected_preset');
        return view('admin.' . $template)->with($data);
    }
    public function save_base_point(Request $request, $preset_id)
    {
        $validator = Validator::make($request->all(), [
            'addlesss' => 'required',
            'point' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $data = [
                'presetid' => $preset_id,
                'addless' => $request->addlesss,
                'point' => $request->point
            ];
            PresetModel::find($preset_id)->schedule()->delete();
            SchedulePresetModel::create($data);
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            return response()->json(array('status' => 0));
        }
    }
    public function save_age_of_players(Request $request, $preset_id)
    {
        $validator = Validator::make($request->all(), [
            'from.*' => 'required',
            'to.*' => 'required',
            'addless .*' => 'required',
            'point .*' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            PresetModel::find($preset_id)->age_of_players()->delete();
            foreach ($request->point as $k => $point) :
                $data = [
                    'presetid' => $preset_id,
                    'from' => $request->from[$k],
                    'to' => $request->to[$k],
                    'addless' => $request->addless[$k],
                    'point' => $request->point[$k]
                ];
                Age_of_PlayersModel::create($data);
            endforeach;
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            return response()->json(array('status' => 0));
        }
    }
    public function save_location_preset(Request $request, $preset_id)
    {
        $validator = Validator::make($request->all(), [
            'locid.*' => 'required',
            'addless .*' => 'required',
            'point .*' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            PresetModel::find($preset_id)->locations()->delete();
            foreach ($request->point as $k => $point) :
                $data = [
                    'presetid' => $preset_id,
                    'locid' => $request->locid[$k],
                    'addless' => $request->addless[$k],
                    'point' => $request->point[$k]
                ];
                GroundPresetModel::create($data);
            endforeach;
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            return response()->json(array('status' => 0));
        }
    }
    public function save_pay(Request $request, $preset_id)
    {
        $validator = Validator::make($request->all(), [
            'from.*' => 'required',
            'to.*' => 'required',
            'addless .*' => 'required',
            'point .*' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            PresetModel::find($preset_id)->pay()->delete();
            foreach ($request->point as $k => $point) :
                $data = [
                    'presetid' => $preset_id,
                    'from' => $request->from[$k],
                    'to' => $request->to[$k],
                    'addless' => $request->addless[$k],
                    'point' => $request->point[$k]
                ];
                PayPresetModel::create($data);
            endforeach;
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            return response()->json(array('status' => 0));
        }
    }
    public function save_time(Request $request, $preset_id)
    {
        $validator = Validator::make($request->all(), [
            'from.*' => 'required',
            'to.*' => 'required',
            'addless .*' => 'required',
            'point .*' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            PresetModel::find($preset_id)->time()->delete();
            foreach ($request->point as $k => $point) :
                $data = [
                    'presetid' => $preset_id,
                    'from' => $request->from[$k],
                    'to' => $request->to[$k],
                    'addless' => $request->addless[$k],
                    'point' => $request->point[$k]
                ];
                TimePresetModel::create($data);
            endforeach;
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            dd($e);
            return response()->json(array('status' => 0));
        }
    }
    public function save_umpire_duration(Request $request, $preset_id)
    {
        $validator = Validator::make($request->all(), [
            'from.*' => 'required',
            'to.*' => 'required',
            'addless .*' => 'required',
            'point .*' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            PresetModel::find($preset_id)->umpire_duration()->delete();
            foreach ($request->point as $k => $point) :
                $data = [
                    'presetid' => $preset_id,
                    'from' => $request->from[$k],
                    'to' => $request->to[$k],
                    'addless' => $request->addless[$k],
                    'point' => $request->point[$k]
                ];
                UmpireDurationPresetModel::create($data);
            endforeach;
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            dd($e);
            return response()->json(array('status' => 0));
        }
    }
    public function save_total_game(Request $request, $preset_id)
    {
        $validator = Validator::make($request->all(), [
            'from.*' => 'required',
            'to.*' => 'required',
            'addless .*' => 'required',
            'point .*' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            PresetModel::find($preset_id)->total_game()->delete();
            foreach ($request->point as $k => $point) :
                $data = [
                    'presetid' => $preset_id,
                    'from' => $request->from[$k],
                    'to' => $request->to[$k],
                    'addless' => $request->addless[$k],
                    'point' => $request->point[$k]
                ];
                TotalGamePresetModel::create($data);
            endforeach;
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            dd($e);
            return response()->json(array('status' => 0));
        }
    }
    public function save_umpire_position(Request $request, $preset_id)
    {
        $validator = Validator::make($request->all(), [
            'position.*' => 'required',
            'addless .*' => 'required',
            'point .*' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            PresetModel::find($preset_id)->umpire_position()->delete();
            foreach ($request->point as $k => $point) :
                $data = [
                    'presetid' => $preset_id,
                    'position' => $request->position[$k],
                    'addless' => $request->addless[$k],
                    'point' => $request->point[$k]
                ];
                UmpirePositionModel::create($data);
            endforeach;
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            dd($e);
            return response()->json(array('status' => 0));
        }
    }
    public function save_day_of_week(Request $request, $preset_id)
    {
        $validator = Validator::make($request->all(), [
            'dayname.*' => 'required',
            'addless .*' => 'required',
            'point .*' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            PresetModel::find($preset_id)->day_of_week()->delete();
            foreach ($request->point as $k => $point) :
                $data = [
                    'presetid' => $preset_id,
                    'dayname' => $request->dayname[$k],
                    'addless' => $request->addless[$k],
                    'point' => $request->point[$k]
                ];
                DayofWeekModel::create($data);
            endforeach;
            return response()->json(array('status' => 1));
        } catch (Exception $e) {
            dd($e);
            return response()->json(array('status' => 0));
        }
    }
}
