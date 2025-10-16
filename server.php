<?php

class FormatText {
    public static function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}


class Calculator {
    public function sum($a, $b){
        return $a+$b;
    }
}

class Request {
    private array $params;
    public function __construct( array $getParams,  array $postParams){
        $this->params = array_merge($getParams, $postParams);
    }
    public function get(string $param){
        if (isset($this->params[$param])){
            return $this->params[$param];
        }
        return null;
    }

}


class DataDto {
    public function __construct(
        public ?int $id,
        public ?string $name = '',
        public ?int $result = 0,
    ){

    }
}

$request = new Request($_GET, $_POST);
$calculator = new Calculator();
$data = new DataDto(id:1, name: FormatText::generateRandomString());

$paramA = (int) $request->get('a');
$paramB = (int) $request->get('b');

if ($paramA && $paramB) {
    $data->result = $calculator->sum($paramA, $paramB);
}


header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);
