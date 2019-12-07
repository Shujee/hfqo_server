import Vue from "vue";
import Vuex from "vuex";
import axios from "axios";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        token: localStorage.getItem("token") || null,
        exams: [],
    },
    getters: {
        loggedIn: state => state.token !== null,
        exams: state => state.exams,
    },
    mutations: {
        setLoginData(state, login_data) {
            if (login_data === null) {
                state.token = null;
                localStorage.removeItem("token");
            } else {
                state.token = login_data.token.access_token;
                localStorage.setItem("token", login_data.token.access_token);
            }
        },

        setExams(state, payload) {
            state.exams = payload;
        },

        setReqDeleted(state, id) {
            var MyReq = state.exams.findIndex(r => r.id == id);
            if(MyReq >= 0)
                state.exams.splice(MyReq , 1 );
        },

        setReqPosted(state, newreq) {
            state.exams.push(newreq);
            state.user.req_balance--;
        },
    },
    actions: {
        logout(context) {
            if (context.getters.loggedIn) {
                return axios
                    .post("logout")
                    .then(response => {
                        context.commit("setLoginData", null);
                        return response;
                    })
                    .catch(err => {
                        context.commit("setLoginData", null);
                        throw err;
                    });
            }
        },

        login(context, credentials) {
            return axios
                .post("login", {
                    email: credentials.email,
                    password: credentials.password
                })
                .then(response => {
                    context.commit("setLoginData", response.data);
                    return response;
                })
                .catch(err => {
                    context.commit("setLoginData", null);
                    throw err;
                });
        },

        fetchExams(context) {
            axios.defaults.headers.common["Authorization"] = "Bearer " + this.state.token;

            return axios
                .get("exams")
                .then(response => {
                    context.commit("setExams", response.data.data);
                    return response;
                })
                .catch((err) => {
                    context.commit("setExams", null);
                    throw err;
                });
        },
    },
    modules: {}
});