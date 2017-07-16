<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->helper("az_core");
        az_check_login();
    }

	public function index(){
		$azapp = $this->load->library('AZApp');
		$azapp = $this->azapp;

		$data_header['title'] = azlang('DASHBOARD');
		$data_header['subtitle'] = "";
		$azapp->set_data_header($data_header);

		$data = array();
		$today = Date("Y-m-d");
		$this->db->select("sum(total_final_price) as total");
		$this->db->where("transaction_date BETWEEN '".$today." 00:00:00' AND '".$today." 23:59:59'");
		if ($this->session->userdata("user_type") != "administrator") {
			$this->db->where("iduser", $this->session->userdata("iduser"));
		}
		$this->db->where("status", "OK");
		$price = $this->db->get("transaction_group");
		
		if ($this->session->userdata("user_type") != "administrator") {
			$this->db->where("iduser", $this->session->userdata("iduser"));
		}
		$this->db->where("status", "OK");
		$this->db->where("transaction_date BETWEEN '".$today." 00:00:00' AND '".$today." 23:59:59'");
		$total = $this->db->get("transaction_group");
			
		$data["price"] = 0;
		$data["total"] = $total->num_rows();
		
		if ($price->num_rows() > 0) {
			$data["price"] = az_thousand_separator($price->row()->total);
		}

		$data['last_transaction'] = 0;
		$data['total_last_transaction'] = 0;
		$data['last_transaction_date'] = "";
		if ($this->session->userdata("user_type") != "administrator") {
			$this->db->where("iduser", $this->session->userdata("iduser"));
		}
		$this->db->order_by("idtransaction_group", "desc");
		$this->db->where("status", "OK");
		$last_transaction = $this->db->get("transaction_group", 1);
		if ($last_transaction->num_rows() > 0) {
			$data['last_transaction'] = az_thousand_separator($last_transaction->row()->total_final_price);
			$data['last_transaction_date'] = Date("d-m-Y H:i:s", strtotime($last_transaction->row()->transaction_date));
			$data['code'] = $last_transaction->row()->code;
			$this->db->select("sum(qty) as total");
			$this->db->where("idtransaction_group", $last_transaction->row()->idtransaction_group);
			$data['total_last_transaction'] = $this->db->get("transaction")->row()->total;
		}

		$this->db->where("stock < 10");
		$this->db->order_by("stock");
		$data["product_stock"] = $this->db->get("product");


		$v_home = $this->load->view('home/v_home', $data, true);
		$azapp->add_content($v_home);

		echo $azapp->render();
	}

	public function graph_transaction_month() {
		$this->load->library('Lib/Jpgraph');
           
        $bar_graph = $this->jpgraph->linechart();

        $datay1 = array();

		// Setup the graph
		$graph = new Graph(800,400);
		$graph->SetScale("textlin");

		$graph->img->SetAntiAliasing();

		$arr_day = array();
		$month_year = Date('Y-m');
		for ($i=1;$i<=31;$i++) {
			$arr_day[] = $i;
			$this->db->select("sum(total_final_price) as total");
			$this->db->where("transaction_date BETWEEN '".$month_year."-".$i." 00:00:00' and '".$month_year."-".$i." 23:59:59'");
			if ($this->session->userdata("user_type") != "administrator") {
				$this->db->where("iduser", $this->session->userdata("iduser"));
			}
			$total = $this->db->get("transaction_group")->row()->total;
			if (strlen($total) == 0) {
				$total = 0;
			}
			$datay1[] = $total;
		}

		$graph->yaxis->HideTicks(false,false);

		$graph->setMargin(80, 20, 20, 40);

		$graph->xgrid->Show();
		$graph->xaxis->SetTickLabels($arr_day);
		$graph->xgrid->SetColor('#E3E3E3');

		// Create the first line
		$p1 = new LinePlot($datay1);
		$graph->Add($p1);
		$p1->SetColor("#6495ED");
		$p1->SetLegend('Rupiah');

		$graph->legend->SetFrameWeight(4);

		// Output line
		$graph->Stroke();
	}

	public function graph_product() {
		$this->load->library('Lib/Jpgraph');
           
        $bar_graph = $this->jpgraph->barchart();

        // SELECT idproduct, count(idproduct), sum(qty) FROM `transaction` group by idproduct ORDER BY `idtransaction` DESC 
        $this->db->select("product.name, sum(transaction.qty) as total");
        $this->db->group_by("transaction.idproduct");
        $this->db->order_by("total", "desc");
        $this->db->join("product", "product.idproduct = transaction.idproduct");
        $pen = $this->db->get("transaction", 6);

        $datax = array('', '');
        $datay = array(0, 1);
        if ($pen->num_rows() > 1) {
        	$datax = array();
        	$datay = array();
        }
        $i=0;
        foreach($pen->result() as $value){
            $datax[$i] = $value->name;
            $datay[$i] = $value->total;
            $i++;
        }
           
        $graph = new PieGraph(450,275,"auto");
        $graph->SetScale('textint');
        $graph->img->setMargin(50, 60, 60, 60);
                  	
        $graph->legend->SetColumns(2);

        $bplot = new PiePlot3D($datay);
        $bplot->SetCenter(0.5,0.40);
        $bplot->SetLegends($datax);
        $bplot->value->Show();

                   
        $graph->Add($bplot);
        $graph->Stroke();
	}

	public function change_language($lang) {
		switch ($lang) {
			case 'id':
				$language = 'indonesian';
				break;
			case 'en':
				$language = 'english';
				break;
			default:
				$language = 'english';
				break;
		}

		$this->config->set_item('language', 'indonesian');
		$this->session->set_userdata('azlang', $language);
	}
}