(function(t){function e(e){for(var o,s,i=e[0],l=e[1],c=e[2],u=0,f=[];u<i.length;u++)s=i[u],Object.prototype.hasOwnProperty.call(r,s)&&r[s]&&f.push(r[s][0]),r[s]=0;for(o in l)Object.prototype.hasOwnProperty.call(l,o)&&(t[o]=l[o]);d&&d(e);while(f.length)f.shift()();return a.push.apply(a,c||[]),n()}function n(){for(var t,e=0;e<a.length;e++){for(var n=a[e],o=!0,s=1;s<n.length;s++){var l=n[s];0!==r[l]&&(o=!1)}o&&(a.splice(e--,1),t=i(i.s=n[0]))}return t}var o={},r={app:0},a=[];function s(t){return i.p+"js/"+({about:"about"}[t]||t)+"."+{about:"af3dcd43"}[t]+".js"}function i(e){if(o[e])return o[e].exports;var n=o[e]={i:e,l:!1,exports:{}};return t[e].call(n.exports,n,n.exports,i),n.l=!0,n.exports}i.e=function(t){var e=[],n=r[t];if(0!==n)if(n)e.push(n[2]);else{var o=new Promise((function(e,o){n=r[t]=[e,o]}));e.push(n[2]=o);var a,l=document.createElement("script");l.charset="utf-8",l.timeout=120,i.nc&&l.setAttribute("nonce",i.nc),l.src=s(t);var c=new Error;a=function(e){l.onerror=l.onload=null,clearTimeout(u);var n=r[t];if(0!==n){if(n){var o=e&&("load"===e.type?"missing":e.type),a=e&&e.target&&e.target.src;c.message="Loading chunk "+t+" failed.\n("+o+": "+a+")",c.name="ChunkLoadError",c.type=o,c.request=a,n[1](c)}r[t]=void 0}};var u=setTimeout((function(){a({type:"timeout",target:l})}),12e4);l.onerror=l.onload=a,document.head.appendChild(l)}return Promise.all(e)},i.m=t,i.c=o,i.d=function(t,e,n){i.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:n})},i.r=function(t){"undefined"!==typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},i.t=function(t,e){if(1&e&&(t=i(t)),8&e)return t;if(4&e&&"object"===typeof t&&t&&t.__esModule)return t;var n=Object.create(null);if(i.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var o in t)i.d(n,o,function(e){return t[e]}.bind(null,o));return n},i.n=function(t){var e=t&&t.__esModule?function(){return t["default"]}:function(){return t};return i.d(e,"a",e),e},i.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},i.p="/",i.oe=function(t){throw console.error(t),t};var l=window["webpackJsonp"]=window["webpackJsonp"]||[],c=l.push.bind(l);l.push=e,l=l.slice();for(var u=0;u<l.length;u++)e(l[u]);var d=c;a.push([0,"chunk-vendors"]),n()})({0:function(t,e,n){t.exports=n("8a87")},"1e0a":function(t,e,n){"use strict";var o=n("d6a0"),r=n("89b0"),a=n("aeec"),s=Object(a["a"])(r["default"],o["a"],o["b"],!1,null,null,null);e["default"]=s.exports},"89b0":function(t,e,n){"use strict";var o=n("bda1"),r=n.n(o);e["default"]=r.a},"8a87":function(t,e,n){"use strict";n.r(e);var o=n("6222"),r=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-app",[n("v-app-bar",{attrs:{app:"",color:"primary",dark:""}},[n("span",{staticClass:"mr-5"},[t._v("HFQ SERVER")]),n("v-btn",{staticClass:"ml-5",attrs:{to:"/",text:""}},[t._v("Home")]),n("v-btn",{attrs:{to:"/about",text:""}},[t._v("About")]),n("v-spacer"),n("v-btn",{attrs:{to:"/login",text:""}},[t._v("Login")])],1),n("v-content",[n("v-container",{attrs:{fluid:"","fill-height":""}},[n("router-view")],1)],1),n("confirm-dialog",{ref:"confirm"})],1)},a=[],s=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-dialog",{style:{zIndex:t.options.zIndex},attrs:{"max-width":t.options.width},on:{keydown:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"esc",27,e.key,["Esc","Escape"])?null:t.cancel(e)}},model:{value:t.show,callback:function(e){t.show=e},expression:"show"}},[n("v-card",[n("v-toolbar",{attrs:{dark:"",color:t.options.color,dense:"",flat:""}},[n("v-toolbar-title",{staticClass:"white--text"},[t._v(t._s(t.title))])],1),n("v-layout",{attrs:{wrap:""}},[n("v-flex",{attrs:{xs2:"",right:""}},[n("v-icon",{staticClass:"pa-4",attrs:{large:"",color:t.options.color}},[t._v(t._s(t.options.icon))])],1),n("v-flex",{attrs:{xs10:""}},[n("v-card-text",{directives:[{name:"show",rawName:"v-show",value:!!t.message,expression:"!!message"}],staticClass:"pa-4"},[t.message!==Object(t.message)?n("span",[t._v(t._s(t.message))]):[1==Object.keys(t.message).length?n("span",[t._v(t._s(Object.values(t.message)[0][0]))]):n("ul",[t._l(t.message,(function(e){return t._l(e,(function(e){return n("li",{key:e},[t._v(t._s(e))])}))}))],2)]],2)],1)],1),n("v-card-actions",{staticClass:"pt-0"},[n("v-spacer"),n("v-btn",{attrs:{color:"primary darken-1",text:""},nativeOn:{click:function(e){return t.agree(e)}}},[t._v("OK")]),n("v-btn",{directives:[{name:"show",rawName:"v-show",value:t.options.show_cancel,expression:"options.show_cancel"}],attrs:{color:"grey",text:""},nativeOn:{click:function(e){return t.cancel(e)}}},[t._v("Cancel")])],1)],1)],1)},i=[],l=(n("7a69"),{data:function(){return{show:!1,resolve:null,reject:null,message:null,title:null,options:{color:"primary",icon:"mdi-help-circle",width:350,zIndex:200,show_cancel:!0}}},methods:{open:function(t,e,n){var o=this;return this.show=!0,this.title=t,this.message=e,this.options=Object.assign(this.options,n),new Promise((function(t,e){o.resolve=t,o.reject=e}))},agree:function(){this.resolve(!0),this.show=!1},cancel:function(){this.resolve(!1),this.show=!1}}}),c=l,u=n("aeec"),d=n("e380"),f=n.n(d),p=n("7714"),m=n("6d16"),v=n("4092"),h=n("5bbe"),g=n("e406"),b=n("0447"),w=n("d3a1"),y=n("cbb6"),_=n("f329"),x=n("2e09"),k=Object(u["a"])(c,s,i,!1,null,null,null),V=k.exports;f()(k,{VBtn:p["a"],VCard:m["a"],VCardActions:v["a"],VCardText:v["b"],VDialog:h["a"],VFlex:g["a"],VIcon:b["a"],VLayout:w["a"],VSpacer:y["a"],VToolbar:_["a"],VToolbarTitle:x["a"]});var C={name:"App",components:{ConfirmDialog:V},mounted:function(){this.$root.$confirm=this.$refs.confirm}},O=C,j=n("9d7b"),E=n("578a"),P=n("69a7"),$=n("9d15"),L=Object(u["a"])(O,r,a,!1,null,null,null),S=L.exports;f()(L,{VApp:j["a"],VAppBar:E["a"],VBtn:p["a"],VContainer:P["a"],VContent:$["a"],VSpacer:y["a"]});var I=n("47f0");o["a"].use(I["a"]);var A=new I["a"]({}),T=n("5b73"),D=n("1e0a"),F=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-layout",{attrs:{wrap:"","align-center":"","justify-center":""}},[n("v-flex",{attrs:{lg3:"",md6:"",sm8:"",xs12:""}},[n("v-form",{ref:"form",attrs:{action:"#"},on:{submit:function(e){return e.preventDefault(),t.login(e)}}},[n("v-row",{staticClass:"justify-center mb-5"},[n("h1",{staticClass:"text-center"},[t._v("HFQ Admin Login")])]),n("v-text-field",{ref:"txtEmail",attrs:{label:"Email",name:"email","prepend-icon":"mdi-at",type:"text",autofocus:"",rules:[t.rules.required,t.rules.email],"validate-on-blur":""},model:{value:t.email,callback:function(e){t.email=e},expression:"email"}}),n("v-text-field",{attrs:{id:"password",label:"Password",name:"password","prepend-icon":"mdi-lock",type:"password",rules:[t.rules.required,t.rules.password],"validate-on-blur":""},on:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.login(e)}},model:{value:t.password,callback:function(e){t.password=e},expression:"password"}}),n("v-row",{staticClass:"mt-5"},[n("div",{staticClass:"flex-grow-1"}),n("v-btn",{attrs:{color:"primary"},on:{click:function(e){return e.stopPropagation(),t.login(e)}}},[t._v("Login")])],1)],1),t.logging_in?n("v-progress-circular",{attrs:{indeterminate:"",dark:""}}):t._e()],1)],1)},q=[],B={name:"Login",data:function(){return{email:"",password:"",logging_in:!1,rules:{required:function(t){return!!t||"Required."},email:[function(t){return!!t||"E-mail is required"},function(t){return/.+@.+\..+/.test(t)||"E-mail must be valid"}],password:function(t){var e=/^[\w\d]{6,15}$/;return e.test(t)||"Password must be 8 to 15 characters long. Alphabet and digits only."}}}},methods:{login:function(){var t=this;this.$refs.form.validate()&&(this.logging_in=!0,this.$store.dispatch("login",{email:this.email,password:this.password}).then((function(){t.show=!1})).catch((function(e){var n;n=401==e.response.status?"Invalid e-mail and/or password":"An error occurred. Check your Internet connection.",t.$root.$confirm.open("Error",n,{color:"red",show_cancel:!1,icon:"mdi-alert-circle"})})))}}},H=B,z=n("7063"),M=n("f26d"),R=n("bffb"),Q=n("2ee8"),J=Object(u["a"])(H,F,q,!1,null,null,null),N=J.exports;f()(J,{VBtn:p["a"],VFlex:g["a"],VForm:z["a"],VLayout:w["a"],VProgressCircular:M["a"],VRow:R["a"],VTextField:Q["a"]}),o["a"].use(T["a"]);var K=[{path:"/",name:"home",component:D["default"]},{path:"/login",name:"login",component:N},{path:"/about",name:"about",component:function(){return n.e("about").then(n.bind(null,"3fd1"))}}],G=new T["a"]({routes:K}),U=G,W=n("5210"),X=n("4353"),Y=n.n(X);o["a"].use(W["a"]);var Z=new W["a"].Store({state:{token:localStorage.getItem("token")||null},getters:{loggedIn:function(t){return null!==t.token}},mutations:{setLoginData:function(t,e){null===e?(t.token=null,localStorage.removeItem("token")):(t.token=e.token.access_token,localStorage.setItem("token",e.token.access_token))}},actions:{logout:function(t){if(Y.a.defaults.headers.common["Authorization"]="Bearer "+t.state.token,t.getters.loggedIn)return Y.a.post("logout").then((function(e){return t.commit("setLoginData",null),e})).catch((function(e){throw t.commit("setLoginData",null),e}))},login:function(t,e){return Y.a.post("login",{email:e.email,password:e.password}).then((function(e){return t.commit("setLoginData",e.data),e})).catch((function(e){throw t.commit("setLoginData",null),e}))}},modules:{}});o["a"].config.productionTip=!1,new o["a"]({vuetify:A,router:U,store:Z,render:function(t){return t(S)}}).$mount("#app")},bda1:function(t,e){},d6a0:function(t,e,n){"use strict";var o=function(){var t=this,e=t.$createElement;t._self._c;return t._m(0)},r=[function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"home"},[n("h1",[t._v("HFQ Home Page")])])}];n.d(e,"a",(function(){return o})),n.d(e,"b",(function(){return r}))}});
//# sourceMappingURL=app.c5590271.js.map