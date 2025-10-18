
# Library js form builder with validator and cloning parts

### Obsluga formularza w vanilly js, walidacja prototypy pol - calosc napisana objektowo 

Example how it works, you can see in index.php file.

### Run simple server:

```
php -S 127.0.0.1:80
```

### Packing

Minification of file index.package.js to dist/js-form-service.min.js

```
npm run build
```

Concat files src/ to index.package.js 

```
npm run concat
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
class CustomerFormBuild extends FormAbstract {
    "customer" = new CustomerForm()
}
```

### Example how validate form

```js
const formElement = document.querySelector('form')
const formData = new FormData(formElement)

const form = new CustomerFormBuild()

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


### Custom assert create

Required only `extends AssertAbstract`

```js
class AssertNotBlank extends AssertAbstract {
    /**
     * @param {string} message 
     */
    constructor(message){
        message = message ?? 'This field can not be empty'
        super(message)
    }
    /**
     * @param {string} value
     * @return {boolean}
     */
    validate(value){
        return value === null || value === '' || value === undefined ? false : true
    }
}
```
