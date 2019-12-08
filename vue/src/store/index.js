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
        
        setExamUpdated(state, exam) {
            var MyExam = state.exams.find(r => r.id == exam.id);
            if(MyExam !== null)
            {
                MyExam.name = exam.name;
                MyExam.qa_count = exam.qa_count;
                MyExam.is_expired = exam.is_expired;
                MyExam.created_at = exam.created_at;
                MyExam.updated_at = exam.updated_at;
                MyExam.xml_file_name = exam.xml_file_name;
                MyExam.xps_file_name = exam.xps_file_name;
            }
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

        updateExam(context, exam) {
            if (context.getters.loggedIn) {
                axios.defaults.headers.common["Authorization"] = "Bearer " + context.state.token;
              
                return axios
                    .put('exam/' + exam.id, exam)
                    .then(response => {
                        context.commit("setExamUpdated", exam);
                        return response;
                    })
                    .catch(err => {
                        if(err.response.status === 403) {
                            alert('Could not update master file on the server.');
                        }

                        throw err;
                    });
            }
        },
    },
    modules: {}
});