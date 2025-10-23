<?php
// lib/db.php
$dbFile = __DIR__ . '/../data/db.json';
if (!file_exists($dbFile)) {
  $starter = [
    'products'=>[
      ['id'=>1,'name'=>'Espresso','price'=>15000,'stock'=>50],
      ['id'=>2,'name'=>'Cappuccino','price'=>22000,'stock'=>40],
      ['id'=>3,'name'=>'Latte','price'=>25000,'stock'=>30]
    ],
    'transactions'=>[],
    'users'=>[
      ['id'=>1,'username'=>'admin','password'=>password_hash('admin123',PASSWORD_DEFAULT),'role'=>'admin']
    ],
    'settings'=>['currency'=>'IDR']
  ];
  file_put_contents($dbFile, json_encode($starter, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
}
function db_load(){ global $dbFile; return json_decode(file_get_contents($dbFile), true); }
function db_save($d){ global $dbFile; file_put_contents($dbFile, json_encode($d, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)); }
function db_next_id($arr){ $max=0; foreach($arr as $a) if(isset($a['id']) && $a['id']>$max) $max=$a['id']; return $max+1; }
?>
