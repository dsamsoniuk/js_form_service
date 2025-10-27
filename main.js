/// <reference path="js-form-service.js" />

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