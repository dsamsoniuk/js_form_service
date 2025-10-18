
class AssertAbstract {
    /**
     * @property {string} message
     */
    constructor(message) {
        this.message = message
        if (typeof this.validate !== 'function') {
            throw new Error("Class must implement method 'validate'");
        }
    }
    /**
     * @return {string}
     */
    getMessage() {
        return this.message
    }
}


class AssertNotBlank extends AssertAbstract {
    /**
     * @param {string} message 
     */
    constructor(message){
        message = message ?? 'This field can not be empty'
        super(message)
    }
    /**
     * @param {File} value
     * @return {boolean}
     */
    validate(value){
        return value === null || value === '' || value === undefined ? false : true
    }
}


class AssertNumber extends AssertAbstract {
    /**
     * 
     * @param {string} message 
     * @param {number} min 
     * @param {number} max 
     * @param {string} messageMin 
     * @param {string} messageMax 
     */
    constructor(message, min, max, messageMin, messageMax){
        message = message ?? 'This field must be number'
        super(message)
        this.min = min ?? null
        this.messageMin = messageMin ?? 'Number is too small'
        this.max = max ?? null
        this.messageMax = messageMax ?? 'Number is too large'
    }
    /**
     * @param {string} value
     * @return {boolean}
     */
    validate(value){
        if (/^\d+$/.test(value) === false) {
            return false
        }
        if (this.min && this.min > value) {
            this.message = this.messageMin
            return false
        }
        if (this.max && this.max < value) {
            this.message = this.messageMax
            return false
        }
        return true
    }
}
class AssertLength extends AssertAbstract {
    /**
     * 
     * @param {string} message 
     * @param {number} min 
     * @param {number} max 
     * @param {string} messageMin 
     * @param {string} messageMax 
     */
    constructor(min, max, messageMin, messageMax){
        super()
        this.min = min ?? null
        this.messageMin = messageMin ?? 'Text is too small'
        this.max = max ?? null
        this.messageMax = messageMax ?? 'Text is too large'
    }
    /**
     * @param {string} value
     * @return {boolean}
     */
    validate(value){
        if (this.min && this.min > value.length) {
            this.message = this.messageMin
            return false
        }
        if (this.max && this.max < value.length) {
            this.message = this.messageMax
            return false
        }
        return true
    }
}
class AssertEmail extends AssertAbstract {
    /**
     * @param {string} message 
     */
    constructor(message){
        message = message ?? 'Email is incorrect'
        super(message)
    }
    /**
     * @param {string} value
     * @return {boolean}
     */
    validate(value){
       return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
    }
}

class AssertFileRequired extends AssertAbstract {
    /**
     * @param {string} message 
     */
    constructor(message, messageFormat, allowFormats){
        message = message ?? 'This file is required'
        super(message)

        this.messageFormat = messageFormat ?? 'Wrong format file'
        this.allowFormats = allowFormats ?? []
    }
    /**
     * @param {string} value
     * @return {boolean}
     */
    validate(value){
        if (value instanceof File === false) {
            return false;
        }
        let fileExists = value.name != '' ? true : false
        if (fileExists === false) {
            return false
        }
        let allowFormats = (this.allowFormats == [] ? true : (this.allowFormats.includes(value.type) ? true : false))
        if (allowFormats === false) {
            this.message = this.messageFormat
            return false
        }
        return true
    }
}

