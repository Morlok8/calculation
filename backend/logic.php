<?php
require_once 'sdbh.php';
$dbh = new sdbh();
//класс калькуляции
class calculation {
    public $product;
    public $numberOfDays;
    public $dBase;
    public $additionalServices;

    function __construct($product, $numberOfDays, $dBase, $additionalServices){
        $this->product = intval($product);

        $this->numberOfDays = intval($numberOfDays);

        $this->dBase = $dBase;

        if(isset($additionalServices)){
            $this->additionalServices = $additionalServices;
        }
    }
    //функция расчета калькуляции
    function calculate(){
        if($this->product === 0){
            return "Не удалось провести калькуляцию";
        }
        //запрос получения тарифа
        $tariff = unserialize($this->dBase->mselect_rows('a25_products', ['ID' => $this->product], 0, 1, 'id')[0]['TARIFF']);
        //запрос цены продукта
        $price = $this->dBase->mselect_rows('a25_products', ['ID' => $this->product], 0, 1, 'id')[0]['PRICE'];
        //в случае, если тариф существует, выполнить следующее
        if(isset($tariff) && !empty($tariff)){
            //найти последний ключ массива (в данном массиве, ключ - это количество дней, с которых меняется цена)
            $keyLast = array_key_last($tariff);
            //пройтись по всем ключам массива
            foreach(array_keys($tariff) as $key){
                //если ключ меньше количества указанных дней, то значение последнего ключа переприсваивается
                if($key <= $this->numberOfDays){
                    $keyLast = $key;
                }
            }
            //таким образом, если есть тариф и выполняются условия, то цена переприсваивается 
            $price = $tariff[$keyLast];
        }
        //расчет цены
        $priceTotal = $price * $this->numberOfDays;
        //если есть доп. услуги, то к цене прибавляется их стоимость
        try{
            if(isset($this->additionalServices)){
                foreach($this->additionalServices as $serv){
                    $priceTotal += $serv * $this->numberOfDays;
                }
            }
        }
        catch(TypeError $er){
            return $priceTotal;
        }

        return $priceTotal;
    }

    public function __destruct() {
        return;
    }
}
//получение значений со стороны клиента
$product = $_POST['product'];

$days = $_POST['days'];

$check = NULL;

if(isset($_POST['check']) && count($_POST['check']) > 0){
    $check = $_POST['check'];
}
//инициализация объекта класса калькуляции
$calculation = new calculation($product, $days, $dbh, $check);
//расчет
$result = $calculation->calculate();
//вывод
echo json_encode($result);
