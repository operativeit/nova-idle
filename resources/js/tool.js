import {createApp,defineComponent, getCurrentInstance } from 'vue';
import renderComponent from './renderComponent'
import IdleReminder from './components/IdleReminder.vue'

Nova.booting((app, store) => {
    window.addEventListener('DOMContentLoaded',()=>{
        let appHeader = document.getElementsByTagName('header');
        let container =  document.createElement('div')


        let idleReminder = renderComponent({
            el: container,
            component: IdleReminder,
            props: {
            },
            context: app._context,
        })

        appHeader[0].parentNode.insertBefore(container,appHeader[0]);

    })
})
