<?php
header('Content-Type: text/plain; charset=utf-8'); 

$name = $teacher = $description  = $group = $credits = "";
$check_group=array("М","ПМ","ОКН","ЯКН");
$no_errors=array("success"=>"true");
$found_errors=array("name"=>'',"teacher"=>"","description"=>"","group"=>"","credits"=>"");


if($_SERVER["REQUEST_METHOD"] == "POST") {
 
    $name=$_POST["name"];
    if(empty($name)) {  
        $found_errors["name"]="Името на учебния предмет е задължително поле";  
    }elseif(!empty($name)) {  
        $name = validate_input($name);  
        if(!preg_match("/^[\p{Cyrillic}\s\-]+$/u",$name)) {  
            $found_errors["name"]="Невалидно поле - името трябва да е на кирилица"; 
        }
        if((strlen(utf8_decode($name))<2) || (strlen(utf8_decode($name))>150)){
            $found_errors["name"]="Невалидно поле - името на учебния предмет трябва да е между 2 и 150 символа"; 
        }
    }else{
        $no_errors["success"]=true;   
    }

    $teacher=$_POST["teacher"];
    if(empty($teacher)) {  
        $found_errors["teacher"]="Името на преподавателя е задължително поле"; 
    }elseif(!empty($teacher)) {  
        $teacher = validate_input($teacher);  
        if(!preg_match("/^[\p{Cyrillic}\s\-]+$/u",$teacher)) {  
            $found_errors["teacher"]="Невалидно поле - името на преподаватя трябва да е на кирилица"; 
        }
        if((strlen(utf8_decode($teacher))<3) || (strlen(utf8_decode($teacher))>200)){
            $found_errors["teacher"]="Невалидно поле - името на преподавателя трябва да е между 3 и 200 символа"; 
        }
    }else{
        $no_errors["success"]=true;   
    }

    $description=$_POST["description"];
    if(empty($description)) { 
        $found_errors["description"]="Описанието е задължително поле"; 
    }elseif(!empty($description)) {  
        $description = validate_input($description);  
        if(!preg_match("/^[\p{Cyrillic}\s\-]+$/u",$description)) {  
            $found_errors["description"]="Невалидно поле - описанието трябва да е на кирилица"; 
        }
        if((strlen(utf8_decode($description)))<10){
            $found_errors["description"]="Невалидно поле - описанието трябва да е с дължина поне 10 символа, а Вие сте въвели " .(strlen(utf8_decode($description))). " символа"; 
        }
    }else{
        $no_errors["success"]=true;   
    }

    $group=$_POST["group"];
    if(empty($group)) {  
        $found_errors["group"]="Избора на група е задължително"; 
    }elseif(!empty($group)){
        $group = validate_input($group);  
        if(!in_array($group, $check_group)) {
            $found_errors["group"]="Невалидна група, изберете една от М, ПМ, ОКН и ЯКН"; 
        }
        if(!preg_match("/^[\p{Cyrillic}\s\-]+$/u",$group)) {  
            $found_errors["group"]="Невалидно поле - избраната група трябва да е на кирилица"; 
        } 
    }else{
        $no_errors["success"]=true;   
    }

    $credits=$_POST["credits"];
    if(empty($credits)) {  
        $found_errors["credits"]="Кредитите са задължително поле"; 
    }elseif(!empty($credits)) {  
        $credits = validate_input($credits);  
        if(!preg_match("/^[1-9]+$/u",$credits)||((is_integer($credits)) && ((int)($credits)<=0))) {  
            $found_errors["credits"]="Невалидно поле - кредитите трябва да са цяло положително число"; 
        }
    }else{
        $no_errors["success"]=true;   
    }  
}  

function validate_input($input) {  
    $input = trim($input);   
    $input = stripslashes($input);  
    $input = htmlspecialchars($input);  
    return $input;  
}

if($found_errors["name"]=="" && $found_errors["teacher"]=="" && $found_errors["description"]=="" && $found_errors["group"]=="" && $found_errors["credits"]==""){
    echo json_encode($no_errors);

}else{
    $found_errors = array_filter($found_errors, function ($x){return $x !== "";});
    echo json_encode(array("success"=>false,"error"=>$found_errors),JSON_UNESCAPED_UNICODE);
}

?>