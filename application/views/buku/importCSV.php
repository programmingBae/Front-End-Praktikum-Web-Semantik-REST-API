<?php
$koneksi    = mysqli_connect("localhost", "root", "", "login");
$sukses     = "";
$error      = "";
if (isset($_POST['input'])) {
    $filename = $_FILES["file"]["tmp_name"];
    $dataBarang = $_FILES["file"]["name"];
    $exe = pathinfo($dataBarang, PATHINFO_EXTENSION);
    if ($exe == 'csv') {
        if ($_FILES["file"]["size"] > 0) {
            $file = fopen($filename, "r");
            $number = 0;
            while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
               $data = array(
                   'idBuku' => $column[0],
                   'namaBuku' => $column[1],
                   'penerbit' => $column[2],
                   'pengarang' => $column[3],
                   'stokBuku' => $column[4],
               );
               if ($number>0){
                $this->db->insert('buku', $data);
               }
               $number++;
               
            }
        }
        redirect(site_url('buku'));
    } elseif ($exe == 'xml') {
        $rowaffected = 0;
        if ($_FILES["file"]["size"] > 0) {
            $xml = simplexml_load_file($_FILES['file']['tmp_name']);
            foreach ($xml as $value) {
                $data = array(
                    'idBuku' =>$value->idBuku,
                    'namaBuku' => $value->namaBuku,
                    'penerbit' => $value->penerbit,
                    'pengarang' =>$value->pengarang,
                    'stokBuku' =>$value->stokBuku,
                );
                
                $result = $this->db->insert('buku',$data);
                if (!empty($result)) {
                    $rowaffected++;
                }
            }
        }
        redirect(site_url('buku'));
    } else if ($exe = 'json'){
            
            $file = fopen($filename, "r");  
            $fread = fread($file,filesize($filename));
            $arrayHasilDecode = json_decode($fread,true);
            fclose($fread);
            foreach ($arrayHasilDecode as $rekkrit){
                $data = array (
                    'idBuku' => html_escape($this->security->xss_clean($rekkrit['idBuku'])),
                    'namaBuku' => html_escape($this->security->xss_clean($rekkrit['namaBuku'])),
                    'penerbit' => html_escape($this->security->xss_clean($rekkrit['penerbit'])),
                    'pengarang' =>html_escape($this->security->xss_clean($rekkrit['pengarang'])),
                    'stokBuku' =>html_escape($this->security->xss_clean($rekkrit['stokBuku'])),
                );
                $this->db->insert('buku',$data);
            }
        
        redirect(site_url('buku'));
    }
}
?>

<div class="container-fluid">
<form action="" method="POST" enctype="multipart/form-data">
                        <input type="file" name="file">
                        <input type="submit" name="input" value="IMPORT" class="btn btn-primary">
                    </form>
</div>