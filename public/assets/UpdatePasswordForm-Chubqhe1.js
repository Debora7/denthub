import{r as c,T as _,o as i,f as m,b as o,a as e,u as r,w as f,A as v,e as g,d as y,g as V}from"./app-m8O9x8db.js";import{_ as l}from"./InputError-mYeCkuLg.js";import{_ as n}from"./InputLabel-vZ1nCw19.js";import{P as x}from"./PrimaryButton-D-IF3Vpv.js";import{_ as u}from"./TextInput-CdSXe7W9.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const b=o("header",null,[o("h2",{class:"text-lg font-medium text-gray-900"},"Schimbă parola"),o("p",{class:"mt-1 text-sm text-gray-600"}," Asigură-te că folosești o parolă lungă și puternică. ")],-1),k={class:"flex items-center gap-4"},P={key:0,class:"text-sm text-gray-600"},$={__name:"UpdatePasswordForm",setup(S){const d=c(null),p=c(null),s=_({current_password:"",password:"",password_confirmation:""}),w=()=>{s.put(route("password.update"),{preserveScroll:!0,onSuccess:()=>s.reset(),onError:()=>{s.errors.password&&(s.reset("password","password_confirmation"),d.value.focus()),s.errors.current_password&&(s.reset("current_password"),p.value.focus())}})};return(N,a)=>(i(),m("section",null,[b,o("form",{onSubmit:g(w,["prevent"]),class:"mt-6 space-y-6"},[o("div",null,[e(n,{for:"current_password",value:"Parola curentă"}),e(u,{id:"current_password",ref_key:"currentPasswordInput",ref:p,modelValue:r(s).current_password,"onUpdate:modelValue":a[0]||(a[0]=t=>r(s).current_password=t),type:"password",class:"mt-1 block w-full",autocomplete:"current-password"},null,8,["modelValue"]),e(l,{message:r(s).errors.current_password,class:"mt-2"},null,8,["message"])]),o("div",null,[e(n,{for:"password",value:"Noua parolă"}),e(u,{id:"password",ref_key:"passwordInput",ref:d,modelValue:r(s).password,"onUpdate:modelValue":a[1]||(a[1]=t=>r(s).password=t),type:"password",class:"mt-1 block w-full",autocomplete:"new-password"},null,8,["modelValue"]),e(l,{message:r(s).errors.password,class:"mt-2"},null,8,["message"])]),o("div",null,[e(n,{for:"password_confirmation",value:"Confirmă noua parolă"}),e(u,{id:"password_confirmation",modelValue:r(s).password_confirmation,"onUpdate:modelValue":a[2]||(a[2]=t=>r(s).password_confirmation=t),type:"password",class:"mt-1 block w-full",autocomplete:"new-password"},null,8,["modelValue"]),e(l,{message:r(s).errors.password_confirmation,class:"mt-2"},null,8,["message"])]),o("div",k,[e(x,{disabled:r(s).processing},{default:f(()=>[y("Salvează")]),_:1},8,["disabled"]),e(v,{"enter-active-class":"transition ease-in-out","enter-from-class":"opacity-0","leave-active-class":"transition ease-in-out","leave-to-class":"opacity-0"},{default:f(()=>[r(s).recentlySuccessful?(i(),m("p",P," Salvat. ")):V("",!0)]),_:1})])],32)]))}};export{$ as default};