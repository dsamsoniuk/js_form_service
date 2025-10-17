<!DOCTYPE html>
<html>
<head>

</head>
<body>
    
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

    <protptyp-field style="display:none">
        <label>File ({{index}}):</label>
        <div>
            <input type="file" name="customer[files][{{index}}]" value="">
        </div>
    </protptyp-field>

<br>
    <div id="cloned-box"></div>
    <br>
    <button id="formSubmit" type="submit">Send data</button>
</form>


 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> 
 
 <script src="js/utils.js"></script> 
 <script src="js/asserts.js"></script> 
 <script src="js/event.js"></script> 
 <script src="js/template_builder.js"></script> 
 <script src="js/form_builder.js"></script> 
 <!-- <script src="cnd.js"></script>  -->

<script>




class AddressForm extends FormAbstract {
    "phone" = new FieldType([new AssertNotBlank()])
    "email" = new FieldType([new AssertNotBlank()])
}
class CustomerForm extends FormAbstract {
    "id" = new FieldType([
        new AssertNotBlank('Custom error example')
    ])
    "name" = new FieldType([new AssertNotBlank(),])
    "option" = new FieldType([new AssertNotBlank()])
    "address" = new FormCollection(AddressForm)
    "files" = new FormCollection(FieldType, [
        new AssertFileRequired(null, null, ['image/png', 'image/jpg']),
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
        //    console.log(this.form)
        if (this.formValidator.validate(this.form)) {
            console.log('Correct form data')
            return
        } else {
            console.log('Uncorrect form data')
        }

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