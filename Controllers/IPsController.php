<?php

namespace Blog\Controllers;

require_once __DIR__ . '/../Models/IP.php';
require_once __DIR__ . '/../Models/Paginator.php';
require_once __DIR__ . '/../Helpers/XSSFilter.php';

use Blog\Helpers\XSSFilter as XSSFilter;
use Blog\Models\IP as IP;
use Blog\Models\Paginator as Paginator;
use Blog\Repositories\IPRepository as IPRepo;

class IPsController {

	private $repo;
	private $paginator;

	public function __construct() {
		$this->repo = new IPRepo;
		$this->paginator = new Paginator;
	}

	/**
	 *
	 * Method to get ips in the requested page
	 * @param $offset int the starting row
	 * @return $ips array of objects
	 *
	 */

	public function paginateIPs(int $offset = 0) {
		$offset = XSSFilter::globalXssClean($offset);
		$ips = $this->repo->getIPs($offset);
		$ips = XSSFilter::escape($ips);
		return $ips;
	}

	/**
	 *
	 * Method to paginate the ips to pages
	 * @param $page int the page number the default is 1
	 * @return array has the next, previous, and the offset
	 *
	 */

	public function paginate(int $page = 1) {
		$page = XSSFilter::globalXssClean($page);
		return $this->paginator->paginate('banned_ips', $page);
	}

	/**
	 *
	 * Method to add a new ip to the banned-ips  this method responds only to the ajax requests
	 * @param $ip string validated ip
	 *
	 *
	 */

	public function banIP(string $ip) {
		// @TODO check if it's ip and sanitize it
		$ip = XSSFilter::globalXssClean($ip);
		// 1 => admin id
		$newIP = new IP($ip, 1);
		if ($newIP->save()) {
			echo json_encode(['success' => true]);
			exit;
		} else {
			header('HTTP/1.1 503 Service Temporarily Unavailable');
		}
	}

	/**
	 *
	 * Method to delete ip from the banned ips this method responds only to the ajax requests
	 * @param $id int the id of the IP
	 *
	 */

	public function allowIP(int $id) {
		$id = XSSFilter::globalXssClean($id);
		if ($this->repo->deleteIP($id)) {
			echo json_encode(['success' => true]);
			exit;
		} else {
			header('HTTP/1.1 503 Service Temporarily Unavailable');
		}
	}

	/**
	 *
	 * Method to get an IP with it's id this method responds only to the ajax requests
	 * @param $id int the IP id
	 *
	 *
	 */

	public function getIP(int $id) {
		$id = XSSFilter::globalXssClean($id);
		$ip = $this->repo->getIP($id);
		$ip = XSSFilter::escape($ip);
		if ($ip) {
			header('Content-Type: application/json');
			echo json_encode($ip);
			exit;
		} else {
			header('HTTP/1.1 503 Service Temporarily Unavailable');
		}
	}

	/**
	 *
	 * Method to update an ip this method responds only to the ajax requests
	 * @param $id int the IP id
	 * @param $ip string the new IP
	 *
	 */

	public function editIP(int $id, string $ip) {
		$id = XSSFilter::globalXssClean($id);
		$ip = XSSFilter::globalXssClean($ip);
		// @TODO validate $data
		if ($this->repo->editIP($id, $ip)) {
			echo json_encode(['success' => true]);
			exit;
		} else {
			header('HTTP/1.1 503 Service Temporarily Unavailable');
		}
	}

	// it is better to write in an .htaccess file and ban it
	/**
	 *
	 * Method to check if the accessed user is banned
	 *
	 */

	public function isBanned() {
		$ip = $this->getRealIpAddr();
		if ($this->repo->isExists($ip)) {
			header('Location: ' . ROOT_URL . '401.php');
			exit;
		}
	}

	/**
	 *
	 * Method to get the real IP of the client
	 * @return $ip string the IP
	 */

	private function getRealIpAddr() {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) //check ip from share internet
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) //to check ip is pass from proxy
		{
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
}