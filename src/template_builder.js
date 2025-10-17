

class TemplateServiceAbstract {
    constructor() {
        if (typeof this.render !== 'function') {
            throw new Error("Class must implement method 'render'");
        }
    }
}

/**
 * Generator szablonu na podstawie elementu doc oraz nadpisanie parametrow
 */
class TemplateService extends TemplateServiceAbstract {

    /**
     * Szukaj po nazwie z klamrami, przyklad {{name}} bedzie zastapiony przez wartosc np. 13
     * @param {string} key 
     * @returns 
     */
    search(key){
        return `{+${key}+}`
    }

    /**
     * @param {object} root 
     * @param {object} data 
     * @returns 
     */
    replacePlaceholders(root, data) {
        const elements = root.querySelectorAll('*');
        elements.forEach(el => {
            // Podmiana w atrybutach
            for (const attr of el.attributes) {
                const oldValue = attr.value;
                let newValue = oldValue;

                for (const [key, val] of Object.entries(data)) {
                    newValue = newValue.replaceAll(this.search(key), val);
                }

                if (newValue !== oldValue) {
                    el.setAttribute(attr.name, newValue);
                }
            }
            // Podmiana w tekście elementu
            if (el.childNodes.length === 1 && el.childNodes[0].nodeType === Node.TEXT_NODE) {
                let text = el.textContent;
                for (const [key, val] of Object.entries(data)) {
                    text = text.replaceAll(this.search(key), val);
                }
                el.textContent = text;
            }
        });

        return root
    }
    setPrototype(prototype){
        this.template = prototype.cloneNode(true)
        this.template.style = ''
        prototype.remove()
    }
    cloneTemplate(){
        return this.template.cloneNode(true)
    }
    /**
     * Nadpisz placeholdery wartosciami
     * @param {object} data 
     * @returns {object}
     */
    render(data) {
        return this.replacePlaceholders(this.cloneTemplate(), data)
    }
}


