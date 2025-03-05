<?php
//require('./config/dbconfig.php');

class Table {
    public $extraHeader = 'Actions';
    public $modify = 'Modify';
    public $delete = 'Delete';
    public $noFound = '<p>No lines found</p>';

    private array $datas ;
    public bool $action = true;

    private array $header;
    public function __construct(array $datas) {
        $this->datas = $datas;
        $this->header = $this->getHeader();
    }
    
    public function setHeader($header)
    {
        $this->header = $header;
    }

    function getHeader() {
        if(is_array($this->datas)) {
            if(count($this->datas) === 0) {
                $header = [$this->noFound];
                return $header;
            } else {
            $header = array_keys($this->datas[0]);
            return $header;
            }
        } else {
            print 'Function not available due to wrong data';
        }
    }
    function generateTable(string $footer = null){
        $tableString = '<table>';
        $tableHeaderString = '';
        if(count($this->datas) === 0) {
            $tableString .= $this->header[0];
        }
        else if($this->header !== null){
            if(count($this->header) !== count($this->datas[0])){
                return 'Error: amount of header and data not matching';
            }
            $this->action ? $this->header[] = $this->extraHeader : $this->header;
            $tableHeaderString = $this->generateTableHeader();
        }
        $tableString .= $tableHeaderString;
        $tableString .= $this->generateTableBody();
            if($footer !== null){
                $tableFooter = '<tfoot><tr><td colspan = '.(count($this->header) +1).'><button>'.$footer.'</button></td></tr></tfoot>';
                $tableString .= $tableFooter;
            }
        $tableString .= '</table>';
        return $tableString;
    }
    function generateTableHeader(){
        $tableHeaderString = '<thead>';
        $tableHeaderString .= '<tr>';
        for ($i=0; $i < count($this->header); $i++) { 
            $this->header[$i] === $this->extraHeader ?
                $tableHeaderString .= '<th colspan = 2>'.$this->header[$i].'</th>' :
                $tableHeaderString .= '<th>'.$this->header[$i].'</th>';
        }
        $tableHeaderString .= '</tr>';
        $tableHeaderString .= '</thead>';
        return $tableHeaderString;
    }
    function generateTableBody(){
        $tableString = "<tbody> \n";
        foreach ($this->datas as $key => $data) {
            $data = array_change_key_case($data, CASE_LOWER);
            $tableString .= "<tr> \n";
            foreach ($data as $key => $column) {
                $tableString .= '<td>'.$column."</td> \n";
            }
            if($this->action && isset($data['id'])){
                $tableString .= '<td>'.$this->generateUpdateAction((int)$data['id'])."</td> \n";
                $tableString .= '<td>'.$this->generateDeleteAction((int)$data['id'])."</td> \n";
    
            }
            $tableString .= "</tr> \n";
        }
        $tableString .= "</tbody> \n";
        return $tableString;
    }
    function generateDeleteAction(int $id){
        return '<form method="POST">'
        . '<input type="hidden" name="delete" id="delete_'.$id.'" value="'.$id.'">'
        . '<button type="submit">'.$this->delete.'</button>'
        . '</form>';
        
    }
    function generateUpdateAction(int $id){
        return '<form method="POST">'
        . '<input type="hidden" name="update" id="update_'.$id.'" value="'.$id.'">'
        . '<button type="submit">'.$this->modify.'</button>'
        . '</form>';
    }

}