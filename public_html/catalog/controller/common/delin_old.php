<?php
class ControllerCommonDelin extends Controller {
	public function cities() {
		$json = array();
		
		$city = $this->request->post['city'];

		$cities = $this->getCities($city);
		$json['cities'] = $cities;
		$json['arrivalPoint'] = $this->getCity($city, $cities);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function calc() {
		$json = array();

		$params = array();

		$params['derivalPoint'] = $this->request->post['derivalPoint'];
		$params['arrivalPoint'] = $this->request->post['arrivalPoint'];
		$params['sizedWeight'] = $this->request->post['sizedWeight'];
		$params['sizedVolume'] = $this->request->post['sizedVolume'];
		$params['oversizedWeight'] = $this->request->post['oversizedWeight'];
		$params['oversizedVolume'] = $this->request->post['oversizedVolume'];

		$params['arrivalDoor'] = 0;

		$json['result'] = $this->dlc->calculator($params);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	private function getCity($city, $cities){
		foreach ($cities as $key => $c) {
			if($c['name'] == $city){
				return $c['code'];
			}
		}
	}

	private function getCities($city){ 

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "delin WHERE name LIKE '" . $city . "%' LIMIT 0, 30");
		return $query->rows;
		$result = array();

		foreach ($query->rows as $key => $row) {
			if(!isset($result[$row['name']])){
				$result[$row['name']] = $row;
			}
		}
// print_r($result);
		return $result;
	}

	public function doo(){ 
		$cities = array();
		$i = 0;
		$file = fopen(DIR_CACHE . "dlc_cities.csv", 'r');
		while (!feof($file)) { 
			// if($i == 3){
			// 	break;
			// }

			$line = fgetcsv($file, 0, ";"); 
			// print_r( $line );
			$line = explode(",", $line[0]);

			// if(count($line) < 4) continue;
			foreach ($line as $key => $item) {
				$line[$key] = str_replace("\"", "", $item);
			}

		
			// $subj = strtolower($line[3]);
			// if(!preg_match("/^" . $city . "/usi", $subj)){
			// 	continue;
			// } else {
				$cities[] = $line;
				$i++;
			// }
			$this->db->query("INSERT INTO " . DB_PREFIX . "delin SET code = '" . $line[2] . "', name = '" . $line[3] . "', fullname = '" . $line[1] . "', resp = '" . $line[4] . "'");
			sleep(1);

		}
		// echo "string";
		// print_r($cities);
		// foreach ($cities as $city) {
		// 	$this->db->query("INSERT INTO " . DB_PREFIX . "delin SET code = '" . $city[2] . "', name = '" . $city[3] . "', fullname = '" . $city[1] . "', resp = '" . $city[4] . "'");
		// }
	}

	private function download(){
		$obj = $this->dlc->getCatalog("places");
		// fwrite(fopen(DIR_CACHE . "dlc_cities.csv", 'w'), string);
		file_put_contents(DIR_CACHE . "dlc_cities.csv", file_get_contents($obj->url));
	}
}