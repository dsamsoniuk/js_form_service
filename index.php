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
        <input id="customerId" type="number" name="customer[id]" id="" value="234">
    </div>
    <br>
    <div>
        <label for="">name</label>
        <input id="customerName" type="text" name="customer[name]" id="">
    </div>

    <div>
        <label for="">Opcja test</label>
        <select name="customer[option]" id="">
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
            <input id="customerC" type="text" name="customer[address][1][email]" id="" value="jacek@pp.pl">
        </div>
        <div>
            <label for="">phone</label>
            <input id="customerC" type="text" name="customer[address][1][phone]" id="" value="333">
        </div>
    </div>
    <br>
    <br>
    <div>
        <label for="">Address 2</label>

        <div>
            <label for="">email</label>
            <input id="customerC" type="text" name="customer[address][4][email]" id="" value="jacek@pp.pl">
        </div>
        <div>
            <label for="">phone</label>
            <input id="customerC" type="text" name="customer[address][4][phone]" id="" value="333">
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
 <!-- <script src="dist/js-form-service.min.js"></script>  -->


<script>


class AddressForm extends FormAbstract {
    "phone" = new FieldType([new AssertNotBlank()])
    "email" = new FieldType([new AssertNotBlank()])
}
class CustomerForm extends FormAbstract {
    "id" = new FieldType([
        new AssertNumber(null, 5, 10)
    ])
    "name" = new FieldType([
        new AssertEmail(),
        new AssertLength(5,16),
    ])
    "option" = new FieldType([new AssertNotBlank()])
    "address" = new FormCollection(AddressForm)
    "files" = new FormCollection(FieldType, [
        new AssertFileRequired(null, null, ['image/png', 'image/jpg']),
    ]) 
}
class CustomerFormBuild extends FormAbstract {
    "customer" = new CustomerForm()
}



class AddPrototypeEvent extends EventAbstract {
    /**
     * @param {TemplateService} templateService 
     */
    constructor(templateService, clonBtn, prototype, container, params){
        super()
        this.index = 0
        // bind all class, instead only function
        this.exec = this.exec.bind(this);
        this.templateService = templateService
        this.clonBtn = clonBtn
        this.prototype = prototype
        this.container = container
        this.params = params ?? {}
    }
    exec(event){
        const params = Object.assign({ index: this.index }, this.params)
        const template = this.templateService.render(params)
        this.container.append(template)
        this.index++;
    }
    init(){
        this.templateService.setPrototype(this.prototype)
        this.clonBtn.addEventListener("click", this.exec)
    }
}

class DeletePrototypeEvent extends EventAbstract {
    exec(event){
        if (event.target.matches('.delete-proto')) {
            event.target.closest('.prototype-box').remove()
        }
    }
    init(){
        document.addEventListener('click', this.exec);
    }
}

class SubmitFormEvent extends EventAbstract {
    constructor(
        formClass, 
        formMapper, 
        formErrorService, 
        formValidatorService,
        formElement
    ){
        super()
        this.formClass = formClass
        this.formElement = formElement
        this.formMapper = formMapper
        this.errorService = formErrorService
        this.formValidator = formValidatorService
        this.exec = this.exec.bind(this); // bind all class, instead only function
    }
    exec(event){
        event.preventDefault()

        const formData = new FormData(this.formElement)
        let form = new this.formClass()

        form = this.formMapper.setFormData(form, formData)
        this.errorService.clear(this.formElement)
        // console.log(form)
        if (this.formValidator.validate(form)) {
            console.log('Correct form data')
            return
        } else {
            console.log('Uncorrect form data')
        }

        this.errorService.showErrors(form, this.formElement)
    }
    init(){
        const submit = document.getElementById("formSubmit")
        submit.addEventListener("click", this.exec)
    }
}



/** INIT */
document.addEventListener("DOMContentLoaded", (event) => { 

    // const translationsEn = JSON.parse(document.getElementById('translations-en').textContent);
    // console.log(translationsEn)

    const clone = new AddPrototypeEvent(
        new TemplateService(), 
        document.getElementById("cloneButton"),
        document.getElementById('prototyp-field-file'),
        document.getElementById('cloned-box')
    );
    clone.init()

    const delBtn = new DeletePrototypeEvent()
    delBtn.init()

    const submit = new SubmitFormEvent(
        CustomerFormBuild, 
        new FormMapper(), 
        new FormErrorService(), 
        new FormValidator(),
        document.querySelector('form'), 
    );
    submit.init()


});

</script>


</body>
</html>