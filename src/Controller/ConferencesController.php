<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\Locator\TableLocator;
use Cake\ORM\TableRegistry;
use DateTime;
use DateTimeZone;

class ConferencesController extends AppController
{
	public function index()
	{
		$one_day_before = date('Y-m-d',strtotime("-3 days"));
		if($this->appUser->manage_users == 1) {
			$conferences = $this->Conferences->find()
			->where(['schedule_date >' => $one_day_before])
			->contain(['TeamMembers'])
			->all();
		}else{
			$conferences = $this->Conferences->find()
			->where(['team_member_id = ' => $this->appUser->id])
			->where(['schedule_date >' => $one_day_before])
			->contain(['TeamMembers'])
			->all();
		}
		foreach($conferences as $conference){
			$conference->schedule_date = date('M d, Y', strtotime($conference->schedule_date));
		}
		$this->set(compact('conferences'));
	}

	public function add()
	{
		if ($this->request->getData()) {
			$conferences = $this->Conferences->find()->all();
			$codes = array();
			foreach($conferences as $con){
				array_push($con->hac);
				array_push($con->pac);
				array_push($con->lac);
			}
			$form_data = $this->request->getData();
			$schedule_date = $form_data['schedule_date'];
			// //Create date with input timezone
			// $date = new DateTime($schedule_date, new DateTimeZone($form_data['schedule_timezone']));
			// //convert timezone to EST to store db
			// $date->setTimezone(new DateTimeZone('EST'));
			$date = new DateTime($schedule_date);
			$schedule_date = $date->format('Y-m-d');
			$obj = $this->generate_code($codes);
			$hac = $obj['code'];
			$codes = $obj['codes'];
			$obj = $this->generate_code($codes);
			$pac = $obj['code'];
			$codes = $obj['codes'];
			$obj = $this->generate_code($codes);
			$lac = $obj['code'];
			$codes = $obj['codes'];
			$url = 'https://api.carrierx.com/conference/v1/meetingRooms';
			$data = array(
				'primaryDidGroupId' => intval(getenv('CARRIERX_PDIDGROUPID')),
				'callFlowId' => intval(getenv('CARRIERX_CALLFLOWID')),
				'description' => $form_data['name'],
				'keychain' => array(
					array(
						'accessCode' => $hac,
						'role' => 1
					),
					array(
						'accessCode' => $pac,
						'role' => 2
					),
					array(
						'accessCode' => $lac,
						'role' => 3
					)
				)
			);
			$result_json = $this->carrierxAPICall($url, $data);
			if($result_json != null && isset($result_json->meetingNumber)) {
				$insert_data = array(
					'team_member_id' => $this->appUser->id,
					'meeting_number' => $result_json->meetingNumber,
					'name' => $form_data['name'],
					'hac' => $hac,
					'pac' => $pac,
					'lac' => $lac,
					'schedule_date' => $schedule_date,
					'schedule_timezone' => 'UTC'
				);
				$conference = $this->Conferences->newEntity($insert_data);
				if ($this->Conferences->save($conference)) {
					return $this->redirect(['action' => 'index']);
				}else{
					return $this->redirect($this->referer());
				}
			}else{
				return $this->redirect($this->referer());
			}
		} else {
			$conference = $this->Conferences->newEntity();
		}
		$this->set('conference', $conference);
	}

	public function edit($id){
		$conference = $this->Conferences->find()
		->where(['Conferences.id' => $id])
		->first();
		$date = new DateTime($conference->schedule_date);
		$conference->schedule_date = $date->format('m/d/Y');
		if ($this->request->getData()) {
			$form_data = $this->request->getData();
			$schedule_date = $form_data['schedule_date'];
			//Create date with input timezone
			$date = new DateTime($schedule_date);
			$schedule_date = $date->format('Y-m-d');
			if($conference->name != $form_data['name']){
				$url = 'https://api.carrierx.com/conference/v1/meetingRooms/'.$form_data['meeting_number'];
				$data = array(
					'description' => $form_data['name']
				);
				$result_json = $this->carrierxAPICall($url, $data, 'PUT');
				if($result_json != null && isset($result_json->meetingNumber)){
					$update_array = array(
						'name' => $form_data['name'],
						'schedule_date' => $schedule_date
					);
					$conference = $this->Conferences->patchEntity($conference, $update_array);
					if ($this->Conferences->save($conference)) {
						return $this->redirect(['action' => 'index']);
					}else{
						return $this->redirect($this->referer());
					}
				}else{
					return $this->redirect($this->referer());
				}
			}else{
				$update_array = array(
					'schedule_date' => $schedule_date
				);
				$conference = $this->Conferences->patchEntity($conference, $update_array);
				if ($this->Conferences->save($conference)) {
					return $this->redirect(['action' => 'index']);
				}else{
					return $this->redirect($this->referer());
				}
			}	
		}
		$this->set(compact('conference'));
	}

	/**
     * Delete method
     *
     * @param string|null $id Conference id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
		$conference = $this->Conferences->get($id);
		//remove meeting room on CarrierX
		$url = 'https://api.carrierx.com/conference/v1/meetingRooms/'.$conference->meeting_number;
		$this->carrierxAPICall($url, array(), 'DELETE');
        if ($this->Conferences->delete($conference)) {
            $this->Flash->success(__('The meeting room has been deleted.'));
        } else {
            $this->Flash->error(__('The meeting room could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

	public function carrierxAPICall($url, $data = array(), $method = '')
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_USERPWD, getenv('CARRIERX_API_LOGIN') . ":" . getenv('CARRIERX_API_PASSWORD'));
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		if($method !== ''){
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
			if($method == 'GET'){
				curl_setopt($ch, CURLOPT_POST, 1);
			}
		}else{
			curl_setopt($ch, CURLOPT_POST, 1);
		}
		if(count($data) > 0){
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$return = curl_exec($ch);
		curl_close($ch);
		return json_decode($return);
	}

	public function generate_code($codes){
		$code = mt_rand(100000, 999999);
		if(in_array($code, $codes)){
			return $this->generate_code($codes);
		}else{
			array_push($codes, $code);
			return array('code' => $code, 'codes' => $codes);
		}
	}
}