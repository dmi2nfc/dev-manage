import Errors from './Errors';

class Form {
    /**
     * Create a new Form instance
     *
     * @param {object} data
     */

    constructor(data) {
      this.originalData = data;

      for (let field in data) {
        this[field] = data[field].value;
      }

      this.errors = new Errors();
    }

    /**
     * Fetch all relevant data for the form
     */

    data() {
      let data = {};

      for (let property in this.originalData) {
        data[property] = this[property];
      }

      return data;
    }

    /**
     * Reset the form fields
     */

    reset() {
      for (let field in this.originalData) {
        if(typeof this.originalData[field].default != 'undefined') {
          // debugger;
          this[field] = this.originalData[field].default;
        }
      }

      this.errors.clear();
    }

    /**
     * Submit post query
     */
    post(url){
      return this.submit('POST', url);
    }

    /**
     * Submit delete query
     */
    delete(url){
      return this.submit('DELETE', url);
    }

    /**
     * Submit the form
     *
     * @param {string} requestType
     * @param {url} url
     */

    submit(requestType, url) {
      return new Promise((resolve, reject) => {
        axios[requestType.toLowerCase()](url, this.data())
          .then(
            response => {
              this.onSuccess(response.data);
              resolve(response.data);
            },
            error => {
              this.onFail(error.response.data);
              reject(error.response.data);
            }
          );
      });
    }

    /**
     * Handle a success form submission
     *
     * @param {object} data
     */

    onSuccess(data) {
      this.reset();
    }

    /**
     * Handle a failded form submission
     *
     * @param {object} errors
     */

    onFail({ errors }) {
      this.errors.record(errors);
    }
  }

  export default Form;