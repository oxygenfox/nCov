<?php
defined('BASEPATH') or exit('No direct script access allowed');

//** Copyright Andry Setyoso
//** Github OxygenFox
//** Telegram https://t.me/FvckinD/ *//

class CoronaVirus extends CI_Controller
{
  private $url = "https://coronavirus-19-api.herokuapp.com/countries";
  private $url_2 = "https://api.kawalcorona.com/indonesia/provinsi";
  private $url_3 = "https://coronavirus-19-api.herokuapp.com/countries/indonesia";
  private $url_4 = "https://louislugas.github.io/covid_19_cluster/json/kasus-corona-indonesia.json";


  public function __construct() {
    parent::__construct();
  }

  public function index() {
    $data['RESULT_DATA'] = array();

    $contents = file_get_contents($this->url);
    if ($contents === false)  $resultJSON = NULL;
    else $data['RESULT_DATA'] = json_decode($contents, true);

    $geochart_country_update = array("USA" => "US", "UK" => "United Kingdom", "S. Korea" => "South Korea");
    $data['RESULT_DATA'] = $this->change_country($data['RESULT_DATA'], $geochart_country_update);

    $data['TOTAL_CASE_REPORTED'] = array_sum(array_column($data['RESULT_DATA'], 'cases'));
    $data['TOTAL_DEATHS_REPORTED'] = array_sum(array_column($data['RESULT_DATA'], 'deaths'));
    $data['TOTAL_RECOVERED_REPORTED'] = array_sum(array_column($data['RESULT_DATA'], 'recovered'));
    $data['TOTAL_CASE_REPORTED_LASTDAY'] = $data['TOTAL_CASE_REPORTED'] - array_sum(array_column($data['RESULT_DATA'], 'todayCases'));

    $contents_2 = file_get_contents($this->url_2);
    if ($contents_2 === false)  $resultJSON = NULL;
    else $data['RESULT_INDO'] = json_decode($contents_2, true);

    $contents_3 = file_get_contents($this->url_3);
    if ($contents_3 === false)  $resultJSON = NULL;
    else $data['COUNT_INDO'] = json_decode($contents_3, true);

    // var_dump($data['RESULT_DETAILS']);
    // die;

    $data['TOTAL_CASE_INDO'] = $data['COUNT_INDO']['cases'];
    $data['TOTAL_DEATHS_INDO'] = $data['COUNT_INDO']['deaths'];
    $data['TOTAL_RECOVERED_INDO'] = $data['COUNT_INDO']['recovered'];
    $data['TOTAL_CASE_INDO_LASTDAY'] = $data['COUNT_INDO']['todayCases'];

    $contents_4 = file_get_contents($this->url_4);
    if ($contents_4 === false)  $resultJSON = NULL;
    else $data['RESULT_DETAILS'] = json_decode($contents_4, true);

    $data['RESULT_DETAILS'] = $data['RESULT_DETAILS']['nodes'];


    $this->load->view('coronavirus_vw', $data);
  }

  private function change_country($data, $geochart_country_update) {
    $i = 0;
    foreach ($data as $each) {
      if (array_key_exists($each['country'], $geochart_country_update)) {
        $data[$i]['country'] = $geochart_country_update[$each['country']];
      }
      $i++;
    }

    return $data;
  }

  public function vardump() {

    $url = "https://api.kawalcorona.com/indonesia";


    $contents = file_get_contents($url);
    if ($contents === false)  $resultJSON = NULL;
    else $datas = json_decode($contents, true);




    $data['TOTAL_CASE_INDO'] = $datas;




    var_dump($data->TOTAL_CASE_INDO->name);
    die;


  }
}