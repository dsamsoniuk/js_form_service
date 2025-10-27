<!DOCTYPE html>
<html>
<head>

</head>
<body>

<?php
    $translations = [
        'text1' => 'Hello world',
        'text2' => 'Hi, everyone',
    ];
    $json = htmlspecialchars(
        json_encode($translations), 
        ENT_QUOTES, 
        'UTF-8'
    );
?>
<div id="translations-en" style="display:none"><?php echo $json ?></div>


<h1>Example form walidator</h1>


<form action="/server.php" name="customer">
    <h3>Common data</h3>
    <div>
        <label for="">id</label>
        <input id="customerId" type="number" name="customer[id]"  value="234">
    </div>
    <br>
    <div>
        <label for="">name</label>
        <input id="customerName" type="text" name="customer[name]" >
    </div>

    <div>
        <label for="">Opcja test</label>
        <select name="customer[option]" >
            <option value=""></option>
            <option value="option1">option1</option>
            <option value="option2">option2</option>
        </select>
    </div>

    <h3>Address</h3>


    <div>
        <label for="">Address 1</label>
        <div>
            <label for="">email</label>
            <input id="customerC" type="text" name="customer[address][1][email]"  value="jacek@pp.pl">
        </div>
        <div>
            <label for="">phone</label>
            <input id="customerC" type="text" name="customer[address][1][phone]"  value="333">
        </div>
    </div>
    <br>
    <br>
    <div>
        <label for="">Address 2</label>

        <div>
            <label for="">email</label>
            <input id="customerC" type="text" name="customer[address][4][email]"  value="jacek@pp.pl">
        </div>
        <div>
            <label for="">phone</label>
            <input id="customerC" type="text" name="customer[address][4][phone]"  value="333">
        </div>
    </div>

    <h3>Files</h3>

    <button type="button" id="cloneButton">Attache file</button><br>

    <div id="prototyp-field-file" class="prototype-box" style="display:none">
        <label>File ({@index@}):</label>
        <div style="display:flex">
            <div><input type="file" name="customer[files][{@index@}]" value=""></div>
            <div> <button type="button" class="delete-proto">-</button></div>
        </div>
    </div>

    <br>
    <div id="cloned-box"></div>

    <br>
    <button id="formSubmit" type="submit">Send data</button>
</form>


 <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>  -->
 
 <!-- <script src="src/asserts.js"></script> 
 <script src="src/event.js"></script> 
 <script src="src/template_builder.js"></script> 
 <script src="src/form_builder.js"></script>  -->

 <script src="js-form-service.js"></script> 
 <script src="main.js"></script> 
 <!-- <script src="dist/js-form-service.min.js"></script>  -->



</body>
</html>