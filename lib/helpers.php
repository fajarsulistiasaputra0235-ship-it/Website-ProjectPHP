<?php
function rupiah($n){ return 'Rp ' . number_format($n,0,',','.'); }
function find_product($products,$id){ foreach($products as $p) if($p['id']==$id) return $p; return null; }
?>
