<?php

class Form {
    public $save = 'Save';
    public $back = "Go back";
    public $confirmDelete = "Delete this line?";
    private array $datas;
    private array $header;
    private $cancel = 'cancel';
    private $submit = 'submit';
    private $selectName;
    private $selectValue;
    public int $selectCount = 3;

    public function getSubmit() {
        return $this->submit;
    }
    public function getCancel() {
        return $this->cancel;
    }
    public function setSName(array $selectName) {
        $this->selectName = $selectName;
    }
    public function setSValue(array $selectValue) {
        $this->selectValue = $selectValue;
    }

    public function __construct(array $datas, array $header) {
        $this->datas = $datas;
        $this->header = $header;
    }

    function getHeader() {
        if(is_array($this->datas)) {
            if(count($this->datas) === 0) {
                $header = ['Nincs találat'];
                return $header;
            } else {
            $header = array_keys($this->datas[0]);
            return $header;
            }
        } else {
            print 'Function not available due to wrong data';
        }
    }
    
    function generateForm(bool $select = false) {
    if (count($this->datas[0]) === count($this->header)) {
    $form = '<form method="post">';
        foreach ($this->datas as $key => $data) {
            $i = 0;
            foreach ($data as $key => $value) {
            $i++;
            if ($select === true && $i === $this->selectCount) {
                $form .= $this->generateSelect($i).'<br>';
            } else {
            $form .= '<label>'.$this->header[$i-1].'</label><br>'.$this->generateText($this->header[$i-1], $value).'<br>';
            }
            }
        }
    $form .= $this->generateButton($this->cancel, $this->back);
    $form .= $this->generateButton($this->submit, $this->save);
    $form .= '</form>';
    return $form;
    }
    }

    function generateConfirmForm($dataShow = true) {
    $form = '<form method="post">';
    $form .= '<p>'.$this->confirmDelete.'</p><br>';
    if($dataShow === true) {
    foreach ($this->datas as $key => $data) {
        $i = 0;
        foreach ($data as $key => $value) {
        $i++;
        $form .= '<label>'.$this->header[$i-1].'</label><br>'.$this->generateText($this->header[$i-1], $value, true).'<br>';
        }
    }
    }
    $form .= $this->generateButton($this->cancel, $this->back);
    $form .= $this->generateButton($this->submit, $this->save);
    $form .= '</form>';
    return $form;
    }

function generateText(string $name, string $value = null, bool $readonly = false){
    if ($readonly) {
        return '<input type="text" name="'.$name.'" '. ($value !== null ? 'value="'.$value.'" ': '').' readonly>';
    }else if (strtolower($name) === 'id') {
        return '<input type="text" name="'.$name.'" '. ($value !== null ? 'value="'.$value.'" ': '').' readonly>';
    } else {
        return '<input type="text" name="'.$name.'" '. ($value !== null ? 'value="'.$value.'" ': '').'>';
    }
}

function generateButton(string $name, string $value) {
    return '<input type="submit" name="'.$name.'" '. 'value="'.$value.'" '.'>';

}

function generateSelect(int $i) {
    if(count($this->selectName) === count($this->selectValue)) {
    $select = '<label>'.$this->header[$i-1].'</label>';
    $select .= '<select name="searchColumn" id="searchColumn">';
        for ($i=0; $i < count($this->selectName); $i++) { 
            $select .= '<option value="'.$this->selectName[$i].'">'.$this->selectValue[$i].'</option>';
        }
    $select .= '</select>';
    return $select;
    } else {
        print 'Function not available due to wrong data';
    }
}

}