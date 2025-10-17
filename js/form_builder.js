
class FormAbstract {}

class FieldType { 
    /**
     * @param {array} constrains - assercje
     */
    constructor(constrains) {
        this.constrains = constrains
        this.value = null
    }
}

class FormCollection {
    /**
     * @param {string} className 
     * @param {array} constrains 
     */
    constructor(className, constrains = []) {
        this.data = []
        this.className = className
        this.constrains = constrains
    }
    /**
     * Stworz objekt i asercje na podstawie nazwy klasy
     * @returns {object}
     */
    createObject(){
        return new this.className(this.constrains)
    }
}

/**
 * Service mapujacy - buduje formularz i przypisuje dane
 */
class FormMapper {
    /**
     * Budowanie drzewa formularza (FormAbstract) na podstawie gałęzi (np. customer[id])
     * @param {FormAbstract} form 
     * @param {object} branch 
     * @param {array} params 
     * @param {function} callback 
     * @returns {FormAbstract}
     */
    searchTree(form, branch, params = null, callback = function(){}){

        if (form instanceof FieldType) {
            return callback(form, params)

        }  else if (form instanceof FormAbstract) {
            for (let prop in form) {
                if (branch[prop]) {
                    params.stepsIndex += this.buildStepIndex(params.stepsIndex, prop)
                    form[prop] = this.searchTree(form[prop], branch[prop], params, callback)
                }
            }

        } else if (form instanceof FormCollection) {
            for (let i in branch) {
                if (form.data[i] === undefined) {
                    form.data[i] = form.createObject()
                }
                params.stepsIndex += this.buildStepIndex(params.stepsIndex, i)
                form.data[i] = this.searchTree(form.data[i], branch[i], params, callback)
            }
        }
        return form
    }
    /**
     * Przypisz wartosci z FormData do formularza (FormAbstract)
     * @param {FormAbstract} form 
     * @param {FormData} formData 
     * @returns {FormAbstract}
     */
    setFormData(form, formData){

        for (let prop of formData.keys()){
            let branch = this.groupToTreeString(prop)
            let params = {
                'value': formData.get(prop),
                'fieldName': prop,
                'stepsIndex': '',
            }
            form = this.searchTree(form, branch, params, function(field, data){
                if (params.stepsIndex === params.fieldName) {
                    field.value = data.value
                    field.fieldName = data.fieldName
                }
                return field
            })
        }
        return form
    }
    /**
     * Podziel string na drzewo objektow 
     * @param {string} list 
     * @returns {object}
     */
    groupToTreeString(list) {
        const root = {};
        const keys = list.replace(/\]/g, '').split('[');
        let current = root;

        keys.forEach((key, i) => {
            if (!current[key]) {
                current[key] = {};
            }
            current = current[key];
        });
        return root;
    }
    /**
     * Budowanie indexu pola
     * @param {string} stepsIndex 
     * @param {string} name 
     * @returns {string}
     */
    buildStepIndex(stepsIndex, name){
        return stepsIndex == '' ? name : '[' + name +']'
    }
    /**
     * Wyszukiwanie wszystkich pol (FieldType) w drzewie formularza
     * @param {FormAbstract} form 
     * @param {array} data 
     * @param {function} callback 
     * @returns {FormAbstract}
     */
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

/**
 * Service form - walidacja formularza
 */
class FormValidator {
    /**
     * Sprawdza czy formularz waliduje i dodaje bledy z assercji
     * @param {FormAbstract} form 
     * @returns {boolean}
     */
    validate(form){
        var params = { isValid : true }
        var mapper = new FormMapper()
        mapper.searchBranch(form, params, this.validateField)
        return params.isValid
    }
    /**
     * Dodaje blędy z asercji dla pola
     * @param {FieldType} field 
     * @param {array} data 
     * @returns {FieldType}
     */
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
 * Service form error - wyswietla bledy w html-u
 */
class FormErrorService extends FormErrorAbstract {
    /**
     * Usuń w html-u elementy z błędami
     * @param {document} formElement 
     */
    clear(formElement){
        let elements = formElement.getElementsByClassName('error-field');
        while(elements.length > 0) {
            elements[0].remove();
        }
    }
    /**
     * Wyswietl bledy w html-u dla wszystkich pol 
     * @param {FormAbstract} form 
     * @param {document} formElement 
     */
    showErrors(form, formElement){
        var mapper = new FormMapper()
        mapper.searchBranch(form, {formElement: formElement}, this.showError)
    }
    /**
     * Private - wyswietl błąd pola w html-u
     * @param {*} field 
     * @param {*} data 
     * @returns {FieldType}
     */
    showError(field, data){
        
        if (field.error === undefined || field.error === null || field.error.length == 0) {
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