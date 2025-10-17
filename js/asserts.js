
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

/** Assert - Not empty field */
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

/** Assert - Not empty field */
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

