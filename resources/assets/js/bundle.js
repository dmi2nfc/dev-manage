let source = {
    user: {
        name: 'John Wayne'
    }
}

Vue.component('coupon',{
    props: ['value'],
    template: `
        <input type="text" :value="value" @input="updateCode($event.target.value)" ref="input" />
    `,

    data(){
        return {
            invalids: ['ALLFREE', 'SOMETHINGELSE']
        }
    },    
    methods: {
        updateCode(code){

            if(this.invalids.includes(code)) {
                alert('This code in no more valid. Sorry.');

                this.$refs.input.value = code = '';
            }

            this.$emit('input', code);
        }
    }
});

new Vue({
    el: '#one',

    data: {
        shared: source,
        coupon: 'Coupon',
    }
});

new Vue({
    el: '#two',

    data: {
        shared: source,
        coupon: 'Coupon1',
    }
});