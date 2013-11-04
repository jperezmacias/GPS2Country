<?php


//$query="select foods.uuid, locations.latitude, locations.longitude from foods 
//inner join locations on foods.location_id=locations.uuid order by foods.uuid ASC";

$query="select uuid, latitude, longitude from testingtesting_ratings where country is null order by key";

//  $db_handle = pg_connect("host=localhost port=5432 dbname=eatery user=postgres password=postgres");
	    $db_handle = pg_connect("host=  port= dbname= user= password=");  

		 
		
	//  $query = "SELECT longitude, lattitude FROM locations WHERE uuid= "
		$result = pg_exec($db_handle, $query);   
	//  pg_query

//echo "Number of images to evaluate: " . pg_numrows($result);   
$rows=pg_numrows($result);
echo "Number of rows:".$rows."\r\n";
for ($i=0; $i<$rows; $i++) 

//for ($i=0; $i<2490; $i++) 
{

//sleep(40);	
sleep(1);
$myrow = pg_fetch_row($result, $i); 
	$uuid_food=$myrow[0];
	$latitude=$myrow[1];
	$longitude=$myrow[2];

  
 	 $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($latitude).','.trim($longitude).'&sensor=false';
	 echo $url;
  	$json = @file_get_contents($url);
  	unset($data);
	$data=json_decode($json);
  	
	$status = $data->status;
  
  	if($status=="OK"){
		//  $address = $data->results['types']['country']->formatted_address;
  
 		//$address1 = $data->results[1]->formatted_address;


		for ( $p = 0; $p < count ( $data->results[0]->address_components ); $p ++ ) {
    	switch ( $data->results[0]->address_components[$p]->types[0] ) {
        case "street_number":
            $street_number = $data->results[0]->address_components[$p]->long_name;
        break;
        case "route":
            $route = $data->results[0]->address_components[$p]->long_name;
        break;
        case "neighborhood":
            $neighborhood = $data->results[0]->address_components[$p]->long_name;
        break;
        case "locality":
            $city = $data->results[0]->address_components[$p]->long_name;
        break;
        case "administrative_area_level_1":
            $state = $data->results[0]->address_components[$p]->long_name;
        break;
        case "postal_code":
            $postal_code = $data->results[0]->address_components[$p]->long_name;
        break;
        case "country":
            $country = $data->results[0]->address_components[$p]->long_name;
        break;
    	}
 	}

//	$insert_query ="INSERT INTO countries_final (uuid,country,state,city) VALUES ('".$uuid_food."','".$country."','".$state."','".$city."');";
//$insert_query ="INSERT INTO testingtesting(country) VALUES('".$country."');";
$insert_query="UPDATE testingtesting_ratings SET (country, city)=('".str_replace("'", "", $country)."','".str_replace("'","",$city)."') WHERE uuid='$uuid_food';";
	echo $insert_query."\r\n"; 

	pg_exec($insert_query);

$country='';
$state='';
$city='';
unset($data);
	}	

	else{
  	echo "Invalid Coordinates !!\r\n";
  	}


}
 ?>
