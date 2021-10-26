import Vue from 'vue/dist/vue.min.js';

let focusTrap = false;

let App = new Vue({
    data() {
        return {
            activeTab: 1,
            editMode: false,
            hasForms: false,
            loaded: false,
            widgets: [],
            pid: null,
            hasWidgetEditor: false,
            currentWidget: {}
        }
    },
    watch: {
        editMode(s) {
            if(!s && this.hasWidgetEditor) {
                this.hasWidgetEditor = false;
                this.currentWidget = {}
            }
            if(s) {      
                this.$refs.modal.focus();          
                focusTrap = true;
            } else {
                focusTrap = false;
            }
        }
    },
    mounted() {       
        let el = this.$refs['storage'];      
        this.hasForms = el.dataset.hasforms == 'true';
        this.pid = el.dataset.pid;
        this.$el.parentNode.setAttribute('dir', 'ltr');
        this.searchWidgets();
        this.$refs.container.removeAttribute('hidden');
        // Serach of widgets
    },
    computed: {
        getIframeFormsUrl() {
            return  '/wtp-acf-fasteditor/' + this.pid + '/all'
        },
        currentWidgetUrl() {
            return  '/wtp-acf-fasteditor/' + this.currentWidget.post_id + '/' + this.currentWidget.field
        },
    },
    methods: {
        getCLoseElement() {
            return this.$refs.close;
        },
        addWidget(el) {
            if(el.classList.contains('acf_fasteditor_included')) {
                return
            }
            if(!el.dataset.pid) {
                return
            }    
    
            if(window.getComputedStyle(el).position == 'static') {
                el.style.position = 'relative';
            } 
            let widget = {
                id: '_' + Math.random().toString(36).substr(2, 9),
                post_id: el.dataset.pid,
                title: el.dataset.title ? el.dataset.title : 'No name',
                field: el.dataset['acf-field'] ? el.dataset['acf-field'] : 'all',
                el: el
            }
            let editLink = document.createElement('button');
            editLink.classList.add('app-acf-fasteditor-editlink');
            editLink.innerHTML = '✎ <span class="app-acf-fasteditor-sr-only">Edit this widget</span>';
            editLink.addEventListener('click', (e) => {
                e.preventDefault();
                this.editMode = true;
                this.currentWidget = widget;
                this.hasWidgetEditor = true;
            });
            el.appendChild(editLink);
            el.classList.add('acf_fasteditor_included');
  
            this.widgets.push(widget);
        },
        removeWidget(el) {
            let found = this.widgets.find(el => el.el == el);
            if(found) {
                this.widgets.splice(this.widgets.indexOf(found), 1);
            }
        },
        searchWidgets() {
            let selector = '.mixcode_widget[data-pid], .acf-widget[data-pid], .acf_widget[data-pid]';
            document.querySelectorAll(selector).forEach(el => {
              this.addWidget(el);
            });
        },
        message_height(data) {
           
            this.$el.querySelector('#' + data.id).style.height = data.height + 'px';
        },
        widgetIframeURL(el) {
            return  '/wtp-acf-fasteditor/' + el.post_id + '/' + el.field
        },
        listener(data) {
            
            try {
                data = JSON.parse(data.data);           
                if(data.wtp) {
                    data = data.wtp;
                }
                if(!this['message_' + data.event]) {
                    return
                } 
                this['message_' + data.event].call(this, data);
            } catch(e) {
        
            }
        },
        onEditMode() {
            this.editMode = true;
        },
        onIframeLoad(event) {
            let id = '_' + Math.random().toString(36).substr(2, 9);
            event.target.setAttribute('id', id);   
            event.target.contentWindow.postMessage(JSON.stringify({
                wtp: {
                    event: 'init',
                    id: id
                }
            }), '*');
        }
    }
}).$mount('#app_acf_fasteditor');


document.addEventListener('keydown', function(e) {

    var isTabPressed = (e.key === 'Tab' || e.keyCode == 9);
    if(isTabPressed && focusTrap) {
        let activeEl = document.activeElement;
        if(!App.$el.contains(activeEl) && App.$el != activeEl) {
            e.preventDefault();
            if(e.shiftKey) {
                App.$refs.cancel.focus();
            } else {
                App.$refs.modal.focus();
            }          
        }
    }
});

window.addEventListener('message', App.listener);