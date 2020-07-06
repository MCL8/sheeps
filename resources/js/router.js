import VueRouter from 'vue-router';
import SheepsForm from "./components/SheepsForm";
import Report from "./components/Report";

export default new VueRouter({
    routes : [
        {
            path: '/',
            component: SheepsForm
        },
        {
            path: '/report',
            component: Report
        }
    ],
    mode: 'history'
});
