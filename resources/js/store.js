import {defineStore} from 'pinia'
import {get, remove, set} from "./utils/localStorage";
import {post} from "./utils/fetchAPI";
import router from "./routes";

export const useAppStore = defineStore('app', {
    state: () => {
        return {
            isLoggedIn: get('token') || null,
            user: get('user') || null,
            loginForm: {
                email: 'test@example.com',
                password: 'password',
            },
            registerForm: {
                name: '',
                email: '',
                password: '',
            },
            errors: []
        }
    },
    getters: {
        getError: (state) => (key) => {
            return state.errors[key]?.[0] ?? null;
        },
    },
    actions: {
        async signIn() {
            await post('/login', this.loginForm)
                .then(res => {
                    const {token, user} = res?.data?.data
                    set('token', token)
                    set('user', user)
                    this.user = user;
                    this.isLoggedIn = token
                    router.push('/')
                }).catch(errors => {
                    this.errors = errors?.response?.data?.errors || errors?.response?.data;
                })
        },
        async signUp() {
            await post('/register', this.registerForm)
                .then(res => {
                    const {token, user} = res?.data?.data
                    set('token', token)
                    set('user', user)
                    this.isLoggedIn = token
                    this.registerForm = {}
                    router.push('/')
                }).catch(errors => {
                    console.log(errors)
                    this.errors = errors?.response?.data?.errors || errors?.response?.data;
                })
        },
        async logout() {
            await post('logout').then(_ => {
                remove('token')
                remove('user')
                this.isLoggedIn = null;
            })

        }
    }
})
