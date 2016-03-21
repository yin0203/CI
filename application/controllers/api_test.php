<?php
require_once(APPPATH.'/libraries/REST_Controller.php');
 
class api_test extends REST_Controller {
	
	
     
    public function  connnectdb_get()
    {       
        $data = array('returned: '. $this->post('id'));
        $this->response($data);
    }
 
    public function user_put()
    {       
        $data = array('returned: '. $this->put('id'));
        $this->response($data);
    }
 
    public function user_delete()
    {
        $data = array('returned: '. $this->delete('id'));
        $this->response($data);
    }
	
	public function connnectdb()
	{
        $this->load->database();
        $url = 'http://www.hsi.com.hk/HSI-Net/static/revamp/contents/en/indexes/report/hsi/con_11Mar16.csv';

        // Fetch content and remove trailing line breaks
        $content = trim(file_get_contents($url));

        // Remove trailing BOM byte
        $content = substr($content, 0, -1);

        // Convert from UTF-16 to UTF-8
        $content = iconv('UTF-16LE', 'UTF-8', $content);

        // Split content into array by line breaks
        $lines = explode("\n", $content);

        // Remove first two header rows
        $lines = array_slice($lines, 2);

        $stocksrecords = array();

        foreach ($lines as $line) {
            $data = str_getcsv($line, "\t");
            try{
                $stocksrecord = array(
                    'tradedate' => $data[0],
                    'stockindex' => $data[1],
                    'stockcode' => $data[2],
                    'stockename' => $data[3],
                    'stockcname' => $data[4],
                    'exchangelisted' => $data[5],
                    'industry' => $data[6],
                    'treadingcurrency' => $data[7],
                    'closingprice' => $data[8],
                    'changepercent' => $data[9],
                    'indexpointcon' => $data[10],
                    'weightingpercent' => $data[11],
                    'weightinghsipercent' => $data[12],
                    // 'weightinghsiutil' => $data[13],
                    // 'weightinghsiprop' => $data[14],
                    // 'weightinghsicomm' => $data[15]
                );

                if(array_key_exists(13, $data))
                    $stocksrecord['weightinghsiutil'] = $data[13];
                else
                    $stocksrecord['weightinghsiutil'] = "";

                if(array_key_exists(14, $data))
                    $stocksrecord['weightinghsiprop'] = $data[14];
                else
                    $stocksrecord['weightinghsiprop'] = "";

                if(array_key_exists(15, $data))
                    $stocksrecord['weightinghsicomm'] = $data[15];
                else
                    $stocksrecord['weightinghsicomm'] = "";

                $insert_query = $this->db->insert_string('hsidb', $stocksrecord);
                $insert_query = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
                $this->db->query($insert_query);

                $stocksrecords[] = $stocksrecord;
            } catch (Expecetion $e) {
                echo $data;
            }
            unset($stocksrecord, $data, $insert_query);
    	}

       // var_dump($stocksrecords);
    }
	public function users()
    {
         array('returned: '. $this->get('id'));
        $this->response($stocksrecord);
    }
}