<?php

use Carbon\Carbon;

if(!function_exists('get_app_setting')){
    function get_app_setting($setting_type){
        $setting = App\Models\AppSetting::with(['siteLogo','siteFavicon'])->latest()->first();
        if($setting[$setting_type]){

            if($setting_type == 'logo' && $setting->siteLogo){
                return $setting->siteLogo->file;
            }
            if($setting_type == 'favicon' && $setting->siteFavicon){
                return $setting->siteFavicon->file;
            }

            return $setting[$setting_type];
        }
        
    }
}

if (!function_exists('status')) {

    function status($id){
        $weight = App\Models\Status::where(['id' => $id])->first();
        if($weight){
            return $weight->status_badge;
        }
        return null;
    }
}

if (!function_exists('getFile')) {
    function getFile($file, $id) {
        if ($file > 0){
            $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

            if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                return "<a style='line-height: 40px; width: 46px; display: block; padding: 2px;display: flex; justify-content: center; align-items: center; height: 100%;' class='glightbox' data-gallery='".$id."' href='".asset($file)."'>
                            <img class='img-fluid' src='".asset($file)."'/>
                        </a>";
            } elseif ($fileExtension == 'pdf') {
                return "<a class='glightbox' data-gallery='".$id."' href='".asset($file)."'>
                            <img class='img-fluid' src='".asset('icons/pdf.png')."'/>
                        </a>";
            }
        }
        return 'N/A';
    }
}

if (!function_exists('getAdmin')) {
    function getAdmin($get_detail) {
        $admin = \Auth::guard('admin')->user();
        if (!$admin) {
            return 'No admin is currently logged in';
        }
        if (!in_array($get_detail, ['password', 'role', 'role_id'])) {
            if (isset($admin[$get_detail])) {
                return $admin[$get_detail];
            }
        }
        if ($get_detail == 'role') {
            $admin = $admin->load('role'); 
            if ($admin->role) {
                return $admin->role->display_name;
            }
            return 'Role not found';
        }

        if ($get_detail == 'role_id') {
            return $admin->role_id ?? 'Role ID not found';
        }

    }
}

if (!function_exists('numberToWords')) {
    function numberToWords($number) {
        $no = floor($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = [];
        $words = ['0' => '', '1' => 'One', '2' => 'Two',
                       '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
                       '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
                       '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
                       '13' => 'Thirteen', '14' => 'Fourteen',
                       '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
                       '18' => 'Eighteen', '19' => 'Nineteen', '20' => 'Twenty',
                       '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
                       '60' => 'Sixty', '70' => 'Seventy',
                       '80' => 'Eighty', '90' => 'Ninety'];
        $digits = ['', 'Hundred', 'Thousand', 'Lakh', 'Crore'];
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number] .
                    ' ' . $digits[$counter] . $plural . ' ' . $hundred
                    :
                    $words[floor($number / 10) * 10]
                    . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
            } else $str[] = null;
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($point) ? ' and ' . $words[$point / 10] . ' ' .
                  $words[$point = $point % 10] . ' Paise' : '';
        return $result . 'Rupees' . $points;
    }
}

if (!function_exists('databaseDate')) {
    function databaseDate($date) {
        $formats = [
            'd F, Y',
            'Y-m-d',
            'm/d/Y',
            'd/m/Y',
            'F j, Y',
            'j F, Y',
            'd M, Y',
            'm-d-Y',
            'Y/m/d',
            'M d, Y',
            'd.m.Y',
            'Y.m.d',
        ];
        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $date)->format('Y-m-d');
            } catch (\Exception $e) {
                continue;
            }
        }
        return Carbon::now()->format('Y-m-d');
    }
}

if (!function_exists('age')) {
    function age($date) {
        $date = Carbon::parse($date)->startOfDay();
        $now = Carbon::now()->startOfDay();

        $days = $date->diffInDays($now);

        // Always add 1 day extra
        $days += 1;

        return $days;
    }
}

if (!function_exists('actionDropdown')) {

    function actionDropdown(array $actions, string $btnClass = 'btn btn-soft-secondary btn-sm dropdown'): string
    {
        if (empty($actions)) {
            return '';
        }

        $html = '<div class="dropdown d-inline-block">
            <button class="'.$btnClass.'" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="ri-more-fill align-middle"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">';

        foreach ($actions as $action) {

            $label = $action['label'] ?? '';
            $icon = $action['icon'] ?? '';
            $class = $action['class'] ?? 'dropdown-item';
            $url = $action['url'] ?? 'javascript:void(0);';
            $onclick = isset($action['onclick']) ? ' onclick="'.$action['onclick'].'"' : '';

            $dataAttrs = '';
            if (!empty($action['data']) && is_array($action['data'])) {
                foreach ($action['data'] as $key => $value) {
                    $dataAttrs .= $key.'="'.e($value).'"';
                }
            }

            if (!empty($action['button']) && $action['button'] === true) {

                $html .= '<li>
                    <button type="button" class="'.$class.'"'.$onclick.$dataAttrs.'>
                        <i class="'.$icon.' align-bottom me-2 text-muted"></i> '.$label.'
                    </button>
                </li>';

            } else {

                $html .= '<li>
                    <a href="'.$url.'" class="'.$class.'"'.$onclick.$dataAttrs.'>
                        <i class="'.$icon.' align-bottom me-2 text-muted"></i> '.$label.'
                    </a>
                </li>';
            }
        }

        $html .= '</ul></div>';

        return $html;
    }
}
