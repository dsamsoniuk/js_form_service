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
 <script src="js/event.js"></script> 
 <script src="js/template_builder.js"></script> 
 <script src="js/form_builder.js"></script> 

<script>




class AddressForm extends FormAbstract {
    "phone" = new FieldType([new AssertNotBlank()])
    "email" = new FieldType([new AssertNotBlank()])
}
class CustomerForm extends FormAbstract {
    "id" = new FieldType([
        new AssertNotBlank('Custom error example')
    ])
    "name" = new FieldType([
        new AssertNotBlank(),
        new AssertNotBlank()
    ])
    "address" = new FormCollection(AddressForm) 
    "files" = new FormCollection(FieldType, [
        new AssertNotBlank()
    ]) 
}
class CustomerFormBuilder extends FormAbstract {
    "customer" = new CustomerForm()
}




class ClonElementsEvent extends EventAbstract {
    /**
     * @param {TemplateService} templateService 
     */
    constructor(templateService){
        super()
        this.index = 0
        this.exec = this.exec.bind(this); // bind all class, instead only function
        this.templateService = templateService
    }
    exec(){
        const container = document.getElementById('cloned-box')
        const params = { index: this.index }
        const template = this.templateService.render(params)

        container.append(template)
        this.index++;
    }
    init(){
        const clonBtn = document.getElementById("cloneButton")
        const prototype = document.getElementsByTagName('protptyp-field')[0]

        this.templateService.setPrototype(prototype)
        clonBtn.addEventListener("click", this.exec)
    }
}

class SubmitFormEvent extends EventAbstract {
    constructor(
        form, 
        formElement, 
        formMapper, 
        formErrorService, 
        formValidatorService
    ){
        super()
        this.form = form
        this.formElement = formElement
        this.formMapper = formMapper
        this.errorService = formErrorService
        this.formValidator = formValidatorService
        this.exec = this.exec.bind(this); // bind all class, instead only function
    }
    exec(e){
        e.preventDefault()

        const formData = new FormData(this.formElement)

        this.form = this.formMapper.setFormData(this.form, formData)
        this.errorService.clear(this.formElement)
           
        if (this.formValidator.validate(this.form)) {
            console.log('Formularz poprawny')
            return
        } else {
            console.log('Formularz nie poprawny')
        }
        // console.log(this.form)

        this.errorService.showErrors(this.form, this.formElement)
    }
    init(){
        const submit = document.getElementById("formSubmit")
        submit.addEventListener("click", this.exec)
    }
}



/** INIT */
document.addEventListener("DOMContentLoaded", (event) => { 
    const formElement = document.querySelector('form')

    const form = new CustomerFormBuilder()
    const clone = new ClonElementsEvent(new TemplateService())
    const submit = new SubmitFormEvent(form, formElement, new FormMapper(), new FormErrorService(), new FormValidator())

    clone.init()
    submit.init()
});

</script>


</body>
</html>