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

        setExamDeleted(state, id) {
            var MyExam = state.exams.findIndex(r => r.id == id);
            if(MyExam >= 0)
                state.exams.splice(MyExam , 1);
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

        deleteExam(context, id) {
            if (context.getters.loggedIn) {
                axios.defaults.headers.common["Authorization"] = "Bearer " + context.state.token;
                
                return axios
                    .delete('exam/' + id)
                    .then(response => {
                        context.commit("setExamDeleted", id);
                        return response;
                    })
                    .catch(err => {
                        if(err.response.status === 403) {
                            alert('This master file could not be deleted.');
                        }

                        throw err;
                    });
            }
        },
    },
    modules: {}
});