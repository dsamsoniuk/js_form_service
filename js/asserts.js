
class AssertAbstract {
    /**
     * @property {string} message
     */
    message = ''
    constructor() {
        if (typeof this.validate !== 'function') {
            throw new Error("Class must implement method 'validate'");
        }
        if (typeof this.getMessage !== 'function') {
            throw new Error("Class must implement method 'getMessage'");
        }
    }
}

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

/** Assert - Not empty field */
class AssertFileRequired extends AssertAbstract {
    /**
     * @param {string} message 
     */
    constructor(message, messageFormat, allowFormats){
        super()
        this.message = message ?? 'This file is required'
        this.messageFormat = messageFormat ?? 'Wrong format file, allowed: ' + allowFormats.join(', ')
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
    /**
     * @return {string}
     */
    getMessage() {
        return this.message
    }
}

