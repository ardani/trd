<?php
use App\Models\Setting;
use Carbon\Carbon;

/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/23/17
 * Time: 10:05 PM
 */

function form_value($var, $name) {
    return isset($var) ? $var : old($name);
}

function safe_array($array, $key) {
    return $array ? $array[ $key ] : NULL;
}

function alerts($type, $text) {
    $html = '
        <div class="alert alert-' . $type . ' alert-no-border alert-close alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            ' . $text . '
        </div>';

    return $html;
}

function auto_number_sales($reserve = 0) {
    $nota = DB::table('nota')->where('ip', getIP())
        ->where('type', 1)
        ->first();

    if ($nota) {
        return $nota->no;
    }
    $len   = 5;
    $month = date('n');
    $year  = date('Y');
    $last  = DB::table('sale_orders')
        ->whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->orderBy('no', 'DESC')
        ->first(['no']);

    if ($last) {
        $lasts = explode('/', $last->no);
        $num   = (int) $lasts[0];
        $num   = $reserve ? $reserve : $num + 1;
    }
    else {
        $num = $reserve ? $reserve : 1;
    }

    $num_format = str_repeat('0', $len - strlen($num)) . $num;
    $new        = sprintf('%s/PO/MV/%s/%s', $num_format, romawi($month), $year);
    $exist      = DB::table('nota')->where('no', $new)
        ->where('type', 1)
        ->where('ip', '!=', getIP())
        ->count();

    if ($exist) {
        $num++;

        return auto_number_sales($num);
    }
    else {
        $exist = DB::table('nota')->where('no', $new)
            ->where('type', 1)
            ->where('ip', getIP())
            ->count();
        if (!$exist) {
            DB::table('nota')->insert(['no' => $new, 'ip' => getIP(), 'type' => 1]);
        }
    }

    return $new;
}

function auto_number_orders($reserve = 0) {
    $nota = DB::table('nota')->where('ip', getIP())
        ->where('type', 2)
        ->first();

    if ($nota) {
        return $nota->no;
    }
    $len   = 5;
    $month = date('n');
    $year  = date('Y');
    $last  = DB::table('orders')
        ->whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->orderBy('no', 'DESC')
        ->first(['no']);

    if ($last) {
        $lasts = explode('/', $last->no);
        $num   = (int) $lasts[0];
        $num   = $reserve ? $reserve : $num + 1;
    }
    else {
        $num = $reserve ? $reserve : 1;
    }

    $num_format = str_repeat('0', $len - strlen($num)) . $num;
    $new        = sprintf('%s/OR/MV/%s/%s', $num_format, romawi($month), $year);
    $exist      = DB::table('nota')->where('no', $new)
        ->where('ip', '!=', getIP())
        ->where('type', 2)
        ->count();

    if ($exist) {
        $num++;

        return auto_number_orders($num);
    }
    else {
        $exist = DB::table('nota')->where('no', $new)
            ->where('ip', getIP())
            ->where('type', 2)
            ->count();

        if (!$exist) {
            DB::table('nota')->insert(['no' => $new, 'ip' => getIP(), 'type' => 2]);
        }
    }

    return $new;
}

function auto_number_return_orders($reserve = 0) {
    $nota = DB::table('nota')->where('ip', getIP())
        ->where('type', 4)
        ->first();

    if ($nota) {
        return $nota->no;
    }
    $len   = 5;
    $month = date('n');
    $year  = date('Y');
    $last  = DB::table('return_orders')
        ->whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->orderBy('no', 'DESC')
        ->first(['no']);

    if ($last) {
        $lasts = explode('/', $last->no);
        $num   = (int) $lasts[0];
        $num   = $reserve ? $reserve : $num + 1;
    }
    else {
        $num = $reserve ? $reserve : 1;
    }

    $num_format = str_repeat('0', $len - strlen($num)) . $num;
    $new        = sprintf('%s/RO/MV/%s/%s', $num_format, romawi($month), $year);
    $exist      = DB::table('nota')->where('no', $new)
        ->where('ip', '!=', getIP())
        ->where('type', 4)
        ->count();

    if ($exist) {
        $num++;

        return auto_number_return_orders($num);
    }
    else {
        $exist = DB::table('nota')->where('no', $new)
            ->where('ip', getIP())
            ->where('type', 4)
            ->count();

        if (!$exist) {
            DB::table('nota')->insert(['no' => $new, 'ip' => getIP(), 'type' => 4]);
        }
    }

    return $new;
}

function auto_number_return_sales($reserve = 0) {
    $nota = DB::table('nota')->where('ip', getIP())
        ->where('type', 3)
        ->first();

    if ($nota) {
        return $nota->no;
    }
    $len   = 5;
    $month = date('n');
    $year  = date('Y');
    $last  = DB::table('return_sale_orders')
        ->whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->orderBy('no', 'DESC')
        ->first(['no']);

    if ($last) {
        $lasts = explode('/', $last->no);
        $num   = (int) $lasts[0];
        $num   = $reserve ? $reserve : $num + 1;
    }
    else {
        $num = $reserve ? $reserve : 1;
    }

    $num_format = str_repeat('0', $len - strlen($num)) . $num;
    $new        = sprintf('%s/RS/MV/%s/%s', $num_format, romawi($month), $year);
    $exist      = DB::table('nota')->where('no', $new)
        ->where('ip', '!=', getIP())
        ->where('type', 3)
        ->count();

    if ($exist) {
        $num++;

        return auto_number_return_sales($num);
    }
    else {
        $exist = DB::table('nota')->where('no', $new)
            ->where('ip', getIP())
            ->where('type', 3)
            ->count();

        if (!$exist) {
            DB::table('nota')->insert(['no' => $new, 'ip' => getIP(), 'type' => 3]);
        }
    }

    return $new;
}

function auto_number_productions($reserve = 0) {
    $len   = 5;
    $month = date('n');
    $year  = date('Y');
    $last  = DB::table('productions')
        ->whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->orderBy('no', 'DESC')
        ->first(['no']);

    if ($last) {
        $lasts = explode('/', $last->no);
        $num   = (int) $lasts[0];
        $num   = $reserve ?: $num + 1;
    }
    else {
        $num = $reserve ?: 1;
    }

    $num_format = str_repeat('0', $len - strlen($num)) . $num;
    $new        = sprintf('%s/PR/MV/%s/%s', $num_format, romawi($month), $year);

    return $new;
}

function auto_number_cash_in($reserve = 0) {
    $len   = 5;
    $month = date('n');
    $year  = date('Y');
    $last  = DB::table('cashes')
        ->where('type', 1)
        ->orderBy('no', 'DESC')
        ->first(['no']);

    if ($last) {
        $lasts = explode('/', $last->no);
        $num   = (int) $lasts[0];
        $num   = $reserve ?: $num + 1;
    }
    else {
        $num = $reserve ?: 1;
    }

    $num_format = str_repeat('0', $len - strlen($num)) . $num;
    $new        = sprintf('%s/CI/%s/%s', $num_format, romawi($month), $year);

    return $new;
}

function auto_number_cash_out($reserve = 0) {
    $len   = 5;
    $month = date('n');
    $year  = date('Y');
    $last  = DB::table('cashes')
        ->where('type', 0)
        ->orderBy('no', 'DESC')
        ->first(['no']);

    if ($last) {
        $lasts = explode('/', $last->no);
        $num   = (int) $lasts[0];
        $num   = $reserve ?: $num + 1;
    }
    else {
        $num = $reserve ?: 1;
    }

    $num_format = str_repeat('0', $len - strlen($num)) . $num;
    $new        = sprintf('%s/C0/%s/%s', $num_format, romawi($month), $year);

    return $new;
}

function auto_number_request_product($reserve = 0) {
    $nota = DB::table('nota')->where('ip', getIP())
        ->where('type', 5)
        ->first();

    if ($nota) {
        return $nota->no;
    }
    $len   = 5;
    $month = date('n');
    $year  = date('Y');
    $last  = DB::table('request_products')
        ->whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->orderBy('no', 'DESC')
        ->first(['no']);

    if ($last) {
        $lasts = explode('/', $last->no);
        $num   = (int) $lasts[0];
        $num   = $reserve ? $reserve : $num + 1;
    }
    else {
        $num = $reserve ? $reserve : 1;
    }

    $num_format = str_repeat('0', $len - strlen($num)) . $num;
    $new        = sprintf('%s/RP/MV/%s/%s', $num_format, romawi($month), $year);
    $exist      = DB::table('nota')->where('no', $new)
        ->where('type', 5)
        ->where('ip', '!=', getIP())
        ->count();

    if ($exist) {
        $num++;

        return auto_number_request_product($num);
    }
    else {
        $exist = DB::table('nota')->where('no', $new)
            ->where('type', 5)
            ->where('ip', getIP())
            ->count();
        if (!$exist) {
            DB::table('nota')->insert(['no' => $new, 'ip' => getIP(), 'type' => 5]);
        }
    }

    return $new;
}

function auto_number_product($name) {
    $len   = 5;
    $start = substr($name, 0, 1);
    $last  = DB::table('products')
        ->whereRaw('left(code,1) = "'.$start.'"')
        ->orderBy('code', 'DESC')
        ->first(['code']);
    $num   = ($last) ? (int) str_replace($start, '', $last->code) + 1 : 1;
    $num_format = str_repeat('0', $len - strlen($num)) . $num;
    $new        = sprintf('%s', $start . $num_format);
    return $new;
}

function romawi($i) {
    $romawi = [
        1  => 'I',
        2  => 'II',
        3  => 'III',
        4  => 'IV',
        5  => 'V',
        6  => 'VI',
        7  => 'VII',
        8  => 'VIII',
        9  => 'IX',
        10 => 'X',
        11 => 'XI',
        12 => 'XII',
    ];

    return $romawi[ $i ];
}

function getIP() {
    if (getenv("HTTP_CLIENT_IP")) {
        $ip = getenv("HTTP_CLIENT_IP");
    }
    elseif (getenv("HTTP_X_FORWARDED_FOR")) {
        $ip = getenv("HTTP_X_FORWARDED_FOR");
        if (strstr($ip, ',')) {
            $tmp = explode(',', $ip);
            $ip  = trim($tmp[0]);
        }
    }
    else {
        $ip = getenv("REMOTE_ADDR");
    }

    return $ip;
}

function clear_nota($no) {
    return \DB::table('nota')->where('ip', getIP())->where('no', $no)->delete();
}

function setting($key) {
    $setting = Setting::where('key',$key)->first(['value']);
    return $setting->value;
}

function date_until($date) {
    $dates = explode(' - ', $date);
    $dates[0] = Carbon::createFromFormat('d/m/Y', $dates[0])->format('Y-m-d').' 00:00';
    $dates[1] = Carbon::createFromFormat('d/m/Y', $dates[1])->format('Y-m-d').' 23:59';
    return $dates;
}