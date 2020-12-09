<?php
function billUpdatedCheck($oldSale,$newSale,$con)
{
	$oldBill = $oldSale['bill_no'];
	$newBill = $newSale['bill_no'];
	
	if($oldBill != $newBill)
	{
		if( fnmatch("G*",$newBill) || fnmatch("C*",$newBill) || fnmatch("B*",$newBill))
			return true;
	}
	else
	{
		return false;
	}
}