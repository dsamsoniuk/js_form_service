export class EventAbstract {
    constructor() {
        if (typeof this.init !== 'function') {
            throw new Error("Class must implement method 'init'");
        }
    }
}
