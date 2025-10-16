
class FormAbstract {}
class FormService {}

class FieldType { 
    constructor(constrains) {
        this.constrains = constrains
        this.value = null
    }
}

class FormCollection {
    constructor(className, constrains = []) {
        this.data = []
        this.className = className
        this.constrains = constrains
    }
    createObject(){
        return new this.className(this.constrains)
    }
}


class FormMapper {

    groupToTree(list) {
        list += '[setValue]'

        const root = {};
        const keys = list
            .replace(/\]/g, '')
            .split('[');

        let current = root;

        keys.forEach((key, i) => {
            if (!current[key]) current[key] = {};
            current = current[key];
        });
        return root;
    }

    tree(form, branch, params = null, callback = function(){}){

        if (form instanceof FieldType && branch && typeof branch.setValue === 'object') {
            return callback(form, params)

        }  else if (form instanceof FormAbstract) {
            for (let prop in form) {
                if (branch[prop]) {
                    form[prop] = this.tree(form[prop], branch[prop], params, callback)
                }
            }

        } else if (form instanceof FormCollection) {
            for (let i in branch) {
                if (form.data[i] === undefined) {
                    form.data[i] = form.createObject()
                }
                form.data[i] = this.tree(form.data[i], branch[i], params, callback)
            }
        }
        return form
    }

    setFormData(form, formData){
        for (let prop of formData.keys()){
            let branch = this.groupToTree(prop)

            let params = {
                'value': formData.get(prop),
                'fieldName': prop
            }

            this.tree(form, branch, params, function(field, data){
                field.value = data.value
                field.fieldName = data.fieldName
                return field
            })
        }
        return form
    }

    searchBranch(form, data = null, callback = function(){}){

        if (form instanceof FieldType) {
            return callback(form, data)

        }  else if (form instanceof FormAbstract) {
            for (let prop in form) {
                form[prop] = this.searchBranch(form[prop], data, callback)
            }

        } else if (form instanceof FormCollection) {
            for (let i in form.data) {
                form.data[i] = this.searchBranch(form.data[i], data, callback)
            }
        }
        return form
    }
}


class FormValidator extends FormService {

    validate(form){
        var params = { isValid : true }
        var mapper = new FormMapper()
        mapper.searchBranch(form, params, this.validateField)
        return params.isValid
    }

    validateField(field, data){
        field.error = []
        for (let i in field.constrains) {
            let assert = field.constrains[i]
            if (assert instanceof AssertAbstract === false) {
                console.warn("ValidatorService: assert w constrains jest niepoprawny, pole: " + field.fieldName)
                continue
            }
            if (assert.validate(field.value) === false) {
                field.error.push(assert.getMessage())
                data.isValid = false
            }
        }
        return field
    }
}

/**
 * Form error service - obsluga bledow
 */
class FormErrorAbstract {
    constructor() {
        if (typeof this.clear !== 'function') {
            throw new Error("Class must implement method 'clear'");
        }
        if (typeof this.showErrors !== 'function') {
            throw new Error("Class must implement method 'showErrors'");
        }
    }
}


/**
 * Obsluga bledów w formularzu
 */
class FormErrorService extends FormErrorAbstract {

    clear(formElement){
        let elements = formElement.getElementsByClassName('error-field');
        while(elements.length > 0) {
            elements[0].remove();
        }
    }

    showErrors(form, formElement){
        var mapper = new FormMapper()
        mapper.searchBranch(form, {formElement: formElement}, this.showError)
    }

    showError(field, data){
        
        if (field.error === null || field.error === undefined || field.error.length == 0) {
            return field
        }

        var formElement = data.formElement
        let input = formElement.elements[field.fieldName]

        var error = document.createElement('div')
        error.classList.add('error-field')
        error.style = 'color:red'
        error.setHTMLUnsafe(field.error.join('<br>'))

        if (input.parentNode.getElementsByClassName('error-field').length == 0) {
            input.parentNode.append(error)
        }

        return field
    }
}