<!DOCTYPE html>
<html>
<head>

</head>
<body>
    
<h1>front</h1>
http://localhost/server.php?a=3&b=6


<!-- <form action="/server.php" name="customer">
    <input type="text" name="customer[id]" id="">
    <input type="text" name="customer[address1][][phone1]" id="">
    <input type="text" name="customer[address1][][email]" id="">
    <input type="text" name="customer[address2][][phone1]" id="">
    <input type="text" name="customer[address2][][email]" id="">
</form> -->

<form action="/server.php" name="customer">
    <div>
        id:<input id="customerId" type="number" name="customer[id]" id="" value="234">
    </div>
    <br>
    <div>
        name:<input id="customerName" type="text" name="customer[name]" id="">
    </div>
    <br>
    <input id="customerA" type="text" name="a" id="">
    <br>
    <div>
        <input id="customerAddressPhone" type="text" name="customer[address][0][phone]" id="" value="334455">
        <input id="customerAddressPhoneee" type="text" name="customer[address][0][email]" id="" value="334455">
    </div>
    <div>
        <div>
            <input id="customerC" type="text" name="customer[address][4][email]" id="" value="jacek@pp.pl">
        </div>
        <div>
        <input id="customerC" type="text" name="customer[address][4][phone]" id="" value="333">
        </div>
    </div>

<br>
    <button type="button" id="cloneButton">dodaj pole</button><br>

    <protptyp-field style="display:none">
        <div class="" style="display:flex">
            <div>{{index}}</div>
            <div>file</div><input type="text" name="customer[files][{{index}}]" value="">
        </div>
        <hr>-----
    </protptyp-field>

<br>
    <div id="cloned-box"></div>
    <button id="formSubmit" type="submit">Wyslij</button>
</form>


 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> 
 
 <script src="js/utils.js"></script> 
 <script src="js/asserts.js"></script> 
 <script src="js/template.js"></script> 
 <script src="js/form_service.js"></script> 

<script>

class AddressForm extends FormAbstract {
    "phone" = new FieldType([new AssertNotBlank()])
    "email" = new FieldType([new AssertNotBlank()])
}
class CustomerForm extends FormAbstract {
    "id"        = new FieldType([new AssertNotBlank('This field cant be empty !!!')])
    "name"      = new FieldType([
        new AssertNotBlank('This field cant be empty !!!'),
        new AssertNotBlank('This fielrtrtr!')
    ])
    "address"   = new FormCollection(AddressForm) 
    "files"     = new FormCollection(FieldType, [new AssertNotBlank('This field cant be empty !!!')]) 
}

class CustomForm extends FormAbstract {
    "customer" = new CustomerForm()
}


class ClonElementsEvent extends EventAbstract {
    /**
     * @param {TemplateService} templateService 
     */
    constructor(templateService){
        super()
        this.templateService = templateService
        this.index = 0
    }
    exec(){
        const template = this.templateService.render({
            name: 'test_',
            index: this.index,
        })
        const container = document.getElementById('cloned-box')
        container.append(template)

        this.index++;
    }
    init(){
        this.exec = this.exec.bind(this);
        document.getElementById("cloneButton").addEventListener("click", this.exec)
    }
}

class SubmitFormEvent extends EventAbstract {

    constructor(form, formElement, formMapper, formErrorService, formValidatorService){
        super()
        this.form = form
        this.formElement = formElement
        this.formMapper = formMapper
        this.formErrorService = formErrorService
        this.formValidatorService = formValidatorService
    }
    exec(e){
        e.preventDefault()

        const formData = new FormData(this.formElement)
        this.form =  this.formMapper.setFormData(this.form, formData)

        this.formErrorService.clear(this.formElement)
           
        if (this.formValidatorService.validate(this.form)) {
            console.log('Formularz poprawny')
            return
        } else {
            print('Nie waliduje')
        }
        console.log(this.form)

        this.formErrorService.showErrors(this.form, this.formElement)
    }
    init(){
        this.exec = this.exec.bind(this);
        document.getElementById("formSubmit").addEventListener("click", this.exec)
    }
}



/** INIT */
document.addEventListener("DOMContentLoaded", (event) => { 

    const formElement = document.querySelector('form')

    const form = new CustomForm()

    const prototype = document.getElementsByTagName('protptyp-field')[0]
    const templateService = new TemplateService(prototype)

    const clone = new ClonElementsEvent(templateService)
    clone.init()
    const submit = new SubmitFormEvent(form, formElement, new FormMapper(), new FormErrorService(), new FormValidator())
    submit.init()
});

</script>


</body>
</html>