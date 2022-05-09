/**
 * Main imports.
 */
import Vue from 'vue/dist/vue.js';
import './bootstrap';
import * as VeeValidate from 'vee-validate';

/**
 * Vue prototype.
 */
Vue.prototype.$http = axios;

/**
 * Window assignation.
 */
window.Vue = Vue;
window.eventBus = new Vue();
window.VeeValidate = VeeValidate;
window.$ = window.jQuery = require('jquery');

Vue.use(VeeValidate);

$(function() {
   let app = new Vue({
       el: '#app',

       data: {
       },

        mounted() {
            this.addServerErrors();

            this.addFlashMessages();
        },

        methods: {
            onSubmit: function(e) {
                this.toggleButtonDisable(true);

                this.$validator.validateAll().then(result => {
                    if (result) {
                        e.target.submit();
                    } else {
                        this.toggleButtonDisable(false);

                        eventBus.$emit('onFormError');
                    }
                });
            },

            toggleButtonDisable: function(value) {
                let buttons = document.getElementsByTagName('button');

                for (let i = 0; i < buttons.length; i++) {
                    buttons[i].disabled = value;
                }
            },

            addServerErrors: function(scope = null) {
                for (let key in serverErrors) {
                    let inputNames = [];

                    key.split('.').forEach(function(chunk, index) {
                        if (index) {
                            inputNames.push('[' + chunk + ']');
                        } else {
                            inputNames.push(chunk);
                        }
                    });

                    let inputName = inputNames.join('');

                    const field = this.$validator.fields.find({
                        name: inputName,
                        scope: scope
                    });

                    if (field) {
                        this.$validator.errors.add({
                            id: field.id,
                            field: inputName,
                            msg: serverErrors[key][0],
                            scope: scope
                        });
                    }
                }
            },

            addFlashMessages: function() {
                if (typeof flashMessages == 'undefined') {
                    return;
                }

                let flashes = [];

                flashMessages.forEach(function(flash) {
                    flashes.push(flash);
                }, this);
            },

            enableReload: function (interval = 700) {
                    setTimeout(function() {
                        window.location.reload(true);
                    }, interval);
            },

            enableRedirection: function (response, interval = 700) {
                    url = response.data.redirect;

                    setTimeout(function() {
                        window.location = url;
                    }, interval);
            },
        }
   });
});

// import FlashWrapper from "./components/flash-wrapper";
// import Flash from "./components/flash";
