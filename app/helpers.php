<?php
function seat_dropdown()
{
  $ret = array(
      '' => 'Select Options',
      1 => '1',
      2 => '2',
      3 => '3',
      4 => '4',
      5 => '5',
      6 => '6',
      7 => '7',
      8 => '8'
  );
  return $ret;
}
function duration()
{
  $ret=array(
    ""=>'----Select Duration----',
    "1"=>'Day',
    "2"=>'Week',
    "3"=>'Month',
    "4"=>'Year'
  );
  return $ret;
}


function coupon_type()
{
  $ret=array(
    "1"=>'fix_ammount',
    "2"=>'percentage'
  );
  return $ret;
}

// function hours()
// {
//   $ret=array(
//       '' => 'Hrs',
//       01 => '01',
//       02 => '02',
//       03 => '03',
//       04 => '04',
//       05 => '05',
//       06 => '06',
//       07 => '07',
//       08 => '08',
//       09 => '09',
//       10 => '10',
//       11 => '11',
//       12 => '12'
//     );
//   return $ret;
// }

// function minutes()
// {
//   $ret=array(
//       '' => 'Mins',
//       01 => '01',02 => '02',
//       03 => '03',04 => '04',
//       05 => '05',06 => '06',
//       07 => '07',08 => '08',
//       09 => '09',10 => '10',
//       11 => '11',12 => '12',
//       13 => '13',14 => '14',
//       15 => '15',16 => '16',
//       17 => '17',18 => '18',
//       19 => '19',20 => '20',
//       21 => '21',22 => '22',
//       23 => '23',24 => '24',
//       25 => '25',26 => '26',
//       27 => '27',28 => '28',
//       29 => '29',30 => '30',
//       31 => '31',32 => '32',
//       33 => '33',34 => '34',
//       35 => '35',36 => '36',
//       37 => '37',38 => '38',
//       39 => '39',40 => '40',
//       41 => '41',42 => '42',
//       43 => '43',44 => '44',
//       45 => '45',46 => '46',
//       47 => '47',48 => '48',
//       49 => '49',50 => '50',
//       51 => '51',52 => '52',
//       53 => '53',54 => '54',
//       55 => '55',56 => '56',
//       57 => '57',58 => '58',
//       59 => '59',60 => '60'
//     );
//   return $ret;
// }

function day_part()
{
  $ret=array(
    'AM'=>'AM',
    'PM'=>'PM'
    );
  return $ret;
}
function stop_dropdown()
{
  $ret = array(
      0 => '-- Select Options --',
      15 => '15 min',
      30 => '30 min',
      45 => '45 min',
      60 => '60 min',
      75 => '75 min',
      90 => '90 min',
      105 =>'105 min',
      120 =>'120 min'
  );
  return $ret;
}
function pair_seat()
{
  $ret=array(
    '' => 'Select Options',
      1 => '1',
      2 => '2'
    );
  return $ret;
}

function assoc_to_radios($assoc, $name) {
  $ret = array();
  foreach ($assoc as $k => $v) {
    $ret[$v] = array(
        'name' => $name,
        'value' => $k,
        'id' => str_replace(array("[","]"),"",$name).'_'.$k
    );
  }
  return $ret;
}

function month_dropdown() {
  $ret = array(
      '' => '-- Month --',
      1 => 'Jan',
      2 => 'Feb',
      3 => 'Mar',
      4 => 'Apr',
      5 => 'May',
      6 => 'Jun',
      7 => 'Jul',
      8 => 'Aug',
      9 => 'Sep',
      10 => 'Oct',
      11 => 'Nov',
      12 => 'Dec'
  );
  return $ret;
}

function year_dropdown() {
  $ret = array('' => '-- YEAR --');
  for($i=date('Y');$i>2013;$i--) {
    $ret[$i] = $i;
  } 
  return $ret;
}

function get_month(){
  $expiry_month = array();

    for($i = 1; $i <= 12; $i++) {
        $expiry_month[sprintf('%02d', $i)] = sprintf('%02d', $i);
    }
    return $expiry_month;
}

function get_year(){
  $ret = array();
  $i = date("y");
  $j = $i+11;
  for ($i; $i <= $j; $i++) {
      $ret[sprintf('%02d', $i)] = sprintf('%02d', $i);
  }
  return $ret;
}

function upload_tmp_path($file) {
  return public_path() . "/tmp/$file";
}
function upload_tmp_url($file) {
  return asset("tmp/$file");
}
function upload_path($file, $model, $variation=false, $relative=null) {
  $use_aws = is_null($relative)? Config::get('aws.use',false) : $relative;
  $folder = "/uploads/". ( empty($variation) || $variation =='original' ? $model : "{$model}-{$variation}" ); 

  if (!$use_aws && !is_array($variation) && !file_exists(public_path().$folder)) {
      umask(0);
      @mkdir(public_path().$folder, 0777, true);
    }
  $target_path = ($use_aws? "" : public_path()). "$folder/$file";
  return $target_path;
}
function upload_url($file, $model, $variation=false)  {
  $use_aws = Config::get('aws.use',false);
  $folder = "/uploads/". ( empty($variation)  || $variation =='original' ? $model : "{$model}-{$variation}" );
  if(!empty($file)) {
    if($use_aws)
      return Config::get('aws.host')."$folder/$file";
    else
      return asset("$folder/$file");
  } 
  return false;
}
function upload_move($file,$model,$variation=false) {
  $use_aws = Config::get('aws.use',false);
  $source = upload_tmp_path($file);
  $target = upload_path($file,$model,$variation);

  if($use_aws) {    
    AWS::get('s3')->putObject(array(
            'Bucket'     => Config::get('aws.bucket'),
            'Key'        => $target,
            'SourceFile' => $source,
            'ACL'    => 'public-read'
        ));
  } else {
    copy($source, $target);
  }
}

function upload_delete($file,$model,$variations=false) {
  if(empty($file)) return false;;
  if(is_array($variations)) {
    foreach($variations as $variation){
        $local_path = upload_path($file,$model,$variation,false);
        @unlink($local_path);
    } 
  }
}

//   $use_aws = Config::get('aws.use',false);    
//   if($use_aws) {
//     $aws_path = upload_path($file,$model,$variations,true);
//     AWS::get('s3')->deleteObject(array(
//           'Bucket'     => Config::get('aws.bucket'),
//           'Key'        => $aws_path,          
//         ));     
//   }

// }

// function exec_artisan($task,$ret=false) {
//     $is = App::environment() != 'local';
//     $php = $is? "/opt/php53/bin/php" : 'php';
//     $cmd = "$php ".base_path()."/artisan $task --env=".App::environment();
//     shell_exec($cmd);
//     if($ret) die($cmd);
// }
