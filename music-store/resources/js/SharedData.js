import { reactive } from "vue";

export const user = reactive({
    user: null,
    token: null,

    login(token, user) {
        this.token = token;
        this.user = user;

        localStorage.setItem('token', token);
        localStorage.setItem('user', JSON.stringify(user));
    },

    logout() {
        this.token = null;
        this.user = null;

        localStorage.removeItem('token');
        localStorage.removeItem('user');
    },

    isLoggedIn() {
        return this.token !== null;
    }
});

if(localStorage.getItem('token')) {
    user.token = localStorage.getItem('token');
    user.user = JSON.parse(localStorage.getItem('user'));
}