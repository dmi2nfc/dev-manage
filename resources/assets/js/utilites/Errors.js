class Errors {
    constructor() {
      this.errors = {};
    }
  
    /**
     * Retrieve the errors message for a field
     *
     * @param {string} field
     */
  
    get(field) {
      if (this.errors[field]) {
        return this.errors[field][0];
      }
    }
  
    /**
     * Record the new errors
     *
     * @param {Object} errors
     */
  
    record(errors) {
      this.errors = errors;
    }
  
    /**
     * Clear one or old error fields
     *
     * @param {string|null} field
     */
  
    clear(field) {
      if (field) {
        delete this.errors[field];
        return;
      }
  
      this.errors = {};
    }
  
    /**
     * Determine if the errors exists for giver field
     *
     * @param {string} field
     */
  
    has(field) {
      return this.errors.hasOwnProperty(field);
    }
  
    /**
     * Determine if we have any errors
     */
  
    any() {
      return Object.keys(this.errors).length > 0;
    }
  }

  export default Errors;