
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
    message = 'This field can not be empty'
    /**
     * @param {string} message 
     */
    constructor(message){
        super()
        this.message = message ?? this.message
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

