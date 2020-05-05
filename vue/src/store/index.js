import Vue from "vue";
import Vuex from "vuex";
import axios from "axios";

//Automatically adds bearer token to all axios requests
axios.default.interceptors.request.use(function (config) {
    config.baseURL = (process.env.NODE_ENV !== 'production') ? 'http://hfq/api/v1' : 'https://hfqserver.com/api/v1'
    const token = localStorage.getItem('token');
    config.headers.Authorization =  token ? `Bearer ${token}` : '';
    return config;
  });

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        token: localStorage.getItem("token") || null,
        exams: [],
        users: [],
        downloads: [],
        uploads: [],
    }, 
    getters: {
        loggedIn: state => state.token !== null,
        exams: state => state.exams,
        users: state => state.users,
        downloads: state => state.downloads,
        uploads: state => state.uploads,
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

        setUsers(state, payload) {
            state.users = payload;
        },

        setDownloads(state, payload) {
            state.downloads = payload;
        },

        setUploads(state, payload) {
            state.uploads = payload;
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

        setUserDeleted(state, id) {
            var MyUser = state.users.findIndex(r => r.id == id);
            if(MyUser >= 0)
                state.users.splice(MyUser, 1);
        },
        
        setUserUpdated(state, user) {
            var MyUser = state.users.find(r => r.id == user.id);

            if(MyUser !== null)
            {
                MyUser.name = user.name;
                MyUser.password = user.password;
                MyUser.type = user.type;
            }
        },
       
        setUserCreated(state, user) {
            state.users.push(user);
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

        fetchGitVersion() {
            return axios
                .get("gitversion")
                .then(response => {
                    return response.data;
                })
                .catch(() => {
                    return '';
                });
        },

        fetchExams(context) {
           
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

        fetchExam(context, exam_id) {
           
            return axios
                .get('exam/' + exam_id)
                .then(response => {
                    return response.data;
                })
                .catch(() => {
                    return null;
                });
        },

        deleteExam(context, id) {
            if (context.getters.loggedIn) {               
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

        fetchExamNames(context) {
            if (context.getters.loggedIn) {
                return axios
                    .get('exam_names')
                    .then(response => {
                        return response;
                    })
                    .catch(err => {
                        if(err.response.status === 403) {
                            alert('Could not fetch list of Master Files from the server.');
                        }

                        throw err;
                    });
            }
        },

        fetchUploadDates(context, exam) {
            if (context.getters.loggedIn) {
                console.log(exam);
                return axios
                    .get('upload/dates/' + exam)
                    .then(response => {
                        return response;
                    })
                    .catch(err => {
                        if(err.response.status === 403) {
                            alert('Could not fetch list of Master Files from the server.');
                        }

                        throw err;
                    });
            }
        },
      
        fetchUsers(context) {
            return axios
                .get("users")
                .then(response => {
                    context.commit("setUsers", response.data.data);
                    return response;
                })
                .catch((err) => {
                    context.commit("setUsers", null);
                    throw err;
                });
        },

        deleteUser(context, id) {
            if (context.getters.loggedIn) {
                return axios
                    .delete('user/' + id)
                    .then(response => {
                        context.commit("setUserDeleted", id);
                        return response;
                    })
                    .catch(err => {
                        if(err.response.status === 403) {
                            alert('This user could not be deleted.');
                        }

                        throw err;
                    });
            }
        },

        updateUser(context, user) {
            if (context.getters.loggedIn) {
                return axios
                    .put('user/' + user.id, user)
                    .then(response => {
                        context.commit("setUserUpdated", user);
                        return response;
                    })
                    .catch(err => {
                        if(err.response.status === 403) {
                            alert('Could not update user on the server.');
                        }

                        throw err;
                    });
            }
        },

        createUser(context, user) {
            if (context.getters.loggedIn) {
                return axios
                    .post('user', user)
                    .then(response => {
                        context.commit("setUserCreated", response.data.data);
                        return response;
                    })
                    .catch(err => {                       
                        if(err.response.status === 403) {
                            alert('Could not create user on the server.');
                        }

                        throw err;
                    });
            }
        },

        uploadFrequency(context, {exam_id, machine_name, freq}) {
            if (context.getters.loggedIn) {
                return axios
                    .post('exam/' + exam_id + '/upload_result', {
                        machine_name: machine_name,
                        result: JSON.stringify(freq),
                    });
            }
        },

        fetchDownloads(context) {
            return axios
                .get("downloads")
                .then(response => {
                    context.commit("setDownloads", response.data.data);
                    return response;
                })
                .catch((err) => {
                    context.commit("setDownloads", null);
                    throw err;
                });
        },
        
        fetchUploads(context) {
            return axios
                .get("uploads")
                .then(response => {
                    context.commit("setUploads", response.data.data);
                    return response;
                })
                .catch((err) => {
                    context.commit("setUploads", null);
                    throw err;
                });
        },

        fetchAccesses(context, examid) {
            return axios
                .get("exam/" + examid + "/accesses")
                .then(response => {
                    return response;
                })
                .catch((err) => {
                    throw err;
                });
        },

        fetchHFQReport(context, params) {
            return axios
                .post("hfqreport", params)
                .then(response => {
                    return response;
                })
                .catch((err) => {
                    throw err;
                });
        },
        
        downloadHFQReport(context, params) {
            return axios
                .post("hfqreportpdf", params)
                .then(response => {
                    return response;
                })
                .catch((err) => {
                    throw err;
                });
        },

        fetchUploadLocations() {
            return axios
                .get("/upload/locations")
                .then(response => {
                    return response;
                })
                .catch((err) => {
                    throw err;
                });
          },
      
          fetchSnapshots(context, download_id) {
            return axios
                .get('/download/' + download_id + '/snapshots')
                .then(response => {
                    return response.data;
                })
                .catch((err) => {
                    throw err;
                });
          },

        updateAccesses(context, accesses) {
            if (context.getters.loggedIn) {
                axios.post('access/update_bulk', accesses)
                    .then(response => {
                        return response;
                    })
                    .catch((err) => {
                        throw err;
                    });                     
            }
        },
    },
    modules: {}
});