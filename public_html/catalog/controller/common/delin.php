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

		$params['derivalPoint'] = "5004100029900000000000000";
		$params['arrivalPoint'] = $this->request->post['arrivalPoint'];
		$params['sizedWeight'] = $this->request->post['sizedWeight'];
		$params['sizedVolume'] = $this->request->post['sizedVolume'];
		$params['oversizedWeight'] = $this->request->post['oversizedWeight'];
		$params['oversizedVolume'] = $this->request->post['oversizedVolume'];

		// $params['delivery']["deliveryType"]["type"] = "auto";
		// $params['delivery']["arrival"]["variant"] = "terminal";
		// $params['delivery']["arrival"]["terminalID"] = "1";
		// $params['delivery']["arrival"]["city"] = "7800000000000000000000000";
		// $params['delivery']["derival"]["produceDate"] = "2021-06-08";
		// $params['delivery']["derival"]["variant"] = "terminal";
		// $params['delivery']["derival"]["terminalID"] = "1";
		// $params['members']["requester"]["role"] = "receiver";
		// $params['cargo']["quantity"] = "1";
		// $params['cargo']["length"] = "1";
		// $params['cargo']["width"] = "1";
		// $params['cargo']["weight"] = "12";
		// $params['cargo']["weight"] = "1";
		// $params['cargo']["totalVolume"] = "1";
		// $params['cargo']["totalWeight"] = "12";
		// $params['cargo']["oversizedVolume"] = "0";
		// $params['cargo']["hazardClass"] = "0";
		
		// print_r($params);
		$this->dlc->request('public/calculator', $params);

		$json['result'] = $this->dlc->result;
		// $json['result'] = $this->dlc->calculator($params);

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

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "delin WHERE name LIKE '" . $city . "%'");
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

        $this->db->query("DELETE FROM " . DB_PREFIX . "delin ");

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

	public function download(){
        $this->dlc->request('public/places', []);

        $result = $this->dlc->result;
// var_dump($result);exit;
        file_put_contents(DIR_CACHE . "dlc_cities.csv", file_get_contents($result['url']));
	}
}