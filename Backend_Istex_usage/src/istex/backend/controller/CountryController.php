<?php
namespace istex\backend\controller;
use \istex\backend\controller\RequestController as RequestApi;

class CountryController 
{
	



	function Sort_by_country($received_array,$noaff){
		$noaff=json_decode($noaff,true);
		$tableau_country=[]; // Initialisation tableau
		$master_tab=[];
		foreach ($received_array as $key => $value) { // on parcourt le tableau que la requetes nous a renvoyé
			$tab=array();
			$tab[]=$value['country']; // on stocke les valeurs dans un tableau
			$tab[]=$value['id'];
			$tab[]=$value['lat'];
			$tab[]=$value['lon'];
					
				if ($value['country']=="NULL") {
					$test[]=$value['id'];
				}
				else{
					$master_tab[]=$tab;// on stocke le tableau dans un autre
					$verif[]=$value['id'];
					}

		}
		$master_tab = array_map("unserialize", array_unique(array_map("serialize", $master_tab)));
		//var_dump($master_tab);
		if (isset($test)) {
		$verif = array_map("unserialize", array_unique(array_map("serialize", $verif)));
		$test = array_map("unserialize", array_unique(array_map("serialize", $test)));
		//var_dump($test);
		//var_dump($verif);
		$result = array_diff($test, $verif);
		//var_dump($result);
		$noaff[0]['noaff']=$noaff[0]['noaff']+count($result);
		}



					
	  			
	  		
	  	

		/*foreach ($master_tab as $key2 => $value2) { // on parcourt le tableau precedemment créé
			$index=$value2[0]; // on definie l'index qui va permettre de determiner le nombre de publications par pays
			if (isset($tableau_country[$index])) { // array key exist permet de verifier si une clé existe dans un tableau
				$tableau_country[$index]++; // si elle existe on ajoute 1 a chaque valeur

			}
			else{
				$tableau_country[$index] = 1; // sinon on laisse a 1
				}
			}*/

		//$country=array();
		/*foreach ($master_tab as $key => $value) { // on parcourt ensuite le tableau 
			$array=array();
			$array[$value[0]] = $value[1]; // on range la valeur dans un autre tableau pour obtenir un tableau de tableau
			$country[] = $array;
		}*/
		
		/*foreach ($master_tab as $key => $value) { // on parcourt le tableau precedent
			//foreach ($value as $key2 => $value2) {
				$array= array();
						$Request = new RequestApi;
						$array['gps']=$Request->Request_lat_lon_of_country($value[0]);
				foreach ($master_tab as $key3 => $value3) { // on parcourt le tableau principale
					if ($value[0]==$value3[0]) { // si le nom de pays est le meme alors on les regroupes
						$array[]=$value3[1];
						$countrywithid[$value[0]]=$array;

						
					}
					
				//}
										
				}
			}*/

$countrywithid = array();
foreach($master_tab as $arg)
{
	$array= array();
	$Request = new RequestApi;
	$countrywithid[$arg[0]]["gps"]["lat"]=$arg[2];
	$countrywithid[$arg[0]]["gps"]["lon"]=$arg[3];
	$array[]=$arg[1];
    $countrywithid[$arg[0]][] = $array;
}
				
			arsort($countrywithid);	//tri du pays qui a le plus de documents au plus petit nombre
			foreach ($countrywithid as $key => $value) {
			$array= array();
			$array["gps"]=$value["gps"];
			$array["total"]=count($value)-1;
			$countrywithid[$key]=$array;
			}
			$response=array();
			$array=array();
			$array["noaff"]=$noaff[0]['noaff'];
			$array["total"]=$noaff[0]['total'];
			$response[]=$array;
			$response["documents"]=$countrywithid;
			return $response;
	
	}

/*function callback_functn($response, $url, $request_info, $user_data, $time) {

    $response_array=array();
    $responsedecoded = json_decode($response);
    $search=$user_data[1];
    $id=$user_data[0];
    $hash= md5($search);

				$m = new \Memcached(); // initialisation memcached
				$m->addServer('localhost', 11211); // ajout server memecached
				if (empty($responsedecoded)==false) {
					$cache=$m->set($hash, $responsedecoded, 100);// on set le tableau obtenu dans le cache
				}
				else{
					$cache=$m->set($hash, "NULL", 100);// on set le tableau obtenu dans le cache

				}

				@$country = json_decode(json_encode($responsedecoded[0]->address->country),true); 
				@$latitude = json_decode(json_encode($responsedecoded[0]->lat),true); //acquisition de la latitude
				@$longitude = json_decode(json_encode($responsedecoded[0]->lon),true); //acquisition de la longitude
				$array=array();
				$array['country']=$country;
				$array['id']=$id;
				$array['lat']=$latitude;
				$array['lon']=$longitude;
				
				$response_array=self::storeresult($response_array,$array);

    

    
}*/


/*function storeresult($response_array,$array){
	$response_array[]=$array;
	return $response_array;

}*/
	//focntion qui recupere l'affiliations et qui envoie celui ci vers la requete nominatim
	function get_name($received_array,$query){
		$hash = md5($query);
		$json=json_encode($received_array);
		$results= shell_exec('python Multiquery.py '.escapeshellarg($hash));
		$results= json_decode($results);
		foreach ($results as $key => $value) {
			$value=json_decode($value,true);
			$response_array[]=$value;
		}

		
			//$Request= new RequestApi;
				/*$curl_options = array(
			    CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 1,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "GET",
			);*/
			//$parallel_curl = new RollingCurlX(2000);
   
    		/*$hash= md5($name);
			$search = $name;
			$name=rawurlencode($name);
    		$search_url = "https://nominatim.otelo.univ-lorraine.fr/search.php/".$name."?format=json&addressdetails=1&limit=1&polygon_svg=0&accept-language=en";


				$m = new \Memcached(); // initialisation memcached
				$m->addServer('localhost', 11211); // ajout server memecached
				$cache=$m->get($hash);//on lit la memoire
				if ($cache) {
					$responsedecoded=$cache;
					if ($responsedecoded=="NULL") {
						$responsedecoded=array();
						$array=array();
						$array['country']="NULL";
						$array['id']=$value['id'];
						$array['lat']="NULL";
						$array['lon']="NULL";
								$response_array=self::storeresult($response_array,$array);
					}
					else{
						@$country = json_decode(json_encode($responsedecoded[0]->address->country),true); 
				@$latitude = json_decode(json_encode($responsedecoded[0]->lat),true); //acquisition de la latitude
				@$longitude = json_decode(json_encode($responsedecoded[0]->lon),true); //acquisition de la longitude
				$array=array();
				$array['country']=$country;
				$array['id']=$value['id'];
				$array['lat']=$latitude;
				$array['lon']=$longitude;
				
						$response_array=self::storeresult($response_array,$array);
					}
					
				}
			else{
    			//$parallel_curl->startRequest($search_url, 'on_request_done', $search,null,$value['id']);
    			$post_data=NULL;
    			$user_data=[$value['id'],$search];
    			$options = [CURLOPT_FOLLOWLOCATION => false];
    			$parallel_curl->addRequest($search_url, $post_data, 'callback_functn', $user_data, $curl_options);
				//$array=array();
				//$array['country']=$name;
				//$array['id']=$value['id'];
				//$array['lat']=$latitude;
				//$array['lon']=$longitude;
				
				//$response_array=self::storeresult($response_array,$array);
				

				}*/

			//$array=$Request->Request_name_of_country($value['country'],$value['id']);
			

		//}
		//var_dump(count($response_array));
		//var_dump($response_array);
		return $response_array;
		//$response_array = array_map("unserialize", array_unique(array_map("serialize", $response_array)));
		

	}
	

	
}


?>