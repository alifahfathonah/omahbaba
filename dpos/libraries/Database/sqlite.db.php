<?php 
/* APLIKASI PENJUALAN DPOS PRO
 *
 * Framework DPOS BISNIS berbasis PHP
 *
 * Developed by djavasoft.com
 * Copyright (c) 2018, Djavasoft Smart Technology
 *
 * @author	Mohamad Anton Arizal
 * @copyright	Copyright (c) 2018 Djavasoft. (https://djavasoft.com/)
 *
 *
*/

ob_start();

function sumData($table,$column,$arg=""){
	global $db;
		
		$rs= $db->query("select sum(".$column.") as sum_".$column." from ".$table." ".$arg."");
		$rs=$rs->fetchArray();
		$total = $rs["sum_".$column.""];
		return $total;
}

function checkData($table,$arg=""){
	global $db;
		
		$rs= $db->query("select count(*) as count from ".$table." ".$arg."");
		$rs=$rs->fetchArray();
		$total = $rs["count"];
		return $total;
}
function countData($table,$arg=""){
	global $db;
		
		$rs= $db->query("select count(*) as count from ".$table." ".$arg."");
		$rs=$rs->fetchArray();
		$total = $rs["count"];
		return $total;
}

function doList($table,$column="",$arg=""){
	global $db;
		
		$data=$db->query("SELECT * FROM ".$table." ".$arg."");
		$items= array();	
		while($row=$data->fetchArray()){
			for($i=0;$i<count($column);$i++){
			$col[$i]=$row[$column[$i]];
			}			
			$items[] = $col;
		}

		
		return $items;
}
function doTableArray($table,$column="",$arg=""){
	global $db;
		
		$data=$db->query("SELECT * FROM ".$table." ".$arg."");
		$items= array();	
		while($row=$data->fetchArray()){
			for($i=0;$i<count($column);$i++){
			$col[$i]=$row[$column[$i]];
			}			
			$items[] = $col;
		}

		
		return $items;
}

function doTable($table,$column,$arg=""){
	global $db;

		$data=$db->query("SELECT * FROM ".$table." ".$arg."");
		$items= array();	
		while($row=$data->fetchArray()){
			for($i=0;$i<count($column);$i++){
			$col[$i]=$row[$column[$i]];
			}			
			$items[] = $col;
		}

		$result["data"] = $items;
		echo json_encode($result);
		$db->close();
}
function doTableJSON($table,$column,$arg=""){
	global $db;
		$rs= $db->query("select count(*) as count from ".$table." ".$arg."");
		$rs=$rs->fetchArray();
		$total = $rs["count"];
		$result["sEcho"] = 1;
		$result["iTotalRecords"] = $total;
		$result["iTotalDisplayRecords"] = $total;
		$data=$db->query("SELECT * FROM ".$table." ".$arg."");
		$items= array();	
		while($row=$data->fetchArray()){
			for($i=0;$i<count($column);$i++){
				if($column[$i]=='harga_beli' ||
				$column[$i]=='harga_jual' ||
				$column[$i]=='total' ||
				$column[$i]=='grand_total' ||
				$column[$i]=='pemasukan' ||
				$column[$i]=='pengeluaran' ||
				$column[$i]=='return_pembelian' ||
				$column[$i]=='return_penjualan' ||
				$column[$i]=='return_total' ||
				$column[$i]=='return_dibayar' ||
				$column[$i]=='return_sisa' ||
				$column[$i]=='hutang' ||
				$column[$i]=='hutang_sisa' ||
				$column[$i]=='hutang_dibayar' ||
				$column[$i]=='pajak' ||
				$column[$i]=='hpp' ||
				$column[$i]=='total_hpp' ||
				$column[$i]=='laba_rugi' ||
				$column[$i]=='ongkir' ||
				$column[$i]=='harga'
				)
				{
					$col[$i]=currency($row[$column[$i]]);
					
				}elseif($column[$i]=='user_id'){
					if($row['user_id']!=0 || $row['user_id']!=NULL || $row['user_id']!=''  ){
					if( checkData('user',"WHERE id=".$row['user_id']."")>=1 ){
					$col[$i]=userName($row['user_id']);
					}else{
						$col[$i]='';
					}
					
					}else{
						$col[$i]='';
					}					
				}elseif($column[$i]=='pelanggan_id'){
					if($row['pelanggan_id']!=0 || $row['pelanggan_id']!=NULL || $row['pelanggan_id']!=''  ){
					if( checkData('pelanggan',"WHERE id=".$row['pelanggan_id']."")>=1 ){
					$col[$i]=getPelanggan('nama_pelanggan',"WHERE id=".$row['pelanggan_id']);
					}else{
						$col[$i]='';
					}
					
					}else{
						$col[$i]='';
					}					
				}elseif($column[$i]=='supplier_id'){
				if($row['supplier_id']!=0 || $row['supplier_id']!=NULL || $row['supplier_id']!=''  ){
					if( checkData('supplier',"WHERE id=".$row['supplier_id']."")>=1 ){
						$col[$i]=getSupplier('nama_supplier',"WHERE id=".$row['supplier_id']);
					}else{
						$col[$i]='';
					}
					}else{
						$col[$i]='';
					}
				}elseif($column[$i]=='date' ){
					$date = new DateTime($row['date']);
					$date = $date->format('d-m-Y');
					$col[$i]=$date;
				}elseif($column[$i]=='tempo'){
					$date = new DateTime($row['tempo']);
					$date = $date->format('d-m-Y');
					$col[$i]=$date;
				}else{
					$col[$i]=$row[$column[$i]];
				}
				
			}			
			$items[] = $col;
		}

		$result["data"] = $items;
		echo json_encode($result);
		$db->close();
}
function doDelete($table,$arg=""){
	global $db;
		
		
		$db->exec("DELETE FROM ".$table." ".$arg."");

}

function doInsert($table,$column="",$values=""){
	global $db;
		
		$db->exec("INSERT INTO  ".$table." 
		
		(".$column.") 
		
		VALUES 
		
		(".$values.")
		
		");

}

function doUpdate($table,$param="",$arg=""){
	global $db;
		
		$db->exec("UPDATE  ".$table." SET

		".$param."
		
		".$arg."
		
		");

}

function lastInsert($table,$column,$arg=""){
	global $db;
		
		$rs=$db->query("select $column from ".$table." ".$arg." ORDER BY $column DESC LIMIT 1");
		$rs=$rs->fetchArray();
		return $rs['id'];
}

function getFakturID($mode,$type){	
$fktID=lastInsert("faktur","id","");
$fktID=$fktID+1;
$fktID=$type.'.'.date("ymdHis").'.'.sprintf("%04d", $fktID);
return $fktID;
}


?>

