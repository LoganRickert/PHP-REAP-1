<?php

echo '<form action="' . $_SERVER['PHP_SELF'] . '" method="post">
<input type="text" name="taxyr" value="2014">
<input type="text" name="parid" value="R72617604 0010">
<input type="submit" name="submit" value="submit">
</form>';

if(isset($_POST['submit'])) {
	$location = 'http://mctreas.org/master.cfm?parid='. $_POST['parid'] .'&taxyr='. $_POST['taxyr'] .'&own1=';
	$location = str_replace(' ', '%20', $location);
	
	$response = file_get_contents($location);
	
	echo "The information is being generated. It should only take a few seconds. Please be patient.";
	
	echo '<p>You can see the page here: <a href="'.$location.'" target="_blank">'.$location.'</a>';
	
    $htmlCode = explode("<tr", $response);
	
	$daytonCheck = false;
	if (strpos($htmlCode[10], "R&nbsp") !== false) {
		echo "<p>This parcel is in dayton.";
		$daytonCheck = true;
	}
	
	$lienCheck = 0;
	if (strpos($htmlCode[18], "Yes") !== false) {
		echo "<p>This parcel has a Tax Lien and could be eligible.";
		$lienCheck = 1;
	}
	if (strpos($htmlCode[18], "Sold") !== false) {
		echo "<p>This parcel has sold for the tax lien and is not eligible.";
		$lienCheck = 0;
	}
	if (strpos($htmlCode[18], "No") !== false) {
		echo "<p>This parcel has no Tax Lien and is not eligible for for closure.";
		$lienCheck = 1;
	}

	$location = 'http://mctreas.org/taxes.cfm?parid='. $_POST['parid'] .'&taxyr='. $_POST['taxyr'] .'&own1=';
	$location = str_replace(' ', '%20', $location);

	$responseTaxes = file_get_contents($location);
	
	echo '<p>You can see the page for taxes here: <a href="'.$location.'" target="_blank">'.$location.'</a>';
	
	$htmlCodeTaxes = explode('<th colspan=6 style="text-align : center;"><B>Prior Year Charges/Delinquent Taxes</b></th>', $responseTaxes);
	$htmlCodeTaxesNarrow = explode('<th colspan=6 style="text-align : center;"><B>5/10% Payments</b></th>', $htmlCodeTaxes[1]);
	
	$htmlCodeTaxesPerYear = explode('<td width=10%>', $htmlCodeTaxesNarrow[0]);
	
	foreach($htmlCodeTaxesPerYear as $year) {
		echo '<p>'.$year;
	}
}  

?>
