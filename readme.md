
# Przykład obsługi formularza po stronei frontend

### Obsluga formularza w vanilly js, walidacja prototypy pol - calosc napisana objektowo 

### Run server:

```
php -S 127.0.0.1:80
```


### Example build form

```js
class AddressForm extends FormAbstract {
    "phone" = new FieldType([new AssertNotBlank()])
    "email" = new FieldType([new AssertNotBlank()])
}
class CustomerForm extends FormAbstract {
    "id" = new FieldType([new AssertNotBlank('Custom error example')])
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
```

### Example how validate form

```js
const formElement = document.querySelector('form')
const formData = new FormData(formElement)

const form = new CustomerFormBuilder()

const formMapper = new FormMapper()
const formError = new FormErrorService()
const formValidator = new FormValidator()

form = formMapper.setFormData(form, formData)

formError.clear(formElement)
    
if (formValidator.validate(form)) {
    console.log('Ok')
    return
} else {
    console.log('Errors')
}

formError.showErrors(form, formElement)
```


### Customr assert create

Required only `extends AssertAbstract`

```js
/** Assert - Not empty field */
class AssertNotBlank extends AssertAbstract {
    /**
     * @param {string} message 
     */
    constructor(message){
        super()
        this.message = message ?? 'This field can not be empty'
    }
    /**
     * @param {string} value
     * @return {boolean}
     */
    validate(value){
        return value === null || value === '' || value === undefined ? false : true
    }
    /**
     * @return {string}
     */
    getMessage() {
        return this.message
    }
}
```